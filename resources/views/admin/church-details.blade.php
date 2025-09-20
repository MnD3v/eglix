@extends('layouts.app')

@section('content')
<style>
.detail-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    margin-bottom: 2rem;
}

.church-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e8f0fe;
}

.church-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
    color: white;
    margin-right: 1.5rem;
}

.church-info-large {
    flex-grow: 1;
}

.church-name-large {
    color: #202124;
    font-weight: 600;
    font-size: 1.5rem;
    margin: 0;
}

.church-meta-large {
    color: #5f6368;
    font-size: 1rem;
    margin: 0.5rem 0 0 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    border-left: 3px solid #1a73e8;
}

.info-card-title {
    font-weight: 600;
    color: #1a73e8;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.info-card-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.subscription-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    margin-bottom: 1.5rem;
}

.subscription-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.subscription-plan {
    font-size: 1.2rem;
    font-weight: 600;
    color: #1a73e8;
    margin: 0;
}

.status-badge {
    border: none;
    border-radius: 16px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    font-size: 0.75rem;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.status-active {
    background: #34a853;
}

.status-badge.status-expired {
    background: #ea4335;
}

.status-badge.status-pending {
    background: #fbbc04;
    color: #1a1a1a;
}

.status-badge.status-suspended {
    background: #5f6368;
}

.subscription-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.subscription-detail {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 0.75rem;
}

.subscription-detail-label {
    font-size: 0.8rem;
    color: #5f6368;
    margin-bottom: 0.25rem;
}

.subscription-detail-value {
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e8eaed;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.action-btn {
    background: #1a73e8;
    color: white;
}

.action-btn:hover {
    background: #1557b0;
    color: white;
    text-decoration: none;
}

.action-btn-secondary {
    background: #f8f9fa;
    color: #5f6368;
    border: 1px solid #dadce0;
}

.action-btn-secondary:hover {
    background: #e8eaed;
    color: #202124;
    text-decoration: none;
}

.action-btn-success {
    background: #34a853;
    color: white;
}

.action-btn-success:hover {
    background: #2d8f47;
    color: white;
    text-decoration: none;
}

.action-btn-warning {
    background: #fbbc04;
    color: #1a1a1a;
}

.action-btn-warning:hover {
    background: #f9ab00;
    color: #1a1a1a;
    text-decoration: none;
}

.action-btn-danger {
    background: #ea4335;
    color: white;
}

.action-btn-danger:hover {
    background: #d33b2c;
    color: white;
    text-decoration: none;
}

.users-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    margin-bottom: 2rem;
}

.user-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border-bottom: 1px solid #f1f3f4;
}

.user-item:last-child {
    border-bottom: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e8f0fe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    color: #1a73e8;
    margin-right: 0.75rem;
}

.user-info {
    flex-grow: 1;
}

.user-name {
    font-weight: 500;
    color: #202124;
    margin: 0;
    font-size: 0.9rem;
}

.user-email {
    color: #5f6368;
    font-size: 0.8rem;
    margin: 0.25rem 0 0 0;
}

.user-role {
    background: #e8f0fe;
    color: #1a73e8;
    border-radius: 12px;
    padding: 0.25rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 500;
}

.empty-state {
    text-align: center;
    padding: 2rem;
    color: #6B7280;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.875rem;
}
</style>

<div class="container py-4">
    <!-- AppBar Administration -->
    <div class="appbar admin-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">{{ $church->name }}</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-geo-alt appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">{{ $church->address }}</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('admin.create-subscription', $church) }}" class="appbar-btn">
                    <i class="bi bi-plus-circle"></i>
                    <span>Nouvel abonnement</span>
                </a>
                <a href="{{ route('admin.index') }}" class="appbar-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="detail-card">
        <!-- En-tête de l'église -->
        <div class="church-header">
            @php $initials = strtoupper(mb_substr($church->name ?? '',0,2)); @endphp
            <div class="church-avatar-large">{{ $initials }}</div>
            <div class="church-info-large">
                <h2 class="church-name-large">{{ $church->name }}</h2>
                <p class="church-meta-large">
                    @if($church->address)
                        <i class="bi bi-geo-alt me-2"></i>{{ $church->address }}
                    @endif
                </p>
                <p class="church-meta-large">
                    @if($church->phone)
                        <i class="bi bi-telephone me-2"></i>{{ $church->phone }}
                    @endif
                    @if($church->email)
                        <i class="bi bi-envelope ms-3 me-2"></i>{{ $church->email }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Informations générales -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-title">Date d'inscription</div>
                <p class="info-card-value">{{ $church->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="info-card">
                <div class="info-card-title">Nombre d'utilisateurs</div>
                <p class="info-card-value">{{ $church->users->count() }}</p>
            </div>
            <div class="info-card">
                <div class="info-card-title">Site web</div>
                <p class="info-card-value">{{ $church->website ?: 'Non renseigné' }}</p>
            </div>
            <div class="info-card">
                <div class="info-card-title">Statut</div>
                <p class="info-card-value">{{ $church->is_active ? 'Actif' : 'Inactif' }}</p>
            </div>
        </div>

        @if($church->description)
        <div class="info-card">
            <div class="info-card-title">Description</div>
            <p class="info-card-value">{{ $church->description }}</p>
        </div>
        @endif
    </div>

    <!-- Abonnements -->
    <div class="detail-card">
        <h3 class="mb-3">
            <i class="bi bi-credit-card me-2"></i>
            Historique des abonnements
        </h3>

        @if($church->subscriptions->count() > 0)
            @foreach($church->subscriptions as $subscription)
            <div class="subscription-card">
                <div class="subscription-header">
                    <h4 class="subscription-plan">{{ \App\Models\Subscription::getPlans()[$subscription->plan_name] }}</h4>
                    <span class="status-badge status-{{ $subscription->is_active }}">
                        {{ \App\Models\Subscription::getStatuses()[$subscription->is_active] }}
                    </span>
                </div>

                <div class="subscription-details">
                    <div class="subscription-detail">
                        <div class="subscription-detail-label">Montant</div>
                        <p class="subscription-detail-value">{{ $subscription->formatted_amount }}</p>
                    </div>
                    <div class="subscription-detail">
                        <div class="subscription-detail-label">Période</div>
                        <p class="subscription-detail-value">
                            {{ $subscription->start_date->format('d/m/Y') }} - {{ $subscription->end_date->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="subscription-detail">
                        <div class="subscription-detail-label">Limite membres</div>
                        <p class="subscription-detail-value">{{ $subscription->max_members }}</p>
                    </div>
                    <div class="subscription-detail">
                        <div class="subscription-detail-label">Paiement</div>
                        <p class="subscription-detail-value">{{ \App\Models\Subscription::getPaymentStatuses()[$subscription->payment_status] }}</p>
                    </div>
                </div>

                @if($subscription->notes)
                <div class="subscription-detail">
                    <div class="subscription-detail-label">Notes</div>
                    <p class="subscription-detail-value">{{ $subscription->notes }}</p>
                </div>
                @endif

                <div class="action-buttons">
                    @if($subscription->payment_status === 'pending')
                        <form method="POST" action="{{ route('admin.mark-subscription-paid', $subscription) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="action-btn action-btn-success" onclick="return confirm('Marquer comme payé ?')">
                                <i class="bi bi-check-circle"></i>
                                Marquer payé
                            </button>
                        </form>
                    @endif

                    @if($subscription->is_active === 'active')
                        <form method="POST" action="{{ route('admin.renew-subscription', $subscription) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="months" value="12">
                            <button type="submit" class="action-btn action-btn-warning" onclick="return confirm('Renouveler pour 12 mois ?')">
                                <i class="bi bi-arrow-clockwise"></i>
                                Renouveler
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.suspend-subscription', $subscription) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="action-btn action-btn-danger" onclick="return confirm('Suspendre cet abonnement ?')">
                                <i class="bi bi-pause-circle"></i>
                                Suspendre
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bi bi-credit-card"></i>
                <h3>Aucun abonnement</h3>
                <p>Cette église n'a pas encore d'abonnement.</p>
                <a href="{{ route('admin.create-subscription', $church) }}" class="action-btn action-btn">
                    <i class="bi bi-plus-circle me-2"></i>
                    Créer un abonnement
                </a>
            </div>
        @endif
    </div>

    <!-- Utilisateurs -->
    <div class="users-section">
        <h3 class="mb-3">
            <i class="bi bi-people me-2"></i>
            Utilisateurs ({{ $church->users->count() }})
        </h3>

        @if($church->users->count() > 0)
            @foreach($church->users as $user)
            <div class="user-item">
                @php $initials = strtoupper(mb_substr($user->name ?? '',0,2)); @endphp
                <div class="user-avatar">{{ $initials }}</div>
                <div class="user-info">
                    <h5 class="user-name">{{ $user->name }}</h5>
                    <p class="user-email">{{ $user->email }}</p>
                </div>
                <div class="user-role">
                    {{ $user->is_church_admin ? 'Admin' : 'Utilisateur' }}
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bi bi-person"></i>
                <h3>Aucun utilisateur</h3>
                <p>Cette église n'a pas encore d'utilisateurs.</p>
            </div>
        @endif
    </div>
</div>
@endsection
