@extends('layouts.app')

@section('content')
<!-- Import de la police EB Garamond -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<div class="container py-4">
    <!-- Section de salutation élégante -->
    <div class="greeting-section">
        <div class="greeting-content">
            <h1 class="greeting-title">
                @php
                    $hour = now()->hour;
                    if ($hour < 12) {
                        $greeting = 'Bonjour';
                        $emoji = '🌅';
                    } elseif ($hour < 18) {
                        $greeting = 'Bon après-midi';
                        $emoji = '☀️';
                    } else {
                        $greeting = 'Bonsoir';
                        $emoji = '🌙';
                    }
                @endphp
                {{ $greeting }} {{ Auth::user()->name }} ! {{ $emoji }}
            </h1>
            <p class="greeting-subtitle">
                🎉 La journée est bien partie. Que diriez-vous de consulter vos statistiques ?
            </p>
        </div>
    </div>



    <!-- KPIs Section -->
    <div class="kpis-section mb-4" data-section="financial">
        <div class="row g-2 g-lg-3">
            <!-- Membres actifs -->
            <div class="col-6 col-lg-3">
                <a href="{{ route('members.index') }}" class="text-decoration-none">
                    <div class="kpi-card stat-card animate-on-scroll">
                        <div class="kpi-header">
                        </div>
                        <div class="kpi-meta">
                            <div class="kpi-value">{{ $stats['active_members'] ?? '0' }}</div>
                        </div>
                        <div class="kpi-info">
                            <h3 class="kpi-title">Membres actifs</h3>
                            <p class="kpi-description">Nombre de membres actifs</p>
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
                        $icon = 'bi-gift';
                        $color = 'offerings';
                    }
                    elseif (($k['label'] ?? '') === 'Dons') { 
                        $href = route('donations.index'); 
                        $icon = 'bi-heart';
                        $color = 'donations';
                    }
                    elseif (($k['label'] ?? '') === 'Dépenses') { 
                        $href = route('expenses.index'); 
                        $icon = 'bi-credit-card';
                        $color = 'expenses';
                    }
                @endphp
                <a href="{{ $href }}" class="text-decoration-none">
                    <div class="kpi-card stat-card animate-on-scroll">
                        <div class="kpi-header">
                        </div>
                        <div class="kpi-meta">
                            <div class="kpi-value">
                                @php
                                    // Correction du problème de formatage
                                    $rawValue = $k['label']==='Dépenses' ? ($k['current'] ?? 0) : ($k['value'] ?? 0);
                                    $numericValue = is_numeric($rawValue) ? (float)$rawValue : 0;
                                    $formattedValue = number_format($numericValue, 0, ',', ' ');
                                @endphp
                                {{ $formattedValue }}
                                <span class="kpi-currency">FCFA</span>
                            </div>
                            @if(!is_null($k['delta']))
                                @php $up = ($k['is_expense'] ?? false) ? ($k['delta'] < 0) : ($k['delta'] >= 0); @endphp
                                <div class="kpi-trend {{ $up ? 'trend-up' : 'trend-down' }}">
                                    <i class="bi {{ $up ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>
                                    {{ abs($k['delta']) }}%
                                </div>
                            @endif
                        </div>
                        <div class="kpi-info">
                            <h3 class="kpi-title">{{ $k['label'] }}</h3>
                            <p class="kpi-description">Montant {{ strtolower($k['label']) }}</p>
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
                <div class="chart-card animate-on-scroll">
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
/* Section de salutation élégante */
.greeting-section {
    padding: 0;
    margin-bottom: 2rem;
    overflow: hidden;
}

.greeting-content {
    padding-top: 2rem;
    text-align: left;
}

.greeting-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
    line-height: 1.2;
    font-family: 'EB Garamond', serif;
    letter-spacing: -0.02em;
}

.greeting-subtitle {
    font-size: 1.125rem;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
    font-weight: 400;
    max-width: 600px;
}

/* Responsive pour la section de salutation */
@media (max-width: 768px) {
    .greeting-content {
        padding: 2rem 1.5rem;
        text-align: center;
    }
    
    .greeting-title {
        font-size: 2rem;
    }
    
    .greeting-subtitle {
        font-size: 1rem;
    }
}

/* Les styles pour l'en-tête de page sont maintenant définis dans le layout principal */


/* KPI Cards - Style minimaliste élégant */
.kpi-card {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 2rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
    min-height: 140px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.kpi-card:hover {
    transform: translateY(-2px);
    border-color: #cbd5e1;
}

.kpi-header {
    margin-bottom: 1rem;
}

.kpi-info {
    flex: 1;
    min-width: 0;
    margin-top: 1rem;
}

.kpi-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.kpi-description {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0;
    line-height: 1.4;
}

.kpi-meta {
    margin-bottom: 1rem;
}

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
    line-height: 1;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.kpi-currency {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-left: 0.25rem;
}

.kpi-trend {
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #64748b;
}

.trend-up { color: #22C55E; }
.trend-down { color: #EF4444; }


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
                    borderColor: '#6B7280', 
                    backgroundColor: gradientTithes, 
                    fill: true, 
                    tension: 0.4,
                    pointRadius: 4, 
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#6B7280',
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
                    borderColor: '#6B7280',
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

<!-- Sections de support -->
<div class="container py-4">
    <div class="row g-4">
        <!-- Rejoignez-nous sur YouTube -->
        <div class="col-md-6">
            <div class="support-card">
                <div class="support-card-body">
                    <div class="support-icon youtube-icon">
                        <i class="bi bi-youtube"></i>
                    </div>
                    <div class="support-content">
                        <h3 class="support-title">Rejoignez-nous sur Youtube</h3>
                        <p class="support-description">Découvrez des vidéos pratiques pour apprendre à utiliser Eglix</p>
                        <a href="#" class="btn btn-dark support-btn">Accéder maintenant</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Rejoignez-nous sur WhatsApp -->
        <div class="col-md-6">
            <div class="support-card">
                <div class="support-card-body">
                    <div class="support-icon whatsapp-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div class="support-content">
                        <h3 class="support-title">Rejoignez-nous sur WhatsApp</h3>
                        <p class="support-description">Rejoignez notre canal WhatsApp</p>
                        <a href="#" class="btn btn-success support-btn">Rejoindre</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour les cartes de support - Style exact de l'image */
.support-card {
    background: #ffffff;
    border: none;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: none;
    transition: all 0.3s ease;
    height: 100%;
}

.support-card:hover {
    transform: none;
    box-shadow: none;
}

.support-card-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    height: 100%;
    text-align: center;
}

.support-icon {
    width: 60px;
    height: 60px;
    background: #f5f5f5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: none;
    position: relative;
}

.support-icon i {
    font-size: 1.8rem;
    color: #333333;
}

/* Icône YouTube avec les couleurs officielles */
.support-icon.youtube-icon {
    background: #f5f5f5;
}

.support-icon.youtube-icon i {
    color: #FF0000;
    font-size: 2rem;
}



/* Icône WhatsApp */
.support-icon.whatsapp-icon {
    background: #f5f5f5;
}

.support-icon.whatsapp-icon i {
    color: #25D366;
    font-size: 1.8rem;
}

.support-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    width: 100%;
}

.support-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #000000;
    margin-bottom: 0.5rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    line-height: 1.3;
}

.support-description {
    font-size: 0.875rem;
    color: #000000;
    margin-bottom: 1.5rem;
    line-height: 1.4;
    font-weight: 400;
}

.support-btn {
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.875rem;
    padding: 10px 20px;
    align-self: center;
    transition: all 0.3s ease;
    border: none;
    text-decoration: none;
    display: inline-block;
}

.support-btn.btn-dark {
    background-color: #000000 !important;
    color: #ffffff !important;
    border: none !important;
}


.support-btn.btn-outline-primary {
    background-color: transparent;
    color: #007bff;
    border: 1px solid #007bff;
}

.support-btn.btn-success {
    background-color: #25D366;
    color: #ffffff;
}

.support-btn:hover {
    transform: none;
    box-shadow: none;
    opacity: 0.9;
}

/* Responsive design */
@media (max-width: 768px) {
    .support-btn {
        white-space: nowrap !important;
        overflow: visible !important;
        text-overflow: clip !important;
        min-width: auto !important;
        width: auto !important;
        padding: 10px 20px !important;
        font-size: 0.875rem !important;
    }
    
    /* S'assurer que le texte des boutons reste visible */
    .support-btn .btn-text,
    .support-btn span {
        display: inline !important;
        visibility: visible !important;
    }
}

/* Règles supplémentaires pour très petits écrans */
@media (max-width: 480px) {
    .support-btn {
        white-space: nowrap !important;
        overflow: visible !important;
        text-overflow: clip !important;
        padding: 8px 16px !important;
        font-size: 0.8rem !important;
        width: auto !important;
        min-width: auto !important;
    }
    
    .support-card {
        padding: 1rem !important;
    }
}
</style>
@endsection



