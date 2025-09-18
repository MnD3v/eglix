@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">Rapport des Dîmes</h1>
                <p class="page-subtitle">Analyse détaillée des dîmes reçues</p>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-calendar3 text-muted"></i>
                    <span class="text-muted">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.tithes.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-light">
                    <i class="bi bi-download"></i> Télécharger CSV
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Retour aux rapports
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres de période -->
    <div class="card card-soft mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Filtrer par période</h5>
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Appliquer
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.tithes') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Graphique d'évolution annuelle -->
    <div class="card card-soft mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Évolution des Dîmes par Mois</h5>
            <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                <canvas id="tithesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-wallet2 text-primary" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ number_format($totalTithes ?? 0, 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted mb-0">Total des dîmes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-people text-success" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ $totalMembers ?? 0 }}</h4>
                    <p class="text-muted mb-0">Membres contributeurs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-graph-up text-info" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ number_format($averageTithe ?? 0, 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted mb-0">Moyenne par membre</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-month text-warning" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ $totalMonths ?? 0 }}</h4>
                    <p class="text-muted mb-0">Mois avec données</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des dîmes -->
    <div class="card card-soft">
        <div class="card-body">
            <h5 class="card-title mb-3">Détail des Dîmes</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Membre</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Méthode de paiement</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tithes ?? [] as $tithe)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="member-avatar me-2" style="width: 32px; height: 32px; border-radius: 50%; background: #e8f0fe; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem; color: #1a73e8;">
                                        {{ substr($tithe->member->first_name, 0, 1) }}{{ substr($tithe->member->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $tithe->member->last_name }} {{ $tithe->member->first_name }}</div>
                                        <small class="text-muted">{{ $tithe->member->email ?? 'Aucun email' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $tithe->paid_at->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-success">{{ number_format($tithe->amount, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge bg-{{ $tithe->payment_method === 'cash' ? 'success' : ($tithe->payment_method === 'mobile' ? 'info' : 'primary') }}">
                                    {{ ucfirst($tithe->payment_method) }}
                                </span>
                            </td>
                            <td>{{ $tithe->reference ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">Aucune dîme trouvée pour cette période</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données du graphique
    const chartData = {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
        datasets: [{
            label: 'Dîmes (FCFA)',
            data: @json($monthlyData['data'] ?? []),
            borderColor: '#1a73e8',
            backgroundColor: 'rgba(26, 115, 232, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#1a73e8',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    };

    // Configuration du graphique
    const config = {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 14,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#1a73e8',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Dîmes: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#5f6368'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#5f6368',
                        callback: function(value) {
                            return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    };

    // Création du graphique
    const ctx = document.getElementById('tithesChart').getContext('2d');
    new Chart(ctx, config);
});
</script>
@endpush
@endsection