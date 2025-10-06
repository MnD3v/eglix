@extends('layouts.app')

@section('content')
<style>
/* Styles pour les cartes de statistiques */
.guest-stats-card {
    background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.guest-stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 16px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    margin-top: 4px;
    font-weight: 500;
}

/* Styles pour les cartes d'invités */
.guest-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.guest-card:hover {
    border-color: #FFCC00;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.guest-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.guest-info {
    flex: 1;
}

.guest-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.guest-contact {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 2px;
}

.guest-status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-visit-1 { background: #dbeafe; color: #1e40af; }
.status-visit-2 { background: #fef3c7; color: #92400e; }
.status-returning { background: #dcfce7; color: #166534; }
.status-converted { background: #fce7f3; color: #be185d; }
.status-interested { background: #fee2e2; color: #dc2626; }

.guest-details {
    display: flex;
    gap: 24px;
    margin-bottom: 16px;
}

.guest-detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.875rem;
    color: #64748b;
}

.guest-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.guest-actions .btn {
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
    padding: 6px 12px;
    transition: all 0.2s ease;
    border-width: 1.5px;
}

.guest-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.guest-actions .btn i {
    color: #000000 !important;
}

/* Styles pour le graphique */
.chart-container {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

#guestsChart {
    width: 100% !important;
    height: 300px !important;
}

/* Styles pour les filtres */
.filter-bar {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.filter-row {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.filter-select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.875rem;
    background: #ffffff;
}

/* Responsive */
@media (max-width: 768px) {
    .guest-details {
        flex-direction: column;
        gap: 8px;
    }
    
    .filter-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .guest-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .guest-actions {
        justify-content: flex-start;
    }
}
</style>

<div class="container py-4">
    <!-- AppBar Invités -->
    <div class="appbar guests-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ route('members.index') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Gestion des Invités</h1>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('guests.create') }}" class="appbar-btn-yellow">
                    <i class="bi bi-person-plus"></i>
                    <span class="btn-text">Nouvel Invité</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="guest-stats-card">
                <div class="stat-icon" style="background: #dbeafe;">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <div class="stat-value">{{ $stats['this_month'] }}</div>
                <div class="stat-label">Visiteurs pour la période</div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="guest-stats-card">
                <div class="stat-icon" style="background: #fef3c7;">
                    <i class="bi bi-person text-warning"></i>
                </div>
                <div class="stat-value">{{ $stats['first_time'] }}</div>
                <div class="stat-label">Premières visites</div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="guest-stats-card">
                <div class="stat-icon" style="background: #dcfce7;">
                    <i class="bi bi-arrow-repeat text-success"></i>
                </div>
                <div class="stat-value">{{ $stats['returning'] }}</div>
                <div class="stat-label">Visiteurs réguliers</div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="guest-stats-card">
                <div class="stat-icon" style="background: #fce7f3;">
                    <i class="bi bi-check-circle text-pink"></i>
                </div>
                <div class="stat-value">{{ $stats['conversions'] }}</div>
                <div class="stat-label">Conversions</div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <form method="GET" class="filter-bar">
        <h5 class="mb-3">🔗 Filtres</h5>
        <div class="filter-row">
            <div class="filter-item">
                <label class="filter-label">Date de début</label>
                <input type="date" name="start_date" value="{{ old('start_date', $startDate ?? '') }}" class="filter-select">
            </div>
            <div class="filter-item">
                <label class="filter-label">Date de fin</label>
                <input type="date" name="end_date" value="{{ old('end_date', $endDate ?? '') }}" class="filter-select">
            </div>
            <div class="filter-item">
                <label class="filter-label">Statut</label>
                <select name="status" class="filter-select">
                    <option value="">Tous les statuts</option>
                    <option value="visit_1" @selected($status == 'visit_1')>Première visite</option>
                    <option value="visit_2_3" @selected($status == 'visit_2_3')>2ème-3ème visite</option>
                    <option value="returning" @selected($status == 'returning')>Visiteur régulier</option>
                    <option value="member_converted" @selected($status == 'member_converted')>Devenu membre</option>
                    <option value="no_longer_interested" @selected($status == 'no_longer_interested')>Plus intéressé</option>
                </select>
            </div>
            <div class="filter-item">
                <label class="filter-label">Origine</label>
                <select name="origin" class="filter-select">
                    <option value="">Toutes les origines</option>
                    <option value="referral" @selected($origin == 'referral')>Invitation</option>
                    <option value="social_media" @selected($origin == 'social_media')>Réseaux sociaux</option>
                    <option value="event" @selected($origin == 'event')>Événement</option>
                    <option value="walk_in" @selected($origin == 'walk_in')>Visite spontanée</option>
                    <option value="flyer" @selected($origin == 'flyer')>Flyer</option>
                    <option value="other" @selected($origin == 'other')>Autre</option>
                </select>
            </div>
            <div class="filter-item">
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">
                    <i class="bi bi-funnel me-1"></i>Filtrer
                </button>
            </div>
        </div>
    </form>

    <!-- Graphique -->
    @if(!empty($chartData))
    <div class="chart-container">
        <h3 class="chart-title">Évolution des Visiteurs (12 derniers mois)</h3>
        <canvas id="guestsChart"></canvas>
    </div>
    @endif

    <!-- Liste des invités -->
    <div class="row">
        <div class="col-12">
            @forelse($guests as $guest)
                <div class="guest-card">
                    <div class="guest-header">
                        <div class="guest-info">
                            <div class="guest-name">{{ $guest->full_name }}</div>
                            <div class="guest-contact">
                                @if($guest->phone)
                                    <i class="bi bi-telephone me-1"></i>{{ $guest->phone }}
                                @endif
                                @if($guest->email)
                                    <i class="bi bi-envelope me-2 ms-3"></i>{{ $guest->email }}
                                @endif
                            </div>
                        </div>
                        <span class="guest-status-badge status-{{ str_replace('_', '-', $guest->status) }}">
                            {{ $guest->status_label }}
                        </span>
                    </div>
                    
                    <div class="guest-details">
                        <div class="guest-detail-item">
                            <i class="bi bi-calendar-event"></i>
                            <span>Visité le {{ $guest->visit_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="guest-detail-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $guest->origin_label }}</span>
                        </div>
                        @if($guest->welcomedBy)
                            <div class="guest-detail-item">
                                <i class="bi bi-person-heart"></i>
                                <span>Accueilli par {{ $guest->welcomedBy->name }}</span>
                            </div>
                        @endif
                    </div>

                    @if($guest->notes)
                        <div class="mt-3">
                            <small class="text-muted">{{ Str::limit($guest->notes, 100) }}</small>
                        </div>
                    @endif

                    <div class="guest-actions">
                        <a href="{{ route('guests.show', $guest) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                        <a href="{{ route('guests.edit', $guest) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        @if($guest->status !== 'member_converted')
                            <form action="{{ route('guests.convert.to.member', $guest) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-person-check"></i> Convertir en membre
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('guests.destroy', $guest) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet invité ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-people display-1 text-muted"></i>
                    <h4 class="mt-3 mb-2">Aucun invité trouvé</h4>
                    <p class="text-muted mb-4">Commencez par enregistrer votre premier visiteur</p>
                    <a href="{{ route('guests.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Ajouter un invité
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    @if($guests->hasPages())
        <div class="mt-4">
            {{ $guests->withQueryString()->links() }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(!empty($chartData))
// Configuration du graphique
const ctx = document.getElementById('guestsChart').getContext('2d');
const chartData = @json($chartData);

const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: chartData.map(item => item.month),
        datasets: [
            {
                label: 'Premières visites',
                data: chartData.map(item => item.first_time),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Conversion en membres',
                data: chartData.map(item => item.conversions),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Total visiteurs',
                data: chartData.map(item => item.total),
                borderColor: '#FFCC00',
                backgroundColor: 'rgba(255, 204, 0, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: false,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: false
            },
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 6
            }
        }
    }
});
@endif
</script>
@endsection