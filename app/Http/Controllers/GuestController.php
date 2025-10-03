<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuestController extends Controller
{
    /**
     * Afficher la liste des invités
     */
    public function index(Request $request)
    {
        // Vérification des permissions
        if (!Auth::user()->hasPermission('members.read', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        // Paramètres de filtrage
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');
        $origin = $request->get('origin');
        
        // Définir les dates par défaut si non spécifiées (ce mois-ci)
        if (!$startDate) {
            $startDate = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$endDate) {
            $endDate = now()->endOfMonth()->format('Y-m-d');
        }

        // Requête de base
        $query = Guest::where('church_id', Auth::user()->church_id);

        // Statistiques globales
        $stats = [
            'total' => Guest::where('church_id', Auth::user()->church_id)->count(),
            'this_month' => Guest::where('church_id', Auth::user()->church_id)
                ->whereBetween('visit_date', [$startDate, $endDate])->count(),
            'first_time' => Guest::where('church_id', Auth::user()->church_id)
                ->firstTime()->whereBetween('visit_date', [$startDate, $endDate])->count(),
            'conversions' => Guest::where('church_id', Auth::user()->church_id)
                ->converted()->whereBetween('visit_date', [$startDate, $endDate])->count(),
            'returning' => Guest::where('church_id', Auth::user()->church_id)
                ->returning()->whereBetween('visit_date', [$startDate, $endDate])->count(),
        ];

        // Graphique des données mensuelles (6 derniers mois)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthStats = Guest::getMonthlyStats($monthDate->year, $monthDate->month);
            
            $chartData[] = [
                'month' => $monthDate->format('M Y'),
                'first_time' => $monthStats->first_time,
                'conversions' => $monthStats->conversions,
                'total' => $monthStats->total_guests,
            ];
        }

        // Application des filtres
        if ($status) {
            $query = $query->status($status);
        }
        
        if ($origin) {
            $query = $query->where('origin', $origin);
        }

        // Filtrage par dates
        if ($startDate && $endDate) {
            $query = $query->whereBetween('visit_date', [$startDate, $endDate]);
        }

        // Pagination
        $guests = $query->orderBy('visit_date', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(15);

        return view('guests.index', compact(
            'guests', 
            'stats', 
            'chartData', 
            'startDate', 
            'endDate', 
            'status', 
            'origin'
        ));
    }

    /**
     * Afficher le formulaire de création d'invité
     */
    public function create()
    {
        if (!Auth::user()->hasPermission('members.create', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        return view('guests.create');
    }

    /**
     * Enregistrer un nouvel invité
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasPermission('members.create', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'visit_date' => ['required', 'date'],
            'origin' => ['required', 'string', 'in:referral,social_media,event,walk_in,flyer,other'],
            'church_background' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:visit_1,visit_2_3,returning,member_converted,no_longer_interested'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Guest::create($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Invité enregistré avec succès.');
    }

    /**
     * Afficher les détails d'un invité
     */
    public function show(Guest $guest)
    {
        if ($guest->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }

        if (!Auth::user()->hasPermission('members.read', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        return view('guests.show', compact('guest'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Guest $guest)
    {
        if ($guest->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }

        if (!Auth::user()->hasPermission('members.update', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        return view('guests.edit', compact('guest'));
    }

    /**
     * Mettre à jour un invité
     */
    public function update(Request $request, Guest $guest)
    {
        if ($guest->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }

        if (!Auth::user()->hasPermission('members.update', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'visit_date' => ['required', 'date'],
            'origin' => ['required', 'string', 'in:referral,social_media,event,walk_in,flyer,other'],
            'church_background' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:visit_1,visit_2_3,returning,member_converted,no_longer_interested'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);


        $guest->update($validated);

        return redirect()->route('guests.index')
            ->with('success', 'Invité modifié avec succès.');
    }

    /**
     * Supprimer un invité
     */
    public function destroy(Guest $guest)
    {
        if ($guest->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }

        if (!Auth::user()->hasPermission('members.delete', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        $guest->delete();

        return redirect()->route('guests.index')
            ->with('success', 'Invité supprimé avec succès.');
    }

    /**
     * API: Obtenir les données pour les graphiques
     */
    public function chartData(Request $request)
    {
        $year = $request->get('year', now()->year);
        $monthsBack = $request->get('months_back', 12);
        
        $chartData = [];
        
        for ($i = $monthsBack - 1; $i >= 0; $i--) {
            $monthDate = now()->year($year)->subMonths($i);
            $monthStats = Guest::getMonthlyStats($monthDate->year, $monthDate->month);
            
            $chartData[] = [
                'month' => $monthDate->format('M Y'),
                'total' => $monthStats->total_guests,
                'first_time' => $monthStats->first_time,
                'conversions' => $monthStats->conversions,
                'returning' => $monthStats->return_visits,
            ];
        }

        return response()->json($chartData);
    }

    /**
     * Marquer un invité comme converti en membre
     */
    public function convertToMember(Guest $guest)
    {
        if ($guest->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }

        if (!Auth::user()->hasPermission('members.update', Auth::user()->church_id)) {
            abort(403, 'Accès non autorisé');
        }

        $guest->update(['status' => 'member_converted']);

        return redirect()->route('guests.show', $guest)
            ->with('success', 'Invité marqué comme converti en membre.');
    }
}