<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Financier Complet</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #FF2600;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #FF2600;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }
        
        .period-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #FF2600;
        }
        
        .period-info h3 {
            margin: 0 0 10px 0;
            color: #FF2600;
            font-size: 14px;
        }
        
        .period-info p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #FF2600;
            color: white;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .financial-summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .financial-summary .row {
            display: table-row;
        }
        
        .financial-summary .cell {
            display: table-cell;
            padding: 8px 12px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        
        .financial-summary .cell.label {
            font-weight: bold;
            background: #f8f9fa;
            width: 40%;
        }
        
        .financial-summary .cell.value {
            text-align: right;
            font-family: 'Courier New', monospace;
            width: 30%;
        }
        
        .financial-summary .cell.percentage {
            text-align: right;
            color: #666;
            width: 30%;
        }
        
        .kpi-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .kpi-item {
            display: table-cell;
            width: 33.33%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .kpi-item:not(:last-child) {
            border-right: none;
        }
        
        .kpi-value {
            font-size: 18px;
            font-weight: bold;
            color: #FF2600;
            margin-bottom: 5px;
        }
        
        .kpi-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        
        .chart-placeholder {
            background: #f8f9fa;
            border: 2px dashed #ddd;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .chart-placeholder p {
            margin: 0;
            color: #666;
            font-style: italic;
        }
        
        .recommendations {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .recommendations h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 13px;
        }
        
        .recommendation-item {
            margin-bottom: 10px;
            padding-left: 15px;
            position: relative;
        }
        
        .recommendation-item:before {
            content: "•";
            color: #FF2600;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .recommendation-item:last-child {
            margin-bottom: 0;
        }
        
        .priority-high {
            border-left: 4px solid #dc3545;
        }
        
        .priority-medium {
            border-left: 4px solid #ffc107;
        }
        
        .priority-low {
            border-left: 4px solid #28a745;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>RAPPORT FINANCIER COMPLET</h1>
        <h2>Analyse Financière Avancée</h2>
    </div>
    
    <!-- Informations de période -->
    <div class="period-info">
        <h3>Période d'Analyse</h3>
        <p><strong>Du :</strong> {{ $comprehensiveReport['period']['from'] }}</p>
        <p><strong>Au :</strong> {{ $comprehensiveReport['period']['to'] }}</p>
        <p><strong>Durée :</strong> {{ $comprehensiveReport['period']['days'] }} jours</p>
        <p><strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
    </div>
    
    <!-- Résumé financier -->
    <div class="section">
        <div class="section-title">RÉSUMÉ FINANCIER</div>
        
        <div class="financial-summary">
            <div class="row">
                <div class="cell label">REVENUS</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['total_revenue'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">100%</div>
            </div>
            <div class="row">
                <div class="cell label">• Dîmes</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['tithes'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ $comprehensiveReport['financial_summary']['total_revenue'] > 0 ? number_format(($comprehensiveReport['financial_summary']['revenue_breakdown']['tithes'] / $comprehensiveReport['financial_summary']['total_revenue']) * 100, 1) : 0 }}%</div>
            </div>
            <div class="row">
                <div class="cell label">• Offrandes</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['offerings'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ $comprehensiveReport['financial_summary']['total_revenue'] > 0 ? number_format(($comprehensiveReport['financial_summary']['revenue_breakdown']['offerings'] / $comprehensiveReport['financial_summary']['total_revenue']) * 100, 1) : 0 }}%</div>
            </div>
            <div class="row">
                <div class="cell label">• Dons</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['donations'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ $comprehensiveReport['financial_summary']['total_revenue'] > 0 ? number_format(($comprehensiveReport['financial_summary']['revenue_breakdown']['donations'] / $comprehensiveReport['financial_summary']['total_revenue']) * 100, 1) : 0 }}%</div>
            </div>
            <div class="row">
                <div class="cell label">DÉPENSES</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['total_expenses'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ $comprehensiveReport['financial_summary']['total_revenue'] > 0 ? number_format(($comprehensiveReport['financial_summary']['total_expenses'] / $comprehensiveReport['financial_summary']['total_revenue']) * 100, 1) : 0 }}%</div>
            </div>
            <div class="row">
                <div class="cell label">RÉSULTAT NET</div>
                <div class="cell value">{{ number_format($comprehensiveReport['financial_summary']['net_income'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ number_format($comprehensiveReport['financial_summary']['profit_margin'], 1) }}%</div>
            </div>
        </div>
    </div>
    
    <!-- Indicateurs de performance -->
    <div class="section">
        <div class="section-title">INDICATEURS DE PERFORMANCE CLÉS (KPIs)</div>
        
        <div class="kpi-grid">
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['financial_health_score'], 1) }}%</div>
                <div class="kpi-label">Santé Financière</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['member_engagement_rate'], 1) }}%</div>
                <div class="kpi-label">Engagement Membres</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['revenue_per_member'], 0, ',', ' ') }}</div>
                <div class="kpi-label">Revenus/Membre (FCFA)</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['expense_efficiency'], 2) }}</div>
                <div class="kpi-label">Efficacité Dépenses</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['growth_momentum'], 1) }}%</div>
                <div class="kpi-label">Momentum Croissance</div>
            </div>
            <div class="kpi-item">
                <div class="kpi-value">{{ number_format($comprehensiveReport['kpis']['sustainability_index'], 1) }}%</div>
                <div class="kpi-label">Index Durabilité</div>
            </div>
        </div>
    </div>
    
    <!-- Analyse des revenus -->
    <div class="section">
        <div class="section-title">ANALYSE DES REVENUS</div>
        
        <div class="chart-placeholder">
            <p>📊 Graphique des revenus mensuels</p>
            <p><em>Données disponibles : {{ count($comprehensiveReport['revenue_analysis']['monthly_tithes']) }} mois</em></p>
        </div>
        
        <div class="financial-summary">
            <div class="row">
                <div class="cell label">Transaction moyenne</div>
                <div class="cell value">{{ number_format($comprehensiveReport['revenue_analysis']['average_transaction'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">-</div>
            </div>
            <div class="row">
                <div class="cell label">Taux de croissance</div>
                <div class="cell value">{{ number_format($comprehensiveReport['revenue_analysis']['growth_rate'], 1) }}%</div>
                <div class="cell percentage">vs période précédente</div>
            </div>
        </div>
    </div>
    
    <!-- Analyse des contributions des membres -->
    <div class="section">
        <div class="section-title">ANALYSE DES CONTRIBUTIONS DES MEMBRES</div>
        
        <div class="financial-summary">
            <div class="row">
                <div class="cell label">Total contributeurs</div>
                <div class="cell value">{{ $comprehensiveReport['member_contribution']['statistics']['total_contributors'] }}</div>
                <div class="cell percentage">membres actifs</div>
            </div>
            <div class="row">
                <div class="cell label">Contribution moyenne</div>
                <div class="cell value">{{ number_format($comprehensiveReport['member_contribution']['statistics']['average_contribution'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">par membre</div>
            </div>
            <div class="row">
                <div class="cell label">Contribution médiane</div>
                <div class="cell value">{{ number_format($comprehensiveReport['member_contribution']['statistics']['median_contribution'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">valeur médiane</div>
            </div>
            <div class="row">
                <div class="cell label">Score de régularité</div>
                <div class="cell value">{{ number_format($comprehensiveReport['member_contribution']['statistics']['consistency_score'], 1) }}%</div>
                <div class="cell percentage">régularité</div>
            </div>
        </div>
        
        @if(count($comprehensiveReport['member_contribution']['top_contributors']) > 0)
        <h4 style="margin: 20px 0 10px 0; color: #FF2600;">Top 5 Contributeurs</h4>
        <div class="financial-summary">
            @foreach($comprehensiveReport['member_contribution']['top_contributors']->take(5) as $contributor)
            <div class="row">
                <div class="cell label">{{ $contributor->member ? $contributor->member->last_name . ' ' . $contributor->member->first_name : 'Membre inconnu' }}</div>
                <div class="cell value">{{ number_format($contributor->total, 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">{{ $contributor->count }} contributions</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- Analyse des projets -->
    <div class="section">
        <div class="section-title">ANALYSE DES PROJETS</div>
        
        @if(count($comprehensiveReport['project_analysis']) > 0)
        <div class="financial-summary">
            @foreach($comprehensiveReport['project_analysis']->take(5) as $project)
            <div class="row">
                <div class="cell label">{{ $project['project']->name }}</div>
                <div class="cell value">{{ number_format($project['net_income'], 0, ',', ' ') }} FCFA</div>
                <div class="cell percentage">ROI: {{ number_format($project['roi'], 1) }}%</div>
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align: center; color: #666; font-style: italic;">Aucun projet analysé pour cette période</p>
        @endif
    </div>
    
    <!-- Recommandations -->
    @if(count($comprehensiveReport['recommendations']) > 0)
    <div class="section">
        <div class="section-title">RECOMMANDATIONS STRATÉGIQUES</div>
        
        <div class="recommendations">
            <h4>Recommandations basées sur l'analyse des données</h4>
            
            @foreach($comprehensiveReport['recommendations'] as $recommendation)
            <div class="recommendation-item priority-{{ $recommendation['priority'] }}">
                <strong>{{ $recommendation['title'] }}</strong><br>
                {{ $recommendation['description'] }}<br>
                <em>Action suggérée : {{ $recommendation['action'] }}</em>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Analyse des tendances -->
    <div class="section page-break">
        <div class="section-title">ANALYSE DES TENDANCES</div>
        
        <div class="financial-summary">
            <div class="row">
                <div class="cell label">Tendance revenus</div>
                <div class="cell value">{{ number_format($comprehensiveReport['trends']['revenue_trend'], 1) }}%</div>
                <div class="cell percentage">vs période précédente</div>
            </div>
            <div class="row">
                <div class="cell label">Tendance dépenses</div>
                <div class="cell value">{{ number_format($comprehensiveReport['trends']['expense_trend'], 1) }}%</div>
                <div class="cell percentage">vs période précédente</div>
            </div>
            <div class="row">
                <div class="cell label">Croissance membres</div>
                <div class="cell value">{{ number_format($comprehensiveReport['trends']['member_growth'], 1) }}%</div>
                <div class="cell percentage">vs période précédente</div>
            </div>
        </div>
        
        <div class="chart-placeholder">
            <p>📈 Graphique des tendances saisonnières</p>
            <p><em>Analyse sur {{ count($comprehensiveReport['trends']['seasonality']) }} mois</em></p>
        </div>
    </div>
    
    <!-- Pied de page -->
    <div class="footer">
        <p>Rapport généré automatiquement par Eglix - Système de Gestion d'Église</p>
        <p>Confidentialité : Ce rapport contient des informations financières sensibles</p>
        <p>© {{ date('Y') }} Eglix. Tous droits réservés.</p>
    </div>
</body>
</html>

