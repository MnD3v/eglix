@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Tableau de bord</h1>
        <p class="page-subtitle">Vue d'ensemble des activités et finances de l'église</p>
        <div class="d-flex align-items-center gap-2 mt-2">
            <i class="bi bi-calendar3 text-muted"></i>
            <span class="text-muted">{{ optional($from ?? null)->format('d/m/Y') }} — {{ optional($to ?? null)->format('d/m/Y') }}</span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">Tous</button>
        <button class="filter-tab" data-filter="financial">Financier</button>
    </div>

    <!-- KPIs Section -->
    <div class="kpis-section mb-4" data-section="financial">
        <div class="row g-3">
            <!-- Membres actifs -->
            <div class="col-6 col-lg-3">
                <a href="{{ route('members.index') }}" class="text-decoration-none">
                    <div class="kpi-card">
                        <div class="kpi-header">
                            <div class="kpi-icon members">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="kpi-info">
                                <h3 class="kpi-title">Membres actifs</h3>
                                <p class="kpi-description">Nombre de membres actifs</p>
                            </div>
                        </div>
                        <div class="kpi-meta">
                            <div class="kpi-value">{{ $stats['active_members'] ?? '—' }}</div>
                        </div>
                        <div class="kpi-actions">
                            <a href="{{ route('members.index') }}" class="action-btn" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('members.create') }}" class="action-btn" title="Ajouter">
                                <i class="bi bi-plus"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            
            @foreach(($kpis ?? []) as $idx => $k)
            <div class="col-6 col-lg-3">
                @php
                    $href = '#';
                    $icon = 'bi-cash-coin';
                    $color = 'primary';
                    if (($k['label'] ?? '') === 'Dîmes') { 
                        $href = route('tithes.index'); 
                        $icon = 'bi-cash-coin';
                        $color = 'tithes';
                    }
                    elseif (($k['label'] ?? '') === 'Offrandes') { 
                        $href = route('offerings.index'); 
                        $icon = 'bi-gift-fill';
                        $color = 'offerings';
                    }
                    elseif (($k['label'] ?? '') === 'Dons') { 
                        $href = route('donations.index'); 
                        $icon = 'bi-heart-fill';
                        $color = 'donations';
                    }
                    elseif (($k['label'] ?? '') === 'Dépenses') { 
                        $href = route('expenses.index'); 
                        $icon = 'bi-credit-card-fill';
                        $color = 'expenses';
                    }
                @endphp
                <a href="{{ $href }}" class="text-decoration-none">
                    <div class="kpi-card">
                        <div class="kpi-header">
                            <div class="kpi-icon {{ $color }}">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            <div class="kpi-info">
                                <h3 class="kpi-title">{{ $k['label'] }}</h3>
                                <p class="kpi-description">Montant {{ strtolower($k['label']) }}</p>
                            </div>
                        </div>
                        <div class="kpi-meta">
                            <div class="kpi-value">{{ number_format(round($k['label']==='Dépenses' ? ($k['current'] ?? 0) : $k['value']), 0, ',', ' ') }} FCFA</div>
                            @if(!is_null($k['delta']))
                                @php $up = ($k['is_expense'] ?? false) ? ($k['delta'] < 0) : ($k['delta'] >= 0); @endphp
                                <div class="kpi-trend {{ $up ? 'trend-up' : 'trend-down' }}">
                                    <i class="bi {{ $up ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                    {{ abs($k['delta']) }}%
                                </div>
                            @endif
                        </div>
                        <div class="kpi-actions">
                            <a href="{{ $href }}" class="action-btn" title="Voir">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ $href }}/create" class="action-btn" title="Ajouter">
                                <i class="bi bi-plus"></i>
                            </a>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Graphiques -->
    <div class="charts-section" data-section="financial">
        <div class="row g-3">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="chart-info">
                            <h3 class="chart-title">Évolution mensuelle</h3>
                            <p class="chart-description">Toute l'année</p>
                        </div>
                    </div>
                    <div class="chart-content">
                        <div class="chart-container">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<style>
/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #FFFFFF;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #FFFFFF;
    font-size: 1rem;
    margin-bottom: 0;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    padding: 0.25rem;
    background: #F9FAFB;
    border-radius: 8px;
    width: fit-content;
}

.filter-tab {
    padding: 0.5rem 1rem;
    border: none;
    background: transparent;
    color: #6B7280;
    font-weight: 500;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.filter-tab.active {
    background: #FF2600;
    color: white;
}

.filter-tab:hover:not(.active) {
    background: #E5E7EB;
    color: #374151;
}

/* KPI Cards */
.kpi-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    position: relative;
}

.kpi-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.kpi-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.kpi-icon.members { background: #6B7280; }
.kpi-icon.tithes { background: #FF2600; }
.kpi-icon.offerings { background: #22C55E; }
.kpi-icon.donations { background: #8B5CF6; }
.kpi-icon.expenses { background: #F59E0B; }

.kpi-info {
    flex: 1;
    min-width: 0;
}

.kpi-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.kpi-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.4;
}

.kpi-meta {
    margin-bottom: 1rem;
}

.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 0.5rem;
}

.kpi-trend {
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.trend-up { color: #22C55E; }
.trend-down { color: #EF4444; }

.kpi-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #E5E7EB;
    background: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.action-btn:hover {
    border-color: #D1D5DB;
    background: #F9FAFB;
    color: #374151;
    text-decoration: none;
}

/* Transaction Card */
.transaction-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.transaction-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.transaction-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #8B5CF6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.transaction-info {
    flex: 1;
    min-width: 0;
}

.transaction-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.transaction-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.4;
}

.transaction-meta {
    margin-bottom: 1rem;
}

.transaction-tabs {
    display: flex;
    gap: 0.5rem;
    padding: 0.25rem;
    background: #F9FAFB;
    border-radius: 6px;
    width: fit-content;
}

.transaction-tab {
    padding: 0.25rem 0.75rem;
    border: none;
    background: transparent;
    color: #6B7280;
    font-weight: 500;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.75rem;
}

.transaction-tab.active {
    background: #8B5CF6;
    color: white;
}

.transaction-tab:hover:not(.active) {
    background: #E5E7EB;
    color: #374151;
}

.transaction-content {
    margin-top: 1rem;
}

.transaction-pane {
    display: none;
}

.transaction-pane.active {
    display: block;
}

.transaction-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.transaction-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: #F9FAFB;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.transaction-item:hover {
    background: #F3F4F6;
}

.transaction-date {
    background: #8B5CF6;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 40px;
    text-align: center;
    flex-shrink: 0;
}

.transaction-detail {
    flex: 1;
    min-width: 0;
}

.transaction-name {
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.transaction-type {
    font-size: 0.75rem;
    color: #6B7280;
}

.transaction-amount {
    font-weight: 700;
    color: #1F2937;
    flex-shrink: 0;
}

/* Chart Cards */
.chart-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.chart-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.chart-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.chart-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    flex-shrink: 0;
}

.chart-info {
    flex: 1;
    min-width: 0;
}

.chart-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 0.25rem;
    line-height: 1.4;
}

.chart-description {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.4;
}

.chart-content {
    margin-top: 1rem;
}

.chart-container {
    height: 300px;
    position: relative;
}

.image-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    overflow: hidden;
}

.image-card:hover {
    border-color: #D1D5DB;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: #6B7280;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .filter-tabs {
        width: 100%;
        justify-content: center;
    }
    
    .kpi-header {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .kpi-actions {
        justify-content: flex-start;
    }
    
    .transaction-header {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des onglets de filtrage
    const filterTabs = document.querySelectorAll('.filter-tab');
    const sections = document.querySelectorAll('[data-section]');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');            
            // Show/hide sections
            sections.forEach(section => {
                if (filter === 'all') {
                    section.style.display = 'block';
                } else {
                    section.style.display = section.getAttribute('data-section') === filter ? 'block' : 'none';
                }
            });
        });
    });    
    // Gestion des onglets de transactions
    const transactionTabs = document.querySelectorAll('.transaction-tab');
    const transactionPanes = document.querySelectorAll('.transaction-pane');    
    transactionTabs.forEach(button => {
        button.addEventListener('click', function() {

            const targetTab = this.getAttribute('data-tab');

            // Retirer la classe active de tous les boutons et panneaux
            transactionTabs.forEach(btn => btn.classList.remove('active'));
            transactionPanes.forEach(pane => pane.classList.remove('active'));

            // Ajouter la classe active au bouton cliqué et au panneau correspondant
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');

            
        });
    });
    
    // Configuration des graphiques
    const labels = @json($chart['labels'] ?? []);
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    const h = 300;
    
    const gradientTithes = ctxLine.createLinearGradient(0, 0, 0, h);
    gradientTithes.addColorStop(0, 'rgba(255,38,0,0.3)');
    gradientTithes.addColorStop(1, 'rgba(255,38,0,0.05)');
    
    const gradientOfferings = ctxLine.createLinearGradient(0, 0, 0, h);
    gradientOfferings.addColorStop(0, 'rgba(34,197,94,0.3)');
    gradientOfferings.addColorStop(1, 'rgba(34,197,94,0.05)');
    
    const gradientDonations = ctxLine.createLinearGradient(0, 0, 0, h);
    gradientDonations.addColorStop(0, 'rgba(139,92,246,0.3)');
    gradientDonations.addColorStop(1, 'rgba(139,92,246,0.05)');
    
    const gradientExpenses = ctxLine.createLinearGradient(0, 0, 0, h);
    gradientExpenses.addColorStop(0, 'rgba(239,68,68,0.3)');
    gradientExpenses.addColorStop(1, 'rgba(239,68,68,0.05)');

    const lineConfig = {
        type: 'line',
        data: {
            labels,
            datasets: [
                { 
                    label: 'Dîmes', 
                    data: @json($chart['tithes'] ?? []), 
                    borderColor: '#FF2600', 
                    backgroundColor: gradientTithes, 
                    fill: true, 
                    tension: 0.4,
                    pointRadius: 4, 
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#FF2600',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                { 
                    label: 'Offrandes', 
                    data: @json($chart['offerings'] ?? []), 
                    borderColor: '#22C55E', 
                    backgroundColor: gradientOfferings, 
                    fill: true, 
                    tension: 0.4,
                    pointRadius: 4, 
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#22C55E',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                { 
                    label: 'Dons', 
                    data: @json($chart['donations'] ?? []), 
                    borderColor: '#8B5CF6', 
                    backgroundColor: gradientDonations, 
                    fill: true, 
                    tension: 0.4,
                    pointRadius: 4, 
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                { 
                    label: 'Dépenses', 
                    data: @json($chart['expenses'] ?? []), 
                    borderColor: '#EF4444', 
                    backgroundColor: gradientExpenses, 
                    fill: true, 
                    tension: 0.4,
                    pointRadius: 4, 
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#EF4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: { 
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#FF2600',
                    borderWidth: 1,
                    callbacks: { 
                        label: (ctx) => `${ctx.dataset.label}: ${Math.round(Number(ctx.parsed.y)).toLocaleString('fr-FR')} FCFA` 
                    } 
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { 
                        color: 'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                },
                x: { 
                    grid: { 
                        display: false 
                    },
                    ticks: {
                        color: '#6B7280'
                    }
                }
            },
            animation: { 
                duration: 800,
                easing: 'easeInOutQuart'
            }
        }
    };
    
    const lineChart = new Chart(ctxLine, lineConfig);
});
</script>
@endsection



