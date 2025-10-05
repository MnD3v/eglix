@extends('layouts.app')

@section('content')
<style>
/* Styles pour les informations de l'église */
.church-info-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.church-content {
    padding: 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.info-section:last-child {
    margin-bottom: 0;
}

.section-title {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 1rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-value {
    font-size: 1rem;
    color: #1e293b;
    font-weight: 500;
}

.info-value.empty {
    color: #94a3b8;
    font-style: italic;
}

.subscription-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
}

.subscription-active {
    background: rgba(34, 197, 94, 0.1);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.subscription-expired {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.subscription-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #d97706;
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.stat-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #64748b;
    font-weight: 500;
}

/* Styles pour la liste des comptes */
.accounts-list {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.account-row {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    padding: 1rem 1.5rem;
    gap: 1.5rem;
    min-height: 80px;
}

.account-row-separated {
    margin-top: 0.5rem;
    padding-top: 1.5rem;
}

.account-row:hover {
    background: #fafbfc;
    border-color: #e2e8f0;
}

.account-row-body {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex: 1;
}

.account-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.account-date {
    margin-bottom: 4px;
}

.account-name {
    font-size: 16px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #1e293b;
    margin: 0;
    line-height: 1.3;
}

.account-details {
    font-size: 14px;
    color: #64748b;
    margin: 4px 0 0 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.account-row-empty {
    text-align: center;
    padding: 3rem 1rem;
    color: #64748b;
}

.account-row-empty i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.badge.bg-custom {
    background-color: #667eea;
    color: white;
}

@media (max-width: 768px) {
    .church-content {
        padding: 1.5rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .account-row {
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        padding: 1rem;
    }
    
    .account-row-body {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.75rem;
    }
    
    .account-info {
        width: 100%;
        text-align: center;
    }
    
    .account-name {
        font-size: 15px;
        word-break: break-word;
    }
    
    .account-details {
        justify-content: center;
        flex-wrap: wrap;
        gap: 4px;
    }
}
</style>

<div class="container-fluid">
    <!-- AppBar -->
    @include('components.appbar', [
        'title' => $church->name,
        'subtitle' => $church->description ?? 'Informations de l\'église',
        'icon' => 'bi-building',
        'color' => 'primary',
        'actions' => [
            [
                'type' => 'primary',
                'url' => route('churches.edit', $church),
                'label' => 'Modifier',
                'icon' => 'bi-pencil-square'
            ]
        ]
    ])

    <!-- Informations générales -->
    <div class="church-info-card">
        <div class="church-content">
            <div class="info-section">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    Informations générales
                </h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-geo-alt"></i>
                            Adresse
                        </div>
                        <div class="info-value {{ empty($church->address) ? 'empty' : '' }}">
                            {{ $church->address ?? 'Non renseignée' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-telephone"></i>
                            Téléphone
                        </div>
                        <div class="info-value {{ empty($church->phone) ? 'empty' : '' }}">
                            {{ $church->phone ?? 'Non renseigné' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-envelope"></i>
                            Email
                        </div>
                        <div class="info-value {{ empty($church->email) ? 'empty' : '' }}">
                            {{ $church->email ?? 'Non renseigné' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-globe"></i>
                            Site web
                        </div>
                        <div class="info-value {{ empty($church->website) ? 'empty' : '' }}">
                            @if($church->website)
                                <a href="{{ $church->website }}" target="_blank" rel="noopener">{{ $church->website }}</a>
                            @else
                                Non renseigné
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut d'abonnement -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="bi bi-credit-card"></i>
                    Abonnement
                </h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-shield-check"></i>
                            Statut
                        </div>
                        <div class="info-value">
                            @if($church->hasActiveSubscription())
                                <span class="subscription-status subscription-active">
                                    <i class="bi bi-check-circle"></i>
                                    Actif
                                </span>
                            @elseif($church->isSubscriptionExpired())
                                <span class="subscription-status subscription-expired">
                                    <i class="bi bi-x-circle"></i>
                                    Expiré
                                </span>
                            @else
                                <span class="subscription-status subscription-pending">
                                    <i class="bi bi-clock"></i>
                                    En attente
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($church->subscription_end_date)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-calendar-event"></i>
                            Date d'expiration
                        </div>
                        <div class="info-value">
                            {{ $church->subscription_end_date->format('d/m/Y') }}
                        </div>
                    </div>
                    @endif
                    
                    @if($church->subscription_amount)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-currency-dollar"></i>
                            Montant
                        </div>
                        <div class="info-value">
                            {{ number_format($church->subscription_amount, 2) }} {{ $church->subscription_currency ?? 'EUR' }}
                        </div>
                    </div>
                    @endif
                    
                    @if($church->subscription_plan)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="bi bi-box"></i>
                            Plan
                        </div>
                        <div class="info-value">
                            {{ ucfirst($church->subscription_plan) }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comptes de l'église -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="bi bi-people"></i>
                    Comptes de l'église
                </h3>
                
                <div class="accounts-list">
                    @forelse($users as $index => $user)
                        <div class="account-row {{ $index > 0 ? 'account-row-separated' : '' }}">
                            <div class="account-row-body">
                                <div class="account-info">
                                    <div class="account-date">
                                        <span class="badge bg-custom">{{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="account-name">
                                        {{ $user->name }}
                                    </div>
                                    <div class="account-details">
                                        <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                                        <span class="ms-2"><i class="bi bi-person-badge me-1"></i>{{ $user->role->name ?? 'Aucun rôle' }}</span>
                                        @if($user->is_church_admin)
                                            <span class="ms-2"><i class="bi bi-shield-check me-1"></i>Administrateur</span>
                                        @else
                                            <span class="ms-2"><i class="bi bi-person me-1"></i>Utilisateur</span>
                                        @endif
                                        @if($user->is_active)
                                            <span class="ms-2"><i class="bi bi-check-circle me-1"></i>Actif</span>
                                        @else
                                            <span class="ms-2"><i class="bi bi-x-circle me-1"></i>Inactif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="account-row-empty">
                            <i class="bi bi-people"></i>
                            <div>Aucun utilisateur trouvé</div>
                            <small class="text-muted mt-2">Commencez par créer le premier utilisateur de votre église</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection