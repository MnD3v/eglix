<?php

namespace App\Http\Controllers;

use App\Services\AdvancedReportService;
use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class AdvancedReportsController extends Controller
{
    /**
     * Affiche le tableau de bord des rapports avancés
     */
    public function dashboard(Request $request)
    {
        $currentYear = now()->year;
        $from = $request->get('from', $currentYear . '-01-01');
        $to = $request->get('to', $currentYear . '-12-31');
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        $comprehensiveReport = $reportService->generateComprehensiveReport();
        
        return view('reports.advanced.dashboard', compact('comprehensiveReport', 'from', 'to'));
    }

    /**
     * Génère et télécharge un rapport Excel avancé
     */
    public function exportExcel(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        $spreadsheet = $reportService->generateExcelReport();
        
        $filename = 'rapport_financier_avance_' . Carbon::parse($from)->format('Y-m-d') . '_' . Carbon::parse($to)->format('Y-m-d') . '.xlsx';
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Génère et télécharge un rapport PDF professionnel
     */
    public function exportPdf(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        $comprehensiveReport = $reportService->generateComprehensiveReport();
        
        $filename = 'rapport_financier_' . Carbon::parse($from)->format('Y-m-d') . '_' . Carbon::parse($to)->format('Y-m-d') . '.pdf';
        
        try {
            // Vérifier si DomPDF est disponible
            if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.advanced.pdf-template', compact('comprehensiveReport'))
                    ->setPaper('A4', 'portrait')
                    ->setOptions([
                        'defaultFont' => 'Arial',
                        'isRemoteEnabled' => true,
                        'isHtml5ParserEnabled' => true,
                        'isPhpEnabled' => true
                    ]);
                
                return $pdf->download($filename);
            } else {
                // Fallback : générer un HTML simple
                $html = view('reports.advanced.pdf-template', compact('comprehensiveReport'))->render();
                
                return response($html)
                    ->header('Content-Type', 'text/html')
                    ->header('Content-Disposition', 'attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un message d'erreur
            return response()->json([
                'error' => 'Erreur lors de la génération du PDF',
                'message' => $e->getMessage(),
                'suggestion' => 'Veuillez installer DomPDF: composer require barryvdh/laravel-dompdf'
            ], 500);
        }
    }

    /**
     * Export JSON pour intégration avec d'autres systèmes
     */
    public function exportJson(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        $comprehensiveReport = $reportService->generateComprehensiveReport();
        
        // Ajouter des métadonnées
        $exportData = [
            'metadata' => [
                'exported_at' => now()->toISOString(),
                'church_id' => Auth::user()->church_id,
                'period' => [
                    'from' => $from,
                    'to' => $to
                ],
                'version' => '1.0',
                'format' => 'comprehensive_financial_report'
            ],
            'data' => $comprehensiveReport
        ];
        
        $filename = 'rapport_financier_' . Carbon::parse($from)->format('Y-m-d') . '_' . Carbon::parse($to)->format('Y-m-d') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * API endpoint pour récupérer les données en temps réel
     */
    public function apiData(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        
        $dataType = $request->get('type', 'summary');
        
        switch ($dataType) {
            case 'summary':
                return response()->json($reportService->generateComprehensiveReport());
            case 'kpis':
                return response()->json(['kpis' => $reportService->getKPIs()]);
            case 'trends':
                return response()->json(['trends' => $reportService->getTrendAnalysis()]);
            case 'revenue':
                return response()->json(['revenue' => $reportService->getRevenueAnalysis()]);
            case 'expenses':
                return response()->json(['expenses' => $reportService->getExpenseAnalysis()]);
            default:
                return response()->json(['error' => 'Type de données non supporté'], 400);
        }
    }

    /**
     * Génère un rapport de comparaison entre périodes
     */
    public function comparisonReport(Request $request)
    {
        $from1 = $request->get('from1', now()->startOfYear()->format('Y-m-d'));
        $to1 = $request->get('to1', now()->endOfYear()->format('Y-m-d'));
        $from2 = $request->get('from2', now()->subYear()->startOfYear()->format('Y-m-d'));
        $to2 = $request->get('to2', now()->subYear()->endOfYear()->format('Y-m-d'));
        
        $reportService1 = new AdvancedReportService(Auth::user()->church_id, $from1, $to1);
        $reportService2 = new AdvancedReportService(Auth::user()->church_id, $from2, $to2);
        
        $report1 = $reportService1->generateComprehensiveReport();
        $report2 = $reportService2->generateComprehensiveReport();
        
        $comparison = $this->generateComparisonData($report1, $report2);
        
        return view('reports.advanced.comparison', compact('comparison', 'from1', 'to1', 'from2', 'to2'));
    }

    /**
     * Génère des données de comparaison entre deux périodes
     */
    private function generateComparisonData($report1, $report2)
    {
        return [
            'period1' => [
                'label' => 'Période 1',
                'data' => $report1
            ],
            'period2' => [
                'label' => 'Période 2', 
                'data' => $report2
            ],
            'comparison' => [
                'revenue_change' => $this->calculatePercentageChange(
                    $report2['financial_summary']['total_revenue'],
                    $report1['financial_summary']['total_revenue']
                ),
                'expense_change' => $this->calculatePercentageChange(
                    $report2['financial_summary']['total_expenses'],
                    $report1['financial_summary']['total_expenses']
                ),
                'profit_margin_change' => $this->calculatePercentageChange(
                    $report2['financial_summary']['profit_margin'],
                    $report1['financial_summary']['profit_margin']
                ),
                'member_engagement_change' => $this->calculatePercentageChange(
                    $report2['kpis']['member_engagement_rate'],
                    $report1['kpis']['member_engagement_rate']
                )
            ]
        ];
    }

    /**
     * Calcule le pourcentage de changement
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) return $newValue > 0 ? 100 : 0;
        return (($newValue - $oldValue) / $oldValue) * 100;
    }

    /**
     * Génère un rapport de projection basé sur les tendances
     */
    public function projectionReport(Request $request)
    {
        $months = $request->get('months', 12);
        $reportService = new AdvancedReportService(
            Auth::user()->church_id, 
            now()->subMonths(12)->format('Y-m-d'),
            now()->format('Y-m-d')
        );
        
        $historicalData = $reportService->generateComprehensiveReport();
        $projections = $this->generateProjections($historicalData, $months);
        
        return view('reports.advanced.projection', compact('projections', 'months'));
    }

    /**
     * Génère des projections basées sur les données historiques
     */
    private function generateProjections($historicalData, $months)
    {
        $trends = $historicalData['trends'];
        $currentData = $historicalData['financial_summary'];
        
        $projections = [];
        $currentRevenue = $currentData['total_revenue'];
        $currentExpenses = $currentData['total_expenses'];
        
        for ($i = 1; $i <= $months; $i++) {
            $month = now()->addMonths($i);
            
            // Projection basée sur la tendance de croissance
            $revenueGrowthRate = $trends['revenue_trend'] / 100;
            $expenseGrowthRate = $trends['expense_trend'] / 100;
            
            $projectedRevenue = $currentRevenue * pow(1 + $revenueGrowthRate, $i);
            $projectedExpenses = $currentExpenses * pow(1 + $expenseGrowthRate, $i);
            $projectedNetIncome = $projectedRevenue - $projectedExpenses;
            
            $projections[] = [
                'month' => $month->format('M Y'),
                'revenue' => $projectedRevenue,
                'expenses' => $projectedExpenses,
                'net_income' => $projectedNetIncome,
                'profit_margin' => $projectedRevenue > 0 ? ($projectedNetIncome / $projectedRevenue) * 100 : 0
            ];
        }
        
        return $projections;
    }

    /**
     * Export CSV optimisé pour l'analyse
     */
    public function exportCsv(Request $request)
    {
        $from = $request->get('from', now()->startOfYear()->format('Y-m-d'));
        $to = $request->get('to', now()->endOfYear()->format('Y-m-d'));
        $type = $request->get('type', 'all');
        
        $reportService = new AdvancedReportService(Auth::user()->church_id, $from, $to);
        $comprehensiveReport = $reportService->generateComprehensiveReport();
        
        $filename = 'rapport_analyse_' . $type . '_' . Carbon::parse($from)->format('Y-m-d') . '_' . Carbon::parse($to)->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($comprehensiveReport, $type) {
            $file = fopen('php://output', 'w');
            
            // Ajouter BOM pour Excel
            fwrite($file, "\xEF\xBB\xBF");
            
            switch ($type) {
                case 'financial_summary':
                    $this->exportFinancialSummaryCsv($file, $comprehensiveReport);
                    break;
                case 'revenue_analysis':
                    $this->exportRevenueAnalysisCsv($file, $comprehensiveReport);
                    break;
                case 'expense_analysis':
                    $this->exportExpenseAnalysisCsv($file, $comprehensiveReport);
                    break;
                case 'member_analysis':
                    $this->exportMemberAnalysisCsv($file, $comprehensiveReport);
                    break;
                case 'kpis':
                    $this->exportKPIsCsv($file, $comprehensiveReport);
                    break;
                default:
                    $this->exportAllDataCsv($file, $comprehensiveReport);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export du résumé financier en CSV
     */
    private function exportFinancialSummaryCsv($file, $report)
    {
        fputcsv($file, ['RÉSUMÉ FINANCIER']);
        fputcsv($file, ['Période', $report['period']['from'] . ' - ' . $report['period']['to']]);
        fputcsv($file, []);
        
        fputcsv($file, ['REVENUS']);
        fputcsv($file, ['Dîmes', number_format($report['financial_summary']['revenue_breakdown']['tithes'], 0, ',', ' ')]);
        fputcsv($file, ['Offrandes', number_format($report['financial_summary']['revenue_breakdown']['offerings'], 0, ',', ' ')]);
        fputcsv($file, ['Dons', number_format($report['financial_summary']['revenue_breakdown']['donations'], 0, ',', ' ')]);
        fputcsv($file, ['Total Revenus', number_format($report['financial_summary']['total_revenue'], 0, ',', ' ')]);
        fputcsv($file, []);
        
        fputcsv($file, ['DÉPENSES']);
        fputcsv($file, ['Total Dépenses', number_format($report['financial_summary']['total_expenses'], 0, ',', ' ')]);
        fputcsv($file, []);
        
        fputcsv($file, ['RÉSULTAT']);
        fputcsv($file, ['Résultat Net', number_format($report['financial_summary']['net_income'], 0, ',', ' ')]);
        fputcsv($file, ['Marge Bénéficiaire (%)', number_format($report['financial_summary']['profit_margin'], 2)]);
        fputcsv($file, ['Ratio Dépenses (%)', number_format($report['financial_summary']['expense_ratio'], 2)]);
    }

    /**
     * Export de l'analyse des revenus en CSV
     */
    private function exportRevenueAnalysisCsv($file, $report)
    {
        fputcsv($file, ['ANALYSE DES REVENUS']);
        fputcsv($file, ['Période', $report['period']['from'] . ' - ' . $report['period']['to']]);
        fputcsv($file, []);
        
        fputcsv($file, ['DÎMES PAR MOIS']);
        fputcsv($file, ['Mois', 'Montant', 'Nombre de transactions']);
        foreach ($report['revenue_analysis']['monthly_tithes'] as $month) {
            fputcsv($file, [$month->month, number_format($month->total, 0, ',', ' '), $month->count]);
        }
        fputcsv($file, []);
        
        fputcsv($file, ['OFFRANDES PAR TYPE']);
        fputcsv($file, ['Type', 'Montant', 'Nombre']);
        foreach ($report['revenue_analysis']['offering_by_type'] as $offering) {
            fputcsv($file, [$offering->type, number_format($offering->total, 0, ',', ' '), $offering->count]);
        }
        fputcsv($file, []);
        
        fputcsv($file, ['MÉTHODES DE PAIEMENT']);
        fputcsv($file, ['Méthode', 'Montant']);
        foreach ($report['revenue_analysis']['payment_methods'] as $method => $amount) {
            fputcsv($file, [$method, number_format($amount, 0, ',', ' ')]);
        }
    }

    /**
     * Export de l'analyse des dépenses en CSV
     */
    private function exportExpenseAnalysisCsv($file, $report)
    {
        fputcsv($file, ['ANALYSE DES DÉPENSES']);
        fputcsv($file, ['Période', $report['period']['from'] . ' - ' . $report['period']['to']]);
        fputcsv($file, []);
        
        fputcsv($file, ['DÉPENSES PAR PROJET']);
        fputcsv($file, ['Projet', 'Montant', 'Nombre']);
        foreach ($report['expense_analysis']['by_project'] as $expense) {
            $projectName = $expense->project ? $expense->project->name : 'Aucun projet';
            fputcsv($file, [$projectName, number_format($expense->total, 0, ',', ' '), $expense->count]);
        }
        fputcsv($file, []);
        
        fputcsv($file, ['DÉPENSES MENSUELLES']);
        fputcsv($file, ['Mois', 'Montant']);
        foreach ($report['expense_analysis']['monthly'] as $month) {
            fputcsv($file, [$month->month, number_format($month->total, 0, ',', ' ')]);
        }
    }

    /**
     * Export de l'analyse des membres en CSV
     */
    private function exportMemberAnalysisCsv($file, $report)
    {
        fputcsv($file, ['ANALYSE DES CONTRIBUTIONS DES MEMBRES']);
        fputcsv($file, ['Période', $report['period']['from'] . ' - ' . $report['period']['to']]);
        fputcsv($file, []);
        
        fputcsv($file, ['TOP CONTRIBUTEURS']);
        fputcsv($file, ['Membre', 'Montant Total', 'Nombre de contributions']);
        foreach ($report['member_contribution']['top_contributors'] as $contributor) {
            $memberName = $contributor->member ? 
                $contributor->member->last_name . ' ' . $contributor->member->first_name : 
                'Membre inconnu';
            fputcsv($file, [$memberName, number_format($contributor->total, 0, ',', ' '), $contributor->count]);
        }
        fputcsv($file, []);
        
        fputcsv($file, ['STATISTIQUES']);
        fputcsv($file, ['Métrique', 'Valeur']);
        fputcsv($file, ['Total contributeurs', $report['member_contribution']['statistics']['total_contributors']]);
        fputcsv($file, ['Contribution moyenne', number_format($report['member_contribution']['statistics']['average_contribution'], 0, ',', ' ')]);
        fputcsv($file, ['Contribution médiane', number_format($report['member_contribution']['statistics']['median_contribution'], 0, ',', ' ')]);
        fputcsv($file, ['Score de régularité', number_format($report['member_contribution']['statistics']['consistency_score'], 2)]);
    }

    /**
     * Export des KPIs en CSV
     */
    private function exportKPIsCsv($file, $report)
    {
        fputcsv($file, ['INDICATEURS DE PERFORMANCE CLÉS (KPIs)']);
        fputcsv($file, ['Période', $report['period']['from'] . ' - ' . $report['period']['to']]);
        fputcsv($file, []);
        
        fputcsv($file, ['KPI', 'Valeur', 'Unité']);
        fputcsv($file, ['Score de santé financière', number_format($report['kpis']['financial_health_score'], 2), '%']);
        fputcsv($file, ['Taux d\'engagement des membres', number_format($report['kpis']['member_engagement_rate'], 2), '%']);
        fputcsv($file, ['Revenus par membre', number_format($report['kpis']['revenue_per_member'], 0, ',', ' '), 'FCFA']);
        fputcsv($file, ['Efficacité des dépenses', number_format($report['kpis']['expense_efficiency'], 2), 'Ratio']);
        fputcsv($file, ['Momentum de croissance', number_format($report['kpis']['growth_momentum'], 2), '%']);
        fputcsv($file, ['Index de durabilité', number_format($report['kpis']['sustainability_index'], 2), '%']);
    }

    /**
     * Export de toutes les données en CSV
     */
    private function exportAllDataCsv($file, $report)
    {
        $this->exportFinancialSummaryCsv($file, $report);
        fputcsv($file, []);
        $this->exportRevenueAnalysisCsv($file, $report);
        fputcsv($file, []);
        $this->exportExpenseAnalysisCsv($file, $report);
        fputcsv($file, []);
        $this->exportMemberAnalysisCsv($file, $report);
        fputcsv($file, []);
        $this->exportKPIsCsv($file, $report);
    }
}
