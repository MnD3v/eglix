<?php

namespace App\Services;

use App\Models\Tithe;
use App\Models\Offering;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class AdvancedReportService
{
    private $churchId;
    private $from;
    private $to;

    public function __construct($churchId, $from, $to)
    {
        $this->churchId = $churchId;
        $this->from = Carbon::parse($from)->startOfDay();
        $this->to = Carbon::parse($to)->endOfDay();
    }

    /**
     * Génère un rapport financier complet avec analyses avancées
     */
    public function generateComprehensiveReport()
    {
        return [
            'period' => [
                'from' => $this->from->format('d/m/Y'),
                'to' => $this->to->format('d/m/Y'),
                'days' => $this->from->diffInDays($this->to) + 1
            ],
            'financial_summary' => $this->getFinancialSummary(),
            'revenue_analysis' => $this->getRevenueAnalysis(),
            'expense_analysis' => $this->getExpenseAnalysis(),
            'member_contribution' => $this->getMemberContributionAnalysis(),
            'project_analysis' => $this->getProjectAnalysis(),
            'trends' => $this->getTrendAnalysis(),
            'kpis' => $this->getKPIs(),
            'recommendations' => $this->getRecommendations()
        ];
    }

    /**
     * Résumé financier global
     */
    private function getFinancialSummary()
    {
        $tithes = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->sum('amount');

        $offerings = Offering::forChurch($this->churchId)
            ->whereBetween('received_at', [$this->from, $this->to])
            ->sum('amount');

        $donations = Donation::forChurch($this->churchId)
            ->whereBetween('received_at', [$this->from, $this->to])
            ->sum('amount');

        $expenses = Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->sum('amount');

        $totalRevenue = $tithes + $offerings + $donations;
        $netIncome = $totalRevenue - $expenses;

        return [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $expenses,
            'net_income' => $netIncome,
            'revenue_breakdown' => [
                'tithes' => $tithes,
                'offerings' => $offerings,
                'donations' => $donations
            ],
            'profit_margin' => $totalRevenue > 0 ? ($netIncome / $totalRevenue) * 100 : 0,
            'expense_ratio' => $totalRevenue > 0 ? ($expenses / $totalRevenue) * 100 : 0
        ];
    }

    /**
     * Analyse des revenus détaillée
     */
    public function getRevenueAnalysis()
    {
        // Analyse des dîmes par mois
        $monthlyTithes = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Analyse des offrandes par type
        $offeringByType = Offering::forChurch($this->churchId)
            ->whereBetween('received_at', [$this->from, $this->to])
            ->selectRaw('type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Analyse des dons par projet
        $donationsByProject = Donation::forChurch($this->churchId)
            ->whereBetween('received_at', [$this->from, $this->to])
            ->with('project')
            ->selectRaw('project_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('project_id')
            ->get();

        // Méthodes de paiement
        $paymentMethods = DB::table('tithes')
            ->where('church_id', $this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->union(
                DB::table('offerings')
                    ->where('church_id', $this->churchId)
                    ->whereBetween('received_at', [$this->from, $this->to])
                    ->selectRaw('payment_method, SUM(amount) as total')
                    ->groupBy('payment_method')
            )
            ->union(
                DB::table('donations')
                    ->where('church_id', $this->churchId)
                    ->whereBetween('received_at', [$this->from, $this->to])
                    ->selectRaw('payment_method, SUM(amount) as total')
                    ->groupBy('payment_method')
            )
            ->get()
            ->groupBy('payment_method')
            ->map(function ($group) {
                return $group->sum('total');
            });

        return [
            'monthly_tithes' => $monthlyTithes,
            'offering_by_type' => $offeringByType,
            'donations_by_project' => $donationsByProject,
            'payment_methods' => $paymentMethods,
            'average_transaction' => $this->getAverageTransactionSize(),
            'growth_rate' => $this->calculateGrowthRate()
        ];
    }

    /**
     * Analyse des dépenses
     */
    public function getExpenseAnalysis()
    {
        $expensesByProject = Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->with('project')
            ->selectRaw('project_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('project_id')
            ->get();

        $monthlyExpenses = Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'by_project' => $expensesByProject,
            'monthly' => $monthlyExpenses,
            'average_expense' => $this->getAverageExpenseSize(),
            'expense_categories' => $this->getExpenseCategories()
        ];
    }

    /**
     * Analyse des contributions des membres
     */
    private function getMemberContributionAnalysis()
    {
        $topContributors = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->with('member')
            ->selectRaw('member_id, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('member_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $memberStats = [
            'total_contributors' => Tithe::forChurch($this->churchId)
                ->whereBetween('paid_at', [$this->from, $this->to])
                ->distinct('member_id')
                ->count('member_id'),
            'average_contribution' => Tithe::forChurch($this->churchId)
                ->whereBetween('paid_at', [$this->from, $this->to])
                ->avg('amount'),
            'median_contribution' => $this->getMedianContribution(),
            'consistency_score' => $this->getConsistencyScore()
        ];

        return [
            'top_contributors' => $topContributors,
            'statistics' => $memberStats,
            'contribution_distribution' => $this->getContributionDistribution()
        ];
    }

    /**
     * Analyse des projets
     */
    private function getProjectAnalysis()
    {
        $projects = Project::forChurch($this->churchId)->get();
        
        $projectAnalysis = $projects->map(function ($project) {
            $donations = Donation::forChurch($this->churchId)
                ->where('project_id', $project->id)
                ->whereBetween('received_at', [$this->from, $this->to])
                ->sum('amount');

            $expenses = Expense::forChurch($this->churchId)
                ->where('project_id', $project->id)
                ->whereBetween('paid_at', [$this->from, $this->to])
                ->sum('amount');

            return [
                'project' => $project,
                'donations' => $donations,
                'expenses' => $expenses,
                'net_income' => $donations - $expenses,
                'roi' => $expenses > 0 ? (($donations - $expenses) / $expenses) * 100 : 0
            ];
        });

        return $projectAnalysis->sortByDesc('net_income');
    }

    /**
     * Analyse des tendances
     */
    public function getTrendAnalysis()
    {
        $previousPeriod = $this->getPreviousPeriodData();
        $currentPeriod = $this->getCurrentPeriodData();

        return [
            'revenue_trend' => $this->calculateTrend($previousPeriod['revenue'], $currentPeriod['revenue']),
            'expense_trend' => $this->calculateTrend($previousPeriod['expenses'], $currentPeriod['expenses']),
            'member_growth' => $this->calculateTrend($previousPeriod['members'], $currentPeriod['members']),
            'seasonality' => $this->analyzeSeasonality()
        ];
    }

    /**
     * Indicateurs de performance clés (KPIs)
     */
    public function getKPIs()
    {
        $summary = $this->getFinancialSummary();
        $memberAnalysis = $this->getMemberContributionAnalysis();

        return [
            'financial_health_score' => $this->calculateFinancialHealthScore(),
            'member_engagement_rate' => $this->calculateMemberEngagementRate(),
            'revenue_per_member' => $memberAnalysis['statistics']['total_contributors'] > 0 
                ? $summary['total_revenue'] / $memberAnalysis['statistics']['total_contributors'] 
                : 0,
            'expense_efficiency' => $this->calculateExpenseEfficiency(),
            'growth_momentum' => $this->calculateGrowthMomentum(),
            'sustainability_index' => $this->calculateSustainabilityIndex()
        ];
    }

    /**
     * Recommandations basées sur l'analyse
     */
    public function getRecommendations()
    {
        $kpis = $this->getKPIs();
        $summary = $this->getFinancialSummary();
        $recommendations = [];

        // Recommandations basées sur la santé financière
        if ($kpis['financial_health_score'] < 60) {
            $recommendations[] = [
                'type' => 'financial_health',
                'priority' => 'high',
                'title' => 'Améliorer la santé financière',
                'description' => 'Le score de santé financière est faible. Considérez réduire les dépenses ou augmenter les revenus.',
                'action' => 'Analyser les dépenses non essentielles et lancer une campagne de sensibilisation aux dîmes.'
            ];
        }

        // Recommandations basées sur l'engagement des membres
        if ($kpis['member_engagement_rate'] < 30) {
            $recommendations[] = [
                'type' => 'member_engagement',
                'priority' => 'medium',
                'title' => 'Augmenter l\'engagement des membres',
                'description' => 'Moins de 30% des membres contribuent régulièrement.',
                'action' => 'Organiser des sessions d\'enseignement sur la dîme et créer des programmes d\'engagement.'
            ];
        }

        // Recommandations basées sur les marges
        if ($summary['profit_margin'] < 10) {
            $recommendations[] = [
                'type' => 'profitability',
                'priority' => 'high',
                'title' => 'Améliorer la rentabilité',
                'description' => 'La marge bénéficiaire est inférieure à 10%.',
                'action' => 'Optimiser les coûts opérationnels et diversifier les sources de revenus.'
            ];
        }

        return $recommendations;
    }

    /**
     * Génère un fichier Excel avancé avec graphiques
     */
    public function generateExcelReport()
    {
        // Note: PhpSpreadsheet doit être installé via composer
        // composer require phpoffice/phpspreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        // Feuille de résumé
        $this->createSummarySheet($spreadsheet);
        
        // Feuille d'analyse des revenus
        $this->createRevenueAnalysisSheet($spreadsheet);
        
        // Feuille d'analyse des dépenses
        $this->createExpenseAnalysisSheet($spreadsheet);
        
        // Feuille des KPIs
        $this->createKPIsSheet($spreadsheet);
        
        // Feuille des recommandations
        $this->createRecommendationsSheet($spreadsheet);

        return $spreadsheet;
    }

    /**
     * Crée la feuille de résumé avec graphiques
     */
    private function createSummarySheet($spreadsheet)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Résumé Financier');
        
        $summary = $this->getFinancialSummary();
        
        // En-tête
        $sheet->setCellValue('A1', 'RAPPORT FINANCIER COMPLET');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Période
        $sheet->setCellValue('A3', 'Période:');
        $sheet->setCellValue('B3', $this->from->format('d/m/Y') . ' - ' . $this->to->format('d/m/Y'));
        
        // Données financières
        $row = 5;
        $sheet->setCellValue("A{$row}", 'REVENUS');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Dîmes');
        $sheet->setCellValue("B{$row}", $summary['revenue_breakdown']['tithes']);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Offrandes');
        $sheet->setCellValue("B{$row}", $summary['revenue_breakdown']['offerings']);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Dons');
        $sheet->setCellValue("B{$row}", $summary['revenue_breakdown']['donations']);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'TOTAL REVENUS');
        $sheet->setCellValue("B{$row}", $summary['total_revenue']);
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $row += 2;
        
        $sheet->setCellValue("A{$row}", 'DÉPENSES');
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue("A{$row}", 'Total Dépenses');
        $sheet->setCellValue("B{$row}", $summary['total_expenses']);
        $row += 2;
        
        $sheet->setCellValue("A{$row}", 'RÉSULTAT NET');
        $sheet->setCellValue("B{$row}", $summary['net_income']);
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        
        // Formatage des montants
        $sheet->getStyle('B5:B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
        
        // Création du graphique
        $this->createRevenueChart($sheet, 'D5');
    }

    /**
     * Crée un graphique des revenus
     */
    private function createRevenueChart($sheet, $position)
    {
        $summary = $this->getFinancialSummary();
        
        // Données pour le graphique
        $dataLabels = ['Dîmes', 'Offrandes', 'Dons'];
        $dataValues = [
            $summary['revenue_breakdown']['tithes'],
            $summary['revenue_breakdown']['offerings'],
            $summary['revenue_breakdown']['donations']
        ];
        
        // Création du graphique en secteurs
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Résumé Financier!$B$6:$B$8', null, 3),
        ];
        
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Résumé Financier!$A$6:$A$8', null, 3),
        ];
        
        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($dataValues) - 1),
            $dataSeriesValues,
            $xAxisTickValues
        );
        
        $plotArea = new PlotArea(null, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Répartition des Revenus');
        
        $chart = new Chart('Revenus', $title, $legend, $plotArea);
        $chart->setTopLeftPosition($position);
        $chart->setBottomRightPosition('L20');
        
        $sheet->addChart($chart);
    }

    // Méthodes utilitaires pour les calculs avancés
    private function getAverageTransactionSize()
    {
        $totalAmount = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->sum('amount');
        
        $totalCount = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->count();
        
        return $totalCount > 0 ? $totalAmount / $totalCount : 0;
    }

    private function getMedianContribution()
    {
        $contributions = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->pluck('amount')
            ->sort()
            ->values();
        
        $count = $contributions->count();
        if ($count === 0) return 0;
        
        $middle = intval($count / 2);
        return $count % 2 === 0 
            ? ($contributions[$middle - 1] + $contributions[$middle]) / 2
            : $contributions[$middle];
    }

    private function calculateGrowthRate()
    {
        $currentPeriod = $this->getCurrentPeriodData();
        $previousPeriod = $this->getPreviousPeriodData();
        
        if ($previousPeriod['revenue'] == 0) return 0;
        
        return (($currentPeriod['revenue'] - $previousPeriod['revenue']) / $previousPeriod['revenue']) * 100;
    }

    private function calculateFinancialHealthScore()
    {
        $summary = $this->getFinancialSummary();
        $kpis = $this->getKPIs();
        
        // Score basé sur plusieurs facteurs
        $profitMarginScore = min(100, max(0, $summary['profit_margin'] * 2)); // 50% = 100 points
        $memberEngagementScore = min(100, $kpis['member_engagement_rate'] * 2); // 50% = 100 points
        $growthScore = min(100, max(0, $kpis['growth_momentum'] + 50)); // -50% à +50% = 0 à 100 points
        
        return ($profitMarginScore + $memberEngagementScore + $growthScore) / 3;
    }

    private function calculateMemberEngagementRate()
    {
        $totalMembers = Member::forChurch($this->churchId)->count();
        $contributingMembers = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->distinct('member_id')
            ->count('member_id');
        
        return $totalMembers > 0 ? ($contributingMembers / $totalMembers) * 100 : 0;
    }

    private function getPreviousPeriodData()
    {
        $periodLength = $this->from->diffInDays($this->to);
        $previousFrom = $this->from->copy()->subDays($periodLength + 1);
        $previousTo = $this->from->copy()->subDay();
        
        return [
            'revenue' => Tithe::forChurch($this->churchId)
                ->whereBetween('paid_at', [$previousFrom, $previousTo])
                ->sum('amount') +
                Offering::forChurch($this->churchId)
                ->whereBetween('received_at', [$previousFrom, $previousTo])
                ->sum('amount') +
                Donation::forChurch($this->churchId)
                ->whereBetween('received_at', [$previousFrom, $previousTo])
                ->sum('amount'),
            'expenses' => Expense::forChurch($this->churchId)
                ->whereBetween('paid_at', [$previousFrom, $previousTo])
                ->sum('amount'),
            'members' => Member::forChurch($this->churchId)->count()
        ];
    }

    private function getCurrentPeriodData()
    {
        return [
            'revenue' => Tithe::forChurch($this->churchId)
                ->whereBetween('paid_at', [$this->from, $this->to])
                ->sum('amount') +
                Offering::forChurch($this->churchId)
                ->whereBetween('received_at', [$this->from, $this->to])
                ->sum('amount') +
                Donation::forChurch($this->churchId)
                ->whereBetween('received_at', [$this->from, $this->to])
                ->sum('amount'),
            'expenses' => Expense::forChurch($this->churchId)
                ->whereBetween('paid_at', [$this->from, $this->to])
                ->sum('amount'),
            'members' => Member::forChurch($this->churchId)->count()
        ];
    }

    private function calculateTrend($previous, $current)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }

    private function analyzeSeasonality()
    {
        // Analyse de la saisonnalité sur les 12 derniers mois
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = $this->to->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'revenue' => Tithe::forChurch($this->churchId)
                    ->whereBetween('paid_at', [$monthStart, $monthEnd])
                    ->sum('amount')
            ];
        }
        
        return $monthlyData;
    }

    private function getConsistencyScore()
    {
        // Calcule la régularité des contributions
        $monthlyContributions = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total');
        
        if ($monthlyContributions->count() < 2) return 0;
        
        $mean = $monthlyContributions->avg();
        $variance = $monthlyContributions->map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        })->avg();
        
        $coefficientOfVariation = $mean > 0 ? sqrt($variance) / $mean : 0;
        
        // Score inversement proportionnel à la variation
        return max(0, 100 - ($coefficientOfVariation * 100));
    }

    private function getContributionDistribution()
    {
        $contributions = Tithe::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->pluck('amount');
        
        $ranges = [
            '0-1000' => 0,
            '1000-5000' => 0,
            '5000-10000' => 0,
            '10000-25000' => 0,
            '25000+' => 0
        ];
        
        foreach ($contributions as $amount) {
            if ($amount <= 1000) $ranges['0-1000']++;
            elseif ($amount <= 5000) $ranges['1000-5000']++;
            elseif ($amount <= 10000) $ranges['5000-10000']++;
            elseif ($amount <= 25000) $ranges['10000-25000']++;
            else $ranges['25000+']++;
        }
        
        return $ranges;
    }

    private function getAverageExpenseSize()
    {
        $totalAmount = Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->sum('amount');
        
        $totalCount = Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->count();
        
        return $totalCount > 0 ? $totalAmount / $totalCount : 0;
    }

    private function getExpenseCategories()
    {
        // Analyse des catégories de dépenses basée sur les projets
        return Expense::forChurch($this->churchId)
            ->whereBetween('paid_at', [$this->from, $this->to])
            ->with('project')
            ->get()
            ->groupBy(function ($expense) {
                return $expense->project ? $expense->project->name : 'Autres';
            })
            ->map(function ($group) {
                return $group->sum('amount');
            });
    }

    private function calculateExpenseEfficiency()
    {
        $summary = $this->getFinancialSummary();
        
        // Efficacité = revenus générés par franc dépensé
        return $summary['total_expenses'] > 0 
            ? $summary['total_revenue'] / $summary['total_expenses'] 
            : 0;
    }

    private function calculateGrowthMomentum()
    {
        $currentPeriod = $this->getCurrentPeriodData();
        $previousPeriod = $this->getPreviousPeriodData();
        
        return $this->calculateTrend($previousPeriod['revenue'], $currentPeriod['revenue']);
    }

    private function calculateSustainabilityIndex()
    {
        $summary = $this->getFinancialSummary();
        $kpis = $this->getKPIs();
        
        // Index de durabilité basé sur plusieurs facteurs
        $profitabilityScore = min(100, max(0, $summary['profit_margin'] * 2));
        $efficiencyScore = min(100, max(0, $kpis['expense_efficiency'] * 10));
        $engagementScore = $kpis['member_engagement_rate'];
        
        return ($profitabilityScore + $efficiencyScore + $engagementScore) / 3;
    }

    // Méthodes pour créer les autres feuilles Excel
    private function createRevenueAnalysisSheet($spreadsheet) { /* Implementation */ }
    private function createExpenseAnalysisSheet($spreadsheet) { /* Implementation */ }
    private function createKPIsSheet($spreadsheet) { /* Implementation */ }
    private function createRecommendationsSheet($spreadsheet) { /* Implementation */ }
}
