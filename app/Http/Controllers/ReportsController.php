<?php

namespace App\Http\Controllers;

use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Par défaut, afficher l'année en cours
        $currentYear = now()->year;
        $from = $request->get('from', $currentYear . '-01-01');
        $to = $request->get('to', $currentYear . '-12-31');
        
        return view('reports.index', compact('from', 'to'));
    }

    public function tithes(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        // Validation des dates
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        // Récupérer les dîmes pour la période et pour l'église de l'utilisateur connecté
        $tithes = Tithe::with('member')
            ->forChurch() // Utilisation du scope du trait BelongsToChurch
            ->whereBetween('paid_at', [$from, $to])
            ->orderBy('paid_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $totalTithes = $tithes->sum('amount');
        $totalMembers = $tithes->unique('member_id')->count();
        $averageTithe = $totalMembers > 0 ? $totalTithes / $totalMembers : 0;
        $totalMonths = $tithes->groupBy(function($tithe) {
            return $tithe->paid_at->format('Y-m');
        })->count();
        
        // Données pour le graphique mensuel
        $monthlyData = $this->getMonthlyData($tithes, $from, $to, 'paid_at', 'amount');
        
        // Données pour le graphique par membre
        $memberData = $tithes->groupBy('member_id')
            ->map(function ($memberTithes) {
                return [
                    'name' => $memberTithes->first()->member->last_name . ' ' . $memberTithes->first()->member->first_name,
                    'amount' => $memberTithes->sum('amount'),
                    'count' => $memberTithes->count()
                ];
            })
            ->sortByDesc('amount')
            ->take(10)
            ->values();
        
        return view('reports.tithes', compact(
            'tithes', 'totalTithes', 'totalMembers', 'averageTithe', 'totalMonths',
            'monthlyData', 'memberData', 'from', 'to'
        ));
    }

    public function offerings(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        // Validation des dates
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        // Récupérer les offrandes pour la période et pour l'église de l'utilisateur connecté
        $offerings = Offering::with('member')
            ->forChurch() // Utilisation du scope du trait BelongsToChurch
            ->whereBetween('received_at', [$from, $to])
            ->orderBy('received_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $totalAmount = $offerings->sum('amount');
        $totalCount = $offerings->count();
        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        $totalMonths = $offerings->groupBy(function($offering) {
            return $offering->received_at->format('Y-m');
        })->count();
        
        // Données pour le graphique mensuel
        $monthlyData = $this->getMonthlyData($offerings, $from, $to, 'received_at', 'amount');
        
        // Données pour le graphique par type d'offrande
        $typeData = $offerings->groupBy('type')
            ->map(function ($typeOfferings, $type) {
                return [
                    'name' => $type,
                    'amount' => $typeOfferings->sum('amount'),
                    'count' => $typeOfferings->count()
                ];
            })
            ->sortByDesc('amount')
            ->values();
        
        return view('reports.offerings', compact(
            'offerings', 'totalAmount', 'totalCount', 'averageAmount', 'totalMonths',
            'monthlyData', 'typeData', 'from', 'to'
        ));
    }

    public function donations(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        // Validation des dates
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        // Récupérer les dons pour la période et pour l'église de l'utilisateur connecté
        $donations = Donation::with('member', 'project')
            ->forChurch() // Utilisation du scope du trait BelongsToChurch
            ->whereBetween('received_at', [$from, $to])
            ->orderBy('received_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $totalAmount = $donations->sum('amount');
        $totalCount = $donations->count();
        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        $totalMonths = $donations->groupBy(function($donation) {
            return $donation->received_at->format('Y-m');
        })->count();
        
        // Données pour le graphique mensuel
        $monthlyData = $this->getMonthlyData($donations, $from, $to, 'received_at', 'amount');
        
        // Données pour le graphique par type de don
        $typeData = $donations->groupBy('donation_type')
            ->map(function ($typeDonations, $type) {
                return [
                    'name' => ucfirst($type),
                    'amount' => $typeDonations->sum('amount'),
                    'count' => $typeDonations->count()
                ];
            })
            ->sortByDesc('amount')
            ->values();
        
        return view('reports.donations', compact(
            'donations', 'totalAmount', 'totalCount', 'averageAmount', 
            'monthlyData', 'typeData', 'from', 'to'
        ));
    }

    public function expenses(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        // Validation des dates
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        // Récupérer les dépenses pour la période et pour l'église de l'utilisateur connecté
        $expenses = Expense::with('project')
            ->forChurch() // Utilisation du scope du trait BelongsToChurch
            ->whereBetween('paid_at', [$from, $to])
            ->orderBy('paid_at', 'desc')
            ->get();
        
        // Calculer les statistiques
        $totalAmount = $expenses->sum('amount');
        $totalCount = $expenses->count();
        $averageAmount = $totalCount > 0 ? $totalAmount / $totalCount : 0;
        $totalMonths = $expenses->groupBy(function($expense) {
            return $expense->paid_at->format('Y-m');
        })->count();
        
        // Données pour le graphique mensuel
        $monthlyData = $this->getMonthlyData($expenses, $from, $to, 'paid_at', 'amount');
        
        // Données pour le graphique par catégorie
        $categoryData = $expenses->groupBy('category')
            ->map(function ($categoryExpenses, $category) {
                return [
                    'name' => ucfirst($category),
                    'amount' => $categoryExpenses->sum('amount'),
                    'count' => $categoryExpenses->count()
                ];
            })
            ->sortByDesc('amount')
            ->values();
        
        return view('reports.expenses', compact(
            'expenses', 'totalAmount', 'totalCount', 'averageAmount', 
            'monthlyData', 'categoryData', 'from', 'to'
        ));
    }

    private function getMonthlyData($collection, $from, $to, $dateField, $amountField)
    {
        $driver = DB::getDriverName();
        $monthExpr = match ($driver) {
            'mysql', 'mariadb' => "DATE_FORMAT($dateField, '%Y-%m')",
            'pgsql' => "to_char($dateField, 'YYYY-MM')",
            default => "strftime('%Y-%m', $dateField)", // sqlite & others
        };

        $monthlyData = $collection->groupBy(function ($item) use ($dateField) {
            return $item->$dateField->format('Y-m');
        })->map(function ($monthItems, $month) use ($amountField) {
            return [
                'month' => $month,
                'amount' => $monthItems->sum($amountField),
                'count' => $monthItems->count()
            ];
        })->sortBy('month');

        // Créer les labels numériques (1-12)
        $labels = [];
        $data = [];
        $counts = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthKey = $from->year . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $labels[] = $i;
            $data[] = $monthlyData->get($monthKey, ['amount' => 0])['amount'];
            $counts[] = $monthlyData->get($monthKey, ['count' => 0])['count'];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'counts' => $counts
        ];
    }

    // Méthodes d'export
    public function exportTithes(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        $tithes = Tithe::with('member')
            ->forChurch() // Filtrer par église
            ->whereBetween('paid_at', [$from, $to])
            ->orderBy('paid_at', 'desc')
            ->get();
        
        $filename = 'rapport_dimes_' . $from->format('Y-m-d') . '_' . $to->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($tithes) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Membre', 'Date', 'Montant (FCFA)', 'Méthode de paiement', 'Référence']);
            
            // Données
            foreach ($tithes as $tithe) {
                fputcsv($file, [
                    $tithe->member->last_name . ' ' . $tithe->member->first_name,
                    $tithe->paid_at->format('d/m/Y'),
                    number_format($tithe->amount, 0, ',', ' '),
                    ucfirst($tithe->payment_method),
                    $tithe->reference ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportOfferings(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        $offerings = Offering::with('member')
            ->forChurch() // Filtrer par église
            ->whereBetween('received_at', [$from, $to])
            ->orderBy('received_at', 'desc')
            ->get();
        
        $filename = 'rapport_offrandes_' . $from->format('Y-m-d') . '_' . $to->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($offerings) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Membre', 'Type', 'Date', 'Montant (FCFA)', 'Méthode de paiement', 'Référence']);
            
            // Données
            foreach ($offerings as $offering) {
                fputcsv($file, [
                    $offering->member->last_name . ' ' . $offering->member->first_name,
                    $offering->type,
                    $offering->received_at->format('d/m/Y'),
                    number_format($offering->amount, 0, ',', ' '),
                    ucfirst($offering->payment_method),
                    $offering->reference ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportDonations(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        $donations = Donation::with('member', 'project')
            ->forChurch() // Filtrer par église
            ->whereBetween('received_at', [$from, $to])
            ->orderBy('received_at', 'desc')
            ->get();
        
        $filename = 'rapport_dons_' . $from->format('Y-m-d') . '_' . $to->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Membre', 'Type', 'Date', 'Montant (FCFA)', 'Projet', 'Méthode de paiement', 'Référence']);
            
            // Données
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->member->last_name . ' ' . $donation->member->first_name,
                    ucfirst($donation->donation_type),
                    $donation->received_at->format('d/m/Y'),
                    number_format($donation->amount, 0, ',', ' '),
                    $donation->project ? $donation->project->name : 'Aucun',
                    ucfirst($donation->payment_method),
                    $donation->reference ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportExpenses(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();
        
        $expenses = Expense::with('project')
            ->forChurch() // Filtrer par église
            ->whereBetween('paid_at', [$from, $to])
            ->orderBy('paid_at', 'desc')
            ->get();
        
        $filename = 'rapport_depenses_' . $from->format('Y-m-d') . '_' . $to->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($expenses) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, ['Description', 'Projet', 'Date', 'Montant (FCFA)', 'Méthode de paiement', 'Référence']);
            
            // Données
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->description,
                    $expense->project ? $expense->project->name : 'Aucun',
                    $expense->paid_at->format('d/m/Y'),
                    number_format($expense->amount, 0, ',', ' '),
                    ucfirst($expense->payment_method),
                    $expense->reference ?? ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}