@extends('layouts.app')

@section('content')
<link href="{{ asset('css/admin-cards.css') }}" rel="stylesheet">

<div class="container py-4">
    <!-- AppBar Administration -->
    <div class="appbar admin-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="appbar-text">
                    <h1 class="appbar-title">Administration</h1>
                    <p class="appbar-subtitle">Gestion des églises et abonnements</p>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('admin.export-churches') }}" class="appbar-btn-secondary">
                    <i class="bi bi-download"></i>
                    <span>Exporter</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_churches'] }}</div>
            <div class="stat-label">Total Églises</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['active_subscriptions'] }}</div>
            <div class="stat-label">Abonnements actifs</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['expired_subscriptions'] }}</div>
            <div class="stat-label">Abonnements expirés</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['churches_without_subscription'] }}</div>
            <div class="stat-label">Sans abonnement</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.index') }}">
            <div class="filter-group">
                <label for="q">Rechercher :</label>
                <input type="text" name="q" id="q" value="{{ $search }}" placeholder="Nom, adresse, téléphone...">
                
                <label for="subscription">Statut :</label>
                <select name="subscription" id="subscription">
                    <option value="">Tous les statuts</option>
                    @foreach($subscriptionStatuses as $value => $label)
                        <option value="{{ $value }}" {{ $subscriptionFilter === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="btn btn">Filtrer</button>
            </div>
        </form>
    </div>

    <!-- Export -->
    <div class="export-section">
        <h3 class="export-title">Export des données</h3>
        <p class="export-text">Téléchargez la liste complète des églises avec leurs informations d'abonnement.</p>
        <a href="{{ route('admin.export-churches') }}" class="btn btn">
            <i class="bi bi-download me-2"></i>
            Exporter en CSV
        </a>
    </div>

    <!-- Liste des églises -->
    @if($churches->count() > 0)
        <div class="row g-3">
            @foreach($churches as $church)
            <div class="col-lg-6">
                <a href="{{ route('admin.church-details', $church) }}" class="admin-card-clickable">
                    <div class="church-info">
                        @php $initials = strtoupper(mb_substr($church->name ?? '',0,2)); @endphp
                        <div class="church-avatar">{{ $initials }}</div>
                        <div class="church-details">
                            <h5 class="church-name">{{ $church->name }}</h5>
                            <p class="church-meta">
                                @if($church->address)
                                    <i class="bi bi-geo-alt me-2"></i>{{ $church->address }}
                                @endif
                                @if($church->phone)
                                    <i class="bi bi-telephone ms-3 me-2"></i>{{ $church->phone }}
                                @endif
                            </p>
                            <p class="church-meta">
                                <i class="bi bi-people me-2"></i>{{ $church->users->count() }} utilisateur(s)
                                <i class="bi bi-calendar ms-3 me-2"></i>Inscrit le {{ $church->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="subscription-status">
                        @if($church->hasActiveSubscription())
                            <span class="status-badge status-active">Actif</span>
                            <span class="subscription-plan">{{ ucfirst($church->subscription_plan) }}</span>
                        @elseif($church->isSubscriptionExpired())
                            <span class="status-badge status-expired">Expiré</span>
                            <span class="subscription-plan">Dernier: {{ ucfirst($church->subscription_plan) }}</span>
                        @elseif($church->subscription_status === 'suspended')
                            <span class="status-badge status-suspended">Suspendu</span>
                            <span class="subscription-plan">{{ ucfirst($church->subscription_plan) }}</span>
                        @else
                            <span class="status-badge status-no-subscription">Sans abonnement</span>
                        @endif
                    </div>
                    @if($church->subscription_end_date)
                        <div class="subscription-info">
                            <div class="subscription-plan">
                                <i class="bi bi-currency-exchange me-2"></i>
                                {{ $church->subscription_amount ? number_format($church->subscription_amount, 0, ',', ' ') . ' ' . $church->subscription_currency : 'Non défini' }}
                            </div>
                            <p class="subscription-details">
                                <i class="bi bi-calendar-check me-2"></i>
                                Expire le {{ $church->subscription_end_date->format('d/m/Y') }}
                                @if($church->subscription_end_date->isFuture())
                                    ({{ $church->getSubscriptionDaysRemaining() }} jours restants)
                                @else
                                    (Expiré)
                                @endif
                            </p>
                        </div>
                    @endif

                    <div class="action-buttons">
                        @if(!$church->subscription_end_date || $church->isSubscriptionExpired())
                            <a href="{{ route('admin.create-subscription', $church) }}" class="action-btn action-btn-success" onclick="event.stopPropagation()">
                                <i class="bi bi-plus-circle"></i>
                                Créer abonnement
                            </a>
                        @endif
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $churches->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-building" style="font-size: 4rem; color: #d1d5db;"></i>
            </div>
            <h3 class="text-muted mb-3">Aucune église trouvée</h3>
            <p class="text-muted">
                @if($search || $subscriptionFilter)
                    Aucune église ne correspond à vos critères de recherche.
                @else
                    Aucune église n'est encore enregistrée dans le système.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
