@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">Rapport des Dépenses</h1>
                <p class="page-subtitle">Analyse détaillée des dépenses effectuées</p>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-calendar3 text-muted"></i>
                    <span class="text-muted">{{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.expenses.export', ['from' => $from, 'to' => $to]) }}" class="btn btn-light">
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
                        <button type="submit" class="btn btn w-100">
                            <i class="bi bi-funnel"></i> Appliquer
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.expenses') }}" class="btn btn-outline-secondary w-100">
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
            <h5 class="card-title mb-3">Évolution des Dépenses par Mois</h5>
            <div class="chart-container" style="position: relative; height: 400px; width: 100%;">
                <canvas id="expensesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-receipt-cutoff text-custom" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ number_format($totalAmount ?? 0, 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted mb-0">Total des dépenses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-list-ul text-success" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ $totalCount ?? 0 }}</h4>
                    <p class="text-muted mb-0">Nombre de dépenses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-soft text-center">
                <div class="card-body">
                    <i class="bi bi-graph-up text-info" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3 mb-1" style="color: #202124;">{{ number_format($averageAmount ?? 0, 0, ',', ' ') }} FCFA</h4>
                    <p class="text-muted mb-0">Moyenne par dépense</p>
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

    <!-- Tableau des dépenses -->
    <div class="card card-soft">
        <div class="card-body">
            <h5 class="card-title mb-3">Détail des Dépenses</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Projet</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Méthode de paiement</th>
                            <th>Référence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses ?? [] as $expense)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $expense->description }}</div>
                                @if($expense->notes)
                                    <small class="text-muted">{{ Str::limit($expense->notes, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($expense->project)
                                    <span class="badge bg-custom">{{ $expense->project->name }}</span>
                                @else
                                    <span class="text-muted">Aucun</span>
                                @endif
                            </td>
                            <td>{{ $expense->paid_at->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-danger">{{ number_format($expense->amount, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge bg-{{ $expense->payment_method === 'cash' ? 'success' : ($expense->payment_method === 'mobile' ? 'info' : 'primary') }}">
                                    {{ ucfirst($expense->payment_method) }}
                                </span>
                            </td>
                            <td>{{ $expense->reference ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0">Aucune dépense trouvée pour cette période</p>
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
            label: 'Dépenses (FCFA)',
            data: @json($monthlyData['data'] ?? []),
            borderColor: '#0ea5e9',
            backgroundColor: 'rgba(14, 165, 233, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#0ea5e9',
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
                    borderColor: '#0ea5e9',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Dépenses: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
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
    const ctx = document.getElementById('expensesChart').getContext('2d');
    new Chart(ctx, config);
});
</script>
@endpush
@endsection