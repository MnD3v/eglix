@extends('layouts.app')

@section('content')
<style>
/* Design minimaliste et élégant - Style MIT */
.comparison-header {
    background: #ffffff;
    color: #1a1a1a;
    padding: 3rem 2rem;
    margin-bottom: 3rem;
    border-bottom: 1px solid #e5e5e5;
}

.comparison-title {
    font-size: 2.25rem;
    font-weight: 300;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
    color: #1a1a1a;
}

.comparison-subtitle {
    font-size: 1rem;
    color: #666;
    margin-bottom: 0;
    font-weight: 400;
    line-height: 1.5;
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

.comparison-section {
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

.comparison-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.period-card {
    background: #fafafa;
    border: 1px solid #e5e5e5;
    padding: 2rem;
}

.period-label {
    font-size: 1.125rem;
    font-weight: 500;
    margin-bottom: 1rem;
    color: #1a1a1a;
}

.metric-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e5e5;
}

.metric-row:last-child {
    border-bottom: none;
}

.metric-label {
    color: #666;
    font-size: 0.875rem;
    font-weight: 500;
}

.metric-value {
    color: #1a1a1a;
    font-size: 1rem;
    font-weight: 500;
}

.change-indicator {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 0;
}

.change-positive {
    color: #4a9eff;
    background: #f0f7ff;
}

.change-negative {
    color: #ff6b6b;
    background: #fff0f0;
}

.change-neutral {
    color: #999;
    background: #f5f5f5;
}

.summary-section {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    padding: 2rem;
    margin-top: 2rem;
}

.summary-title {
    color: #1a1a1a;
    font-size: 1.25rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.summary-item {
    text-align: center;
    padding: 1rem;
    background: #fafafa;
    border: 1px solid #e5e5e5;
}

.summary-value {
    font-size: 1.5rem;
    font-weight: 300;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
}

.summary-label {
    font-size: 0.875rem;
    color: #666;
    font-weight: 500;
}

.btn-primary {
    background: #1a1a1a;
    border-color: #1a1a1a;
    color: #ffffff;
    font-weight: 500;
    padding: 0.75rem 1.5rem;
    border-radius: 0;
    transition: all 0.2s ease;
}

.btn-primary:hover {
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

@media (max-width: 768px) {
    .comparison-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .comparison-header,
    .comparison-section,
    .period-filter {
        padding: 2rem 1.5rem;
    }
}
</style>

<div class="container py-4">
    <!-- En-tête -->
    <div class="comparison-header">
        <h1 class="comparison-title">
            Comparaison des Rapports Financiers
        </h1>
        <p class="comparison-subtitle">
            Analyse comparative entre deux périodes pour identifier les tendances et évolutions
        </p>
    </div>

    <!-- Filtres de période -->
    <div class="period-filter">
        <h5 class="filter-title">Périodes de comparaison</h5>
        
        <form method="GET" id="comparisonForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Période 1 - Début</label>
                    <input type="date" class="form-control" name="from1" value="{{ $from1 }}" id="from1Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Période 1 - Fin</label>
                    <input type="date" class="form-control" name="to1" value="{{ $to1 }}" id="to1Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Période 2 - Début</label>
                    <input type="date" class="form-control" name="from2" value="{{ $from2 }}" id="from2Date">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Période 2 - Fin</label>
                    <input type="date" class="form-control" name="to2" value="{{ $to2 }}" id="to2Date">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary w-100">
                        Comparer les périodes
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-secondary w-100" onclick="resetComparison()">
                        Réinitialiser
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Comparaison des revenus -->
    <div class="comparison-section">
        <h3 class="section-title">Comparaison des Revenus</h3>
        
        <div class="comparison-grid">
            <div class="period-card">
                <div class="period-label">Période 1</div>
                <div class="metric-row">
                    <span class="metric-label">Total Revenus</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['total_revenue'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Dîmes</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['revenue_breakdown']['tithes'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Offrandes</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['revenue_breakdown']['offerings'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Dons</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['revenue_breakdown']['donations'], 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
            
            <div class="period-card">
                <div class="period-label">Période 2</div>
                <div class="metric-row">
                    <span class="metric-label">Total Revenus</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['total_revenue'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Dîmes</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['revenue_breakdown']['tithes'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Offrandes</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['revenue_breakdown']['offerings'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Dons</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['revenue_breakdown']['donations'], 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparaison des dépenses -->
    <div class="comparison-section">
        <h3 class="section-title">Comparaison des Dépenses</h3>
        
        <div class="comparison-grid">
            <div class="period-card">
                <div class="period-label">Période 1</div>
                <div class="metric-row">
                    <span class="metric-label">Total Dépenses</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['total_expenses'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Résultat Net</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['net_income'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Marge Bénéficiaire</span>
                    <span class="metric-value">{{ number_format($comparison['period1']['data']['financial_summary']['profit_margin'], 1) }}%</span>
                </div>
            </div>
            
            <div class="period-card">
                <div class="period-label">Période 2</div>
                <div class="metric-row">
                    <span class="metric-label">Total Dépenses</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['total_expenses'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Résultat Net</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['net_income'], 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="metric-row">
                    <span class="metric-label">Marge Bénéficiaire</span>
                    <span class="metric-value">{{ number_format($comparison['period2']['data']['financial_summary']['profit_margin'], 1) }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyse des changements -->
    <div class="summary-section">
        <h3 class="summary-title">Analyse des Changements</h3>
        
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-value {{ $comparison['comparison']['revenue_change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                    {{ number_format($comparison['comparison']['revenue_change'], 1) }}%
                </div>
                <div class="summary-label">Évolution Revenus</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value {{ $comparison['comparison']['expense_change'] <= 0 ? 'change-positive' : 'change-negative' }}">
                    {{ number_format($comparison['comparison']['expense_change'], 1) }}%
                </div>
                <div class="summary-label">Évolution Dépenses</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value {{ $comparison['comparison']['profit_margin_change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                    {{ number_format($comparison['comparison']['profit_margin_change'], 1) }}%
                </div>
                <div class="summary-label">Évolution Marge</div>
            </div>
            
            <div class="summary-item">
                <div class="summary-value {{ $comparison['comparison']['member_engagement_change'] >= 0 ? 'change-positive' : 'change-negative' }}">
                    {{ number_format($comparison['comparison']['member_engagement_change'], 1) }}%
                </div>
                <div class="summary-label">Évolution Engagement</div>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    @if(count($comparison['period2']['data']['recommendations']) > 0)
    <div class="comparison-section">
        <h3 class="section-title">Recommandations Basées sur la Comparaison</h3>
        
        <div class="row g-4">
            @foreach($comparison['period2']['data']['recommendations'] as $recommendation)
            <div class="col-md-6">
                <div class="period-card">
                    <div class="period-label">{{ $recommendation['title'] }}</div>
                    <p class="metric-label">{{ $recommendation['description'] }}</p>
                    <p class="metric-value small">{{ $recommendation['action'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function resetComparison() {
    const currentYear = new Date().getFullYear();
    const lastYear = currentYear - 1;
    
    document.getElementById('from1Date').value = currentYear + '-01-01';
    document.getElementById('to1Date').value = currentYear + '-12-31';
    document.getElementById('from2Date').value = lastYear + '-01-01';
    document.getElementById('to2Date').value = lastYear + '-12-31';
    
    document.getElementById('comparisonForm').submit();
}
</script>
@endsection

