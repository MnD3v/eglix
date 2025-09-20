@extends('layouts.app')

@section('content')
<style>
.subscription-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.subscription-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.subscription-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.subscription-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.subscription-subtitle {
    color: #6b7280;
    font-size: 1rem;
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
    border-left: 4px solid #3b82f6;
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
}

.status-expired {
    background-color: #fef2f2;
    color: #dc2626;
}

.status-suspended {
    background-color: #fef3c7;
    color: #d97706;
}

.subscription-details {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.details-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    color: #1f2937;
    font-weight: 500;
}

@media (max-width: 768px) {
    .subscription-container {
        padding: 1rem;
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

<div class="subscription-container">
    <div class="subscription-card">
        <div class="subscription-header">
            <h1 class="subscription-title">Abonnement en cours</h1>
            <p class="subscription-subtitle">{{ $church->name }}</p>
        </div>

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
@endsection