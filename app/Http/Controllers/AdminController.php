<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Vérifier l'accès admin
     */
    private function checkAdminAccess()
    {
        if (!Auth::check() || Auth::user()->email !== 'em.djatika@gmail.com') {
            abort(403, 'Accès non autorisé.');
        }
    }

    /**
     * Afficher le tableau de bord d'administration
     */
    public function index(Request $request)
    {
        $this->checkAdminAccess();
        $search = trim((string) $request->get('q'));
        $statusFilter = $request->get('status');
        $subscriptionFilter = $request->get('subscription');

        $query = Church::with('users');

        // Recherche
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre par statut d'abonnement
        if ($subscriptionFilter) {
            switch ($subscriptionFilter) {
                case 'active':
                    $query->where('subscription_status', 'active')
                          ->where('subscription_end_date', '>=', now());
                    break;
                case 'expired':
                    $query->where(function($q) {
                        $q->where('subscription_status', 'expired')
                          ->orWhere('subscription_end_date', '<', now());
                    });
                    break;
                case 'suspended':
                    $query->where('subscription_status', 'suspended');
                    break;
                case 'no_subscription':
                    $query->whereNull('subscription_end_date');
                    break;
            }
        }

        $churches = $query->paginate(15)->withQueryString();

        // Statistiques globales
        $stats = [
            'total_churches' => Church::count(),
            'active_subscriptions' => Church::where('subscription_status', 'active')
                ->where('subscription_end_date', '>=', now())
                ->count(),
            'expired_subscriptions' => Church::where(function($q) {
                $q->where('subscription_status', 'expired')
                  ->orWhere('subscription_end_date', '<', now());
            })->count(),
            'suspended_subscriptions' => Church::where('subscription_status', 'suspended')->count(),
            'total_revenue' => Church::whereNotNull('subscription_amount')->sum('subscription_amount'),
            'churches_without_subscription' => Church::whereNull('subscription_end_date')->count(),
        ];

        // Données pour les filtres
        $subscriptionStatuses = [
            'active' => 'Abonnements actifs',
            'expired' => 'Abonnements expirés',
            'suspended' => 'Abonnements suspendus',
            'no_subscription' => 'Sans abonnement'
        ];

        return view('admin.index', compact(
            'churches',
            'search',
            'statusFilter',
            'subscriptionFilter',
            'stats',
            'subscriptionStatuses'
        ));
    }

    /**
     * Afficher les détails d'une église
     */
    public function showChurch(Church $church)
    {
        $this->checkAdminAccess();
        $church->load('users');
        return view('admin.church-details', compact('church'));
    }

    /**
     * Afficher le formulaire pour créer un abonnement pour une église
     */
    public function createSubscription(Church $church)
    {
        $this->checkAdminAccess();
        return view('admin.create-subscription', compact('church'));
    }

    /**
     * Créer un abonnement pour une église
     */
    public function storeSubscription(Request $request, Church $church)
    {
        $this->checkAdminAccess();
        $validated = $request->validate([
            'subscription_plan' => 'required|in:basic,premium,enterprise',
            'subscription_start_date' => 'required|date',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
            'payment_reference' => 'nullable|string|max:100',
            'subscription_notes' => 'nullable|string',
        ]);

        // Déterminer le montant selon le plan
        $amounts = [
            'basic' => 39000,
            'premium' => 39000,
            'enterprise' => 39000
        ];

        // Mettre à jour l'église avec les informations d'abonnement
        $church->update([
            'subscription_start_date' => $validated['subscription_start_date'],
            'subscription_end_date' => $validated['subscription_end_date'],
            'subscription_status' => 'active',
            'subscription_plan' => $validated['subscription_plan'],
            'subscription_amount' => $amounts[$validated['subscription_plan']],
            'subscription_notes' => $validated['subscription_notes'],
            'payment_reference' => $validated['payment_reference'],
            'payment_date' => now(),
        ]);

        return redirect()->route('admin.index')->with('success', 'Abonnement attribué avec succès à l\'église ' . $church->name);
    }

    /**
     * Marquer un abonnement comme payé
     */
    public function markSubscriptionPaid(Request $request, Church $church)
    {
        $this->checkAdminAccess();
        $validated = $request->validate([
            'payment_reference' => 'nullable|string|max:100',
            'subscription_notes' => 'nullable|string',
        ]);

        $church->update([
            'subscription_status' => 'active',
            'payment_reference' => $validated['payment_reference'],
            'subscription_notes' => $validated['subscription_notes'],
            'payment_date' => now(),
        ]);

        return redirect()->route('admin.index')->with('success', 'Abonnement marqué comme payé pour l\'église ' . $church->name);
    }

    /**
     * Suspendre un abonnement
     */
    public function suspendSubscription(Request $request, Church $church)
    {
        $this->checkAdminAccess();
        $validated = $request->validate([
            'subscription_notes' => 'nullable|string',
        ]);

        $church->update([
            'subscription_status' => 'suspended',
            'subscription_notes' => $validated['subscription_notes'],
        ]);

        return redirect()->route('admin.index')->with('success', 'Abonnement suspendu pour l\'église ' . $church->name);
    }

    /**
     * Renouveler un abonnement
     */
    public function renewSubscription(Request $request, Church $church)
    {
        $this->checkAdminAccess();
        $validated = $request->validate([
            'subscription_end_date' => 'required|date|after:today',
            'subscription_amount' => 'required|numeric|min:0',
            'subscription_notes' => 'nullable|string',
        ]);

        $church->update([
            'subscription_end_date' => $validated['subscription_end_date'],
            'subscription_status' => 'active',
            'subscription_amount' => $validated['subscription_amount'],
            'subscription_notes' => $validated['subscription_notes'],
            'payment_date' => now(),
        ]);

        return redirect()->route('admin.index')->with('success', 'Abonnement renouvelé pour l\'église ' . $church->name);
    }

    /**
     * Exporter les données des églises
     */
    public function exportChurches(Request $request)
    {
        $this->checkAdminAccess();
        $churches = Church::with('users')->get();

        $data = [];
        foreach ($churches as $church) {
            $data[] = [
                'Nom de l\'église' => $church->name,
                'Adresse' => $church->address,
                'Téléphone' => $church->phone,
                'Email' => $church->email,
                'Nombre d\'utilisateurs' => $church->users->count(),
                'Plan actuel' => $church->subscription_plan ? ucfirst($church->subscription_plan) : 'Aucun',
                'Montant' => $church->subscription_amount ? number_format($church->subscription_amount, 0, ',', ' ') . ' ' . $church->subscription_currency : 'N/A',
                'Date de fin' => $church->subscription_end_date ? $church->subscription_end_date->format('d/m/Y') : 'N/A',
                'Statut' => $church->subscription_status ? ucfirst($church->subscription_status) : 'Sans abonnement',
                'Créé le' => $church->created_at->format('d/m/Y'),
            ];
        }

        $filename = 'eglises_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            if (!empty($data)) {
                fputcsv($file, array_keys($data[0]));
            }
            
            // Données
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
