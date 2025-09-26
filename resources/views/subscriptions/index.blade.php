@extends('layouts.app')

@section('content')
<style>

.subscription-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0;
}

.subscription-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.subscription-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-item {
    background: #f9fafb;
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid #FFCC00;
    transition: all 0.2s ease;
}

.info-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 204, 0, 0.15);
}

.info-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.info-value {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-expired {
    background-color: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.status-suspended {
    background-color: #fef3c7;
    color: #d97706;
    border: 1px solid #fed7aa;
}

.subscription-details {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}

.subscription-details:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.details-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.details-title::before {
    content: '';
    width: 4px;
    height: 20px;
    background: #FFCC00;
    border-radius: 2px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    padding: 1rem;
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.detail-item:hover {
    border-color: #FFCC00;
    box-shadow: 0 2px 8px rgba(255, 204, 0, 0.1);
}

.detail-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

@media (max-width: 768px) {
    .subscription-container {
        padding: 0 1rem;
    }
    
    .subscription-card {
        padding: 1.5rem;
    }
    
    .subscription-info {
        grid-template-columns: 1fr;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container py-4">
    <!-- AppBar Abonnements -->
    <div class="appbar subscriptions-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <a href="{{ url('/') }}" class="appbar-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Abonnement</h1>
                </div>
            </div>
            <div class="appbar-right">
                @if($church->hasActiveSubscription())
                    <a href="{{ route('subscription.renewal') }}" class="appbar-btn-yellow">
                        <i class="bi bi-arrow-clockwise"></i>
                        <span class="btn-text">Renouveler</span>
                    </a>
                @else
                    <a href="{{ route('subscription.request') }}" class="appbar-btn-yellow">
                        <i class="bi bi-plus-lg"></i>
                        <span class="btn-text">Demander</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="subscription-container">
        <div class="subscription-card">

        <div class="subscription-info">
            <div class="info-item">
                <div class="info-label">Plan d'abonnement</div>
                <div class="info-value">Basique</div>
            </div>
            
            <div class="info-item">
                <div class="info-label">Statut</div>
                <div class="info-value">
                    @if($church->hasActiveSubscription())
                        <span class="status-badge status-active">
                            <i class="bi bi-check-circle me-2"></i>
                            Actif
                        </span>
                    @elseif($church->isSubscriptionExpired())
                        <span class="status-badge status-expired">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Expiré
                        </span>
                    @else
                        <span class="status-badge status-suspended">
                            <i class="bi bi-pause-circle me-2"></i>
                            Suspendu
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="subscription-details">
            <h3 class="details-title">Détails de l'abonnement</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Date de début</span>
                    <span class="detail-value">
                        {{ $church->subscription_start_date ? $church->subscription_start_date->format('d/m/Y') : 'Non défini' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Date d'expiration</span>
                    <span class="detail-value">
                        {{ $church->subscription_end_date ? $church->subscription_end_date->format('d/m/Y') : 'Non défini' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Montant</span>
                    <span class="detail-value">
                        {{ $church->subscription_amount ? number_format($church->subscription_amount, 0, ',', ' ') . ' ' . $church->subscription_currency : 'Non défini' }}
                    </span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Référence de paiement</span>
                    <span class="detail-value">
                        {{ $church->payment_reference ?: 'Non défini' }}
                    </span>
                </div>
            </div>
        </div>

        @if($church->subscription_notes)
        <div class="subscription-details mt-4">
            <h3 class="details-title">Notes</h3>
            <p class="text-gray-700">{{ $church->subscription_notes }}</p>
        </div>
        @endif
        </div>
    </div>
</div>
@endsection