@extends('layouts.app')

@section('content')
<style>
/* Design minimaliste et élégant - Style MIT */
.dashboard-header {
    background: #ffffff;
    color: #1a1a1a;
    padding: 3rem 2rem;
    margin-bottom: 3rem;
    border-bottom: 1px solid #e5e5e5;
}

.dashboard-title {
    font-size: 2.25rem;
    font-weight: 300;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
    color: #1a1a1a;
}

.dashboard-subtitle {
    font-size: 1rem;
    color: #666;
    margin-bottom: 0;
    font-weight: 400;
    line-height: 1.5;
}

.kpi-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 2rem;
    transition: all 0.2s ease;
    height: 100%;
    position: relative;
}

.kpi-card:hover {
    border-color: #d0d0d0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.kpi-value {
    font-size: 2rem;
    font-weight: 300;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
    letter-spacing: -0.01em;
}

.kpi-label {
    color: #666;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: none;
    letter-spacing: 0;
    margin-bottom: 0.5rem;
}

.kpi-trend {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.trend-positive {
    color: #4a9eff;
}

.trend-negative {
    color: #ff6b6b;
}

.trend-neutral {
    color: #999;
}

.export-section {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 3rem 2rem;
    margin: 3rem 0;
}

.export-title {
    color: #1a1a1a;
    font-size: 1.5rem;
    font-weight: 300;
    margin-bottom: 1.5rem;
    letter-spacing: -0.01em;
}

.export-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
}

.export-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 2rem;
    text-align: left;
    transition: all 0.2s ease;
    position: relative;
}

.export-card:hover {
    border-color: #d0d0d0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.export-icon {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #666;
}

.export-card h4 {
    margin-bottom: 0.75rem;
    color: #1a1a1a;
    font-weight: 500;
    font-size: 1.125rem;
}

.export-card p {
    color: #666;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.analysis-section {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 3rem 2rem;
    margin: 3rem 0;
}

.section-title {
    color: #1a1a1a;
    font-size: 1.5rem;
    font-weight: 300;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e5e5;
    letter-spacing: -0.01em;
}

.chart-container {
    background: #fafafa;
    border: 1px solid #e5e5e5;
    padding: 3rem 2rem;
    margin: 2rem 0;
    text-align: center;
}

.chart-placeholder {
    color: #999;
    font-style: normal;
    font-size: 0.875rem;
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.recommendation-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 1.5rem;
    border-left: 3px solid #d0d0d0;
}

.recommendation-card.priority-high {
    border-left-color: #ff6b6b;
}

.recommendation-card.priority-medium {
    border-left-color: #ffa726;
}

.recommendation-card.priority-low {
    border-left-color: #4a9eff;
}

.recommendation-title {
    font-weight: 500;
    margin-bottom: 0.75rem;
    color: #1a1a1a;
    font-size: 1rem;
}

.recommendation-description {
    font-size: 0.875rem;
    color: #666;
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

.recommendation-action {
    font-size: 0.75rem;
    color: #999;
    font-style: normal;
    font-weight: 400;
}

.period-filter {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 2rem;
    margin-bottom: 3rem;
}

.filter-title {
    color: #1a1a1a;
    font-weight: 500;
    margin-bottom: 1.5rem;
    font-size: 1.125rem;
}

.quick-filters {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: 1.5rem;
}

.quick-filter-btn {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    color: #666;
    padding: 0.5rem 1rem;
    text-decoration: none;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    font-weight: 400;
}

.quick-filter-btn:hover {
    background: #f5f5f5;
    color: #1a1a1a;
    border-color: #d0d0d0;
    text-decoration: none;
}

.quick-filter-btn.active {
    background: #1a1a1a;
    color: #ffffff;
    border-color: #1a1a1a;
}

/* Boutons avec style minimaliste */
.btn {
    background: #1a1a1a;
    border-color: #1a1a1a;
    color: #ffffff;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn:hover {
    background: #333;
    border-color: #333;
    color: #ffffff;
}

.btn-outline-secondary {
    background: #ffffff;
    border-color: #e5e5e5;
    color: #666;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn-outline-secondary:hover {
    background: #f5f5f5;
    border-color: #d0d0d0;
    color: #1a1a1a;
}

.btn-success {
    background: #4a9eff;
    border-color: #4a9eff;
    color: #ffffff;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn-success:hover {
    background: #357abd;
    border-color: #357abd;
    color: #ffffff;
}

.btn-danger {
    background: #ff6b6b;
    border-color: #ff6b6b;
    color: #ffffff;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn-danger:hover {
    background: #e55a5a;
    border-color: #e55a5a;
    color: #ffffff;
}

.btn-secondary {
    background: #999;
    border-color: #999;
    color: #ffffff;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    background: #777;
    border-color: #777;
    color: #ffffff;
}

/* Formulaires */
.form-control {
    border: 1px solid #e5e5e5;
    border-radius: 0;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #1a1a1a;
    box-shadow: none;
}

.form-label {
    color: #666;
    font-weight: 500;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-title {
        font-size: 1.875rem;
    }
    
    .kpi-value {
        font-size: 1.75rem;
    }
    
    .export-grid {
        grid-template-columns: 1fr;
    }
    
    .recommendations-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header,
    .export-section,
    .analysis-section,
    .period-filter {
        padding: 2rem 1.5rem;
    }
}
</style>

<div class="container py-4">
    <!-- En-tête du tableau de bord -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            Rapports Financiers Avancés
        </h1>
        <p class="dashboard-subtitle">
            Analyse approfondie et recommandations stratégiques pour la gestion financière de l'église
        </p>
    </div>

    <!-- Filtre de période -->
    <div class="period-filter">
        <h5 class="filter-title">Période d'analyse</h5>
        
        <form method="GET" id="periodForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de début</label>
                    <input type="date" class="form-control" name="from" value="{{ $from }}" id="fromDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Date de fin</label>
                    <input type="date" class="form-control" name="to" value="{{ $to }}" id="toDate">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn w-100">
                        Appliquer
                    </button>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="resetPeriod()">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </form>

        <!-- Filtres rapides -->
        <div class="quick-filters">
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('year')">
                Année en cours
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('lastYear')">
                Année dernière
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('last6months')">
                6 derniers mois
            </a>
            <a href="#" class="quick-filter-btn" onclick="setQuickPeriod('last3months')">
                3 derniers mois
            </a>
        </div>
    </div>

    <!-- KPIs principaux -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-value">
                    {{ number_format($comprehensiveReport['kpis']['financial_health_score'], 1) }}%
                </div>
                <div class="kpi-label">Santé Financière</div>
                <div class="kpi-trend trend-{{ $comprehensiveReport['kpis']['financial_health_score'] >= 70 ? 'positive' : ($comprehensiveReport['kpis']['financial_health_score'] >= 50 ? 'neutral' : 'negative') }}">
                    {{ $comprehensiveReport['kpis']['financial_health_score'] >= 70 ? 'Excellent' : ($comprehensiveReport['kpis']['financial_health_score'] >= 50 ? 'Correct' : 'À améliorer') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-value">
                    {{ number_format($comprehensiveReport['kpis']['member_engagement_rate'], 1) }}%
                </div>
                <div class="kpi-label">Engagement Membres</div>
                <div class="kpi-trend trend-{{ $comprehensiveReport['kpis']['member_engagement_rate'] >= 50 ? 'positive' : ($comprehensiveReport['kpis']['member_engagement_rate'] >= 30 ? 'neutral' : 'negative') }}">
                    {{ $comprehensiveReport['kpis']['member_engagement_rate'] >= 50 ? 'Élevé' : ($comprehensiveReport['kpis']['member_engagement_rate'] >= 30 ? 'Moyen' : 'Faible') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-value">
                    {{ number_format($comprehensiveReport['kpis']['revenue_per_member'], 0, ',', ' ') }}
                </div>
                <div class="kpi-label">Revenus/Membre (FCFA)</div>
                <div class="kpi-trend trend-neutral">
                    Moyenne par contributeur
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="kpi-card">
                <div class="kpi-value">
                    {{ number_format($comprehensiveReport['kpis']['sustainability_index'], 1) }}%
                </div>
                <div class="kpi-label">Index Durabilité</div>
                <div class="kpi-trend trend-{{ $comprehensiveReport['kpis']['sustainability_index'] >= 70 ? 'positive' : ($comprehensiveReport['kpis']['sustainability_index'] >= 50 ? 'neutral' : 'negative') }}">
                    {{ $comprehensiveReport['kpis']['sustainability_index'] >= 70 ? 'Durable' : ($comprehensiveReport['kpis']['sustainability_index'] >= 50 ? 'Stable' : 'Risqué') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="analysis-section">
        <h3 class="section-title">Résumé Financier</h3>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <h4>{{ number_format($comprehensiveReport['financial_summary']['total_revenue'], 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted">Total Revenus</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4>{{ number_format($comprehensiveReport['financial_summary']['total_expenses'], 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted">Total Dépenses</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4>{{ number_format($comprehensiveReport['financial_summary']['net_income'], 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted">Résultat Net</p>
                </div>
            </div>
        </div>
        
        <!-- Cartes de navigation vers les détails -->
        <div class="row g-4 mt-4">
            <div class="col-md-4">
                <div class="export-card" onclick="window.location='{{ route('reports.tithes', ['from' => $from, 'to' => $to]) }}'" style="cursor: pointer;">
                    <div class="export-icon">💰</div>
                    <h4>Rapport Dîmes</h4>
                    <p>{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['tithes'], 0, ',', ' ') }} FCFA</p>
                    <div class="text-muted small">Cliquez pour voir les détails</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="export-card" onclick="window.location='{{ route('reports.offerings', ['from' => $from, 'to' => $to]) }}'" style="cursor: pointer;">
                    <div class="export-icon">❤️</div>
                    <h4>Rapport Offrandes</h4>
                    <p>{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['offerings'], 0, ',', ' ') }} FCFA</p>
                    <div class="text-muted small">Cliquez pour voir les détails</div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="export-card" onclick="window.location='{{ route('reports.donations', ['from' => $from, 'to' => $to]) }}'" style="cursor: pointer;">
                    <div class="export-icon">🎁</div>
                    <h4>Rapport Dons</h4>
                    <p>{{ number_format($comprehensiveReport['financial_summary']['revenue_breakdown']['donations'], 0, ',', ' ') }} FCFA</p>
                    <div class="text-muted small">Cliquez pour voir les détails</div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="export-card" onclick="window.location='{{ route('reports.expenses', ['from' => $from, 'to' => $to]) }}'" style="cursor: pointer;">
                    <div class="export-icon">📊</div>
                    <h4>Rapport Dépenses</h4>
                    <p>{{ number_format($comprehensiveReport['financial_summary']['total_expenses'], 0, ',', ' ') }} FCFA</p>
                    <div class="text-muted small">Cliquez pour voir les détails</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="export-card" onclick="window.location='{{ route('reports.advanced.comparison', ['from1' => $from, 'to1' => $to, 'from2' => \Carbon\Carbon::parse($from)->subYear()->format('Y-m-d'), 'to2' => \Carbon\Carbon::parse($to)->subYear()->format('Y-m-d')]) }}'" style="cursor: pointer;">
                    <div class="export-icon">📈</div>
                    <h4>Comparaison Annuelle</h4>
                    <p>Analyse comparative</p>
                    <div class="text-muted small">Cliquez pour comparer avec l'année précédente</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section d'export -->
    <div class="export-section">
        <h3 class="export-title">Exports Avancés</h3>
        <p class="text-muted mb-4">Téléchargez vos données dans différents formats pour une analyse approfondie</p>
        
        <div class="export-grid">
            <div class="export-card">
                <div class="export-icon">📊</div>
                <h4>Excel Avancé</h4>
                <p>Rapport complet avec graphiques, formules et analyses détaillées</p>
                <a href="{{ route('reports.advanced.export.excel', ['from' => $from, 'to' => $to]) }}" class="btn btn-success">
                    Télécharger
                </a>
            </div>
            
            <div class="export-card">
                <div class="export-icon">📄</div>
                <h4>PDF Professionnel</h4>
                <p>Rapport formaté pour présentation et archivage</p>
                <a href="{{ route('reports.advanced.export.pdf', ['from' => $from, 'to' => $to]) }}" class="btn btn-danger">
                    Télécharger
                </a>
            </div>
            
            <div class="export-card">
                <div class="export-icon">🔗</div>
                <h4>JSON Structuré</h4>
                <p>Données brutes pour intégration avec d'autres systèmes</p>
                <a href="{{ route('reports.advanced.export.json', ['from' => $from, 'to' => $to]) }}" class="btn btn">
                    Télécharger
                </a>
            </div>
            
            <div class="export-card">
                <div class="export-icon">📋</div>
                <h4>CSV Optimisé</h4>
                <p>Données tabulaires pour analyse dans Excel ou autres outils</p>
                <a href="{{ route('reports.advanced.export.csv', ['from' => $from, 'to' => $to, 'type' => 'all']) }}" class="btn btn-secondary">
                    Télécharger
                </a>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    @if(count($comprehensiveReport['recommendations']) > 0)
    <div class="analysis-section">
        <h3 class="section-title">Recommandations Stratégiques</h3>
        
        <div class="recommendations-grid">
            @foreach($comprehensiveReport['recommendations'] as $recommendation)
            <div class="recommendation-card priority-{{ $recommendation['priority'] }}">
                <div class="recommendation-title">{{ $recommendation['title'] }}</div>
                <div class="recommendation-description">{{ $recommendation['description'] }}</div>
                <div class="recommendation-action">{{ $recommendation['action'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Analyse des tendances -->
    <div class="analysis-section">
        <h3 class="section-title">Analyse des Tendances</h3>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="{{ $comprehensiveReport['trends']['revenue_trend'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($comprehensiveReport['trends']['revenue_trend'], 1) }}%
                    </h4>
                    <p class="text-muted">Tendance Revenus</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="{{ $comprehensiveReport['trends']['expense_trend'] <= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($comprehensiveReport['trends']['expense_trend'], 1) }}%
                    </h4>
                    <p class="text-muted">Tendance Dépenses</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center">
                    <h4 class="{{ $comprehensiveReport['trends']['member_growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($comprehensiveReport['trends']['member_growth'], 1) }}%
                    </h4>
                    <p class="text-muted">Croissance Membres</p>
                </div>
            </div>
        </div>
        
        <div class="chart-container">
            <div class="chart-placeholder">
                <p>Graphique des tendances temporelles</p>
                <p>Analyse sur {{ count($comprehensiveReport['trends']['seasonality']) }} mois</p>
            </div>
        </div>
    </div>
</div>

<script>
function setQuickPeriod(period) {
    const today = new Date();
    let from, to;
    
    switch(period) {
        case 'year':
            from = new Date(today.getFullYear(), 0, 1);
            to = new Date(today.getFullYear(), 11, 31);
            break;
        case 'lastYear':
            from = new Date(today.getFullYear() - 1, 0, 1);
            to = new Date(today.getFullYear() - 1, 11, 31);
            break;
        case 'last6months':
            from = new Date(today.getFullYear(), today.getMonth() - 6, 1);
            to = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        case 'last3months':
            from = new Date(today.getFullYear(), today.getMonth() - 3, 1);
            to = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
    }
    
    document.getElementById('fromDate').value = from.toISOString().split('T')[0];
    document.getElementById('toDate').value = to.toISOString().split('T')[0];
    
    // Soumettre le formulaire
    document.getElementById('periodForm').submit();
}

function resetPeriod() {
    const currentYear = new Date().getFullYear();
    document.getElementById('fromDate').value = currentYear + '-01-01';
    document.getElementById('toDate').value = currentYear + '-12-31';
    document.getElementById('periodForm').submit();
}

// Mettre à jour les liens d'export quand les dates changent
document.getElementById('fromDate').addEventListener('change', updateExportLinks);
document.getElementById('toDate').addEventListener('change', updateExportLinks);

function updateExportLinks() {
    const from = document.getElementById('fromDate').value;
    const to = document.getElementById('toDate').value;
    
    // Mettre à jour tous les liens d'export
    document.querySelectorAll('a[href*="reports.advanced.export"]').forEach(link => {
        const url = new URL(link.href);
        url.searchParams.set('from', from);
        url.searchParams.set('to', to);
        link.href = url.toString();
    });
}
</script>
@endsection
