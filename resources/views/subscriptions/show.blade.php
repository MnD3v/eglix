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

.detail-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e8f0fe;
}

.member-avatar-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e8f0fe;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
    color: #1a73e8;
    margin-right: 1rem;
}

.member-info-large {
    flex-grow: 1;
}

.member-name-large {
    color: #202124;
    font-weight: 600;
    font-size: 1.25rem;
    margin: 0;
}

.member-details-large {
    color: #5f6368;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.status-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
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

.status-badge.status-suspended {
    background: #fbbc04;
    color: #1a1a1a;
}

.status-badge.status-cancelled {
    background: #5f6368;
}

.payment-badge {
    border: none;
    border-radius: 12px;
    padding: 0.3rem 0.6rem;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-badge.payment-paid {
    background: #34a853;
    color: white;
}

.payment-badge.payment-pending {
    background: #fbbc04;
    color: #1a1a1a;
}

.payment-badge.payment-overdue {
    background: #ea4335;
    color: white;
}

.payment-badge.payment-cancelled {
    background: #5f6368;
    color: white;
}

.type-badge {
    background: #e8f0fe;
    color: #1a73e8;
    border-radius: 12px;
    padding: 0.3rem 0.6rem;
    font-size: 0.7rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.info-card-subtitle {
    font-size: 0.8rem;
    color: #5f6368;
    margin: 0.25rem 0 0 0;
}

.amount-display-large {
    background: #e8f0fe;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    border: 2px solid #1a73e8;
    margin-bottom: 2rem;
}

.amount-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a73e8;
    margin: 0;
}

.amount-currency {
    font-size: 1rem;
    color: #5f6368;
    margin: 0.5rem 0 0 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-top: 2rem;
    padding-top: 2rem;
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

.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e8eaed;
    padding: 1.5rem;
}

.modal-title {
    font-weight: 600;
    color: #202124;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e8eaed;
    padding: 1.5rem;
}

.form-label {
    font-weight: 500;
    color: #5f6368;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #dadce0;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
}

.btn {
    background: #1a73e8;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    background: #1557b0;
}

.btn-secondary {
    background: #f8f9fa;
    color: #5f6368;
    border: 1px solid #dadce0;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    background: #e8eaed;
    color: #202124;
}
</style>

<div class="container py-4">
    <!-- AppBar Abonnements -->
    <div class="appbar subscriptions-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Détails de l'abonnement</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-person appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">{{ $subscription->member->last_name }} {{ $subscription->member->first_name }}</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('subscriptions.edit', $subscription) }}" class="appbar-btn-secondary">
                    <i class="bi bi-pencil"></i>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="appbar-btn-secondary">
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
        <!-- En-tête avec informations du membre -->
        <div class="detail-header">
            @php $initials = strtoupper(mb_substr($subscription->member->first_name ?? '',0,1).mb_substr($subscription->member->last_name ?? '',0,1)); @endphp
            <div class="member-avatar-large">{{ $initials }}</div>
            <div class="member-info-large">
                <h2 class="member-name-large">{{ $subscription->member->last_name }} {{ $subscription->member->first_name }}</h2>
                <p class="member-details-large">
                    @if($subscription->member->phone)
                        <i class="bi bi-telephone me-2"></i>{{ $subscription->member->phone }}
                    @endif
                    @if($subscription->member->email)
                        <i class="bi bi-envelope ms-3 me-2"></i>{{ $subscription->member->email }}
                    @endif
                </p>
                <div class="status-badges">
                    <span class="type-badge">{{ \App\Models\Subscription::getSubscriptionTypes()[$subscription->subscription_type] }}</span>
                    <span class="status-badge status-{{ $subscription->status }}">{{ \App\Models\Subscription::getStatuses()[$subscription->status] }}</span>
                    <span class="payment-badge payment-{{ $subscription->payment_status }}">{{ \App\Models\Subscription::getPaymentStatuses()[$subscription->payment_status] }}</span>
                </div>
            </div>
        </div>

        <!-- Montant de l'abonnement -->
        <div class="amount-display-large">
            <h3 class="amount-value">{{ number_format($subscription->amount, 0, ',', ' ') }}</h3>
            <p class="amount-currency">{{ $subscription->currency }}</p>
        </div>

        <!-- Grille d'informations -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-title">Date de début</div>
                <p class="info-card-value">{{ $subscription->start_date->format('d/m/Y') }}</p>
                <p class="info-card-subtitle">{{ $subscription->start_date->diffForHumans() }}</p>
            </div>

            <div class="info-card">
                <div class="info-card-title">Date de fin</div>
                <p class="info-card-value">{{ $subscription->end_date->format('d/m/Y') }}</p>
                <p class="info-card-subtitle">{{ $subscription->end_date->diffForHumans() }}</p>
            </div>

            @if($subscription->payment_date)
            <div class="info-card">
                <div class="info-card-title">Date de paiement</div>
                <p class="info-card-value">{{ $subscription->payment_date->format('d/m/Y') }}</p>
                <p class="info-card-subtitle">{{ $subscription->payment_date->diffForHumans() }}</p>
            </div>
            @endif

            <div class="info-card">
                <div class="info-card-title">Méthode de paiement</div>
                <p class="info-card-value">{{ \App\Models\Subscription::getPaymentMethods()[$subscription->payment_method] }}</p>
            </div>

            @if($subscription->receipt_number)
            <div class="info-card">
                <div class="info-card-title">Numéro de reçu</div>
                <p class="info-card-value">{{ $subscription->receipt_number }}</p>
            </div>
            @endif

            @if($subscription->payment_reference)
            <div class="info-card">
                <div class="info-card-title">Référence de paiement</div>
                <p class="info-card-value">{{ $subscription->payment_reference }}</p>
            </div>
            @endif

            <div class="info-card">
                <div class="info-card-title">Créé par</div>
                <p class="info-card-value">{{ $subscription->createdBy->name ?? 'Système' }}</p>
                <p class="info-card-subtitle">{{ $subscription->created_at->format('d/m/Y à H:i') }}</p>
            </div>

            @if($subscription->updatedBy && $subscription->updated_at != $subscription->created_at)
            <div class="info-card">
                <div class="info-card-title">Dernière modification</div>
                <p class="info-card-value">{{ $subscription->updatedBy->name }}</p>
                <p class="info-card-subtitle">{{ $subscription->updated_at->format('d/m/Y à H:i') }}</p>
            </div>
            @endif
        </div>

        <!-- Notes -->
        @if($subscription->notes)
        <div class="info-card">
            <div class="info-card-title">Notes</div>
            <p class="info-card-value" style="white-space: pre-line;">{{ $subscription->notes }}</p>
        </div>
        @endif

        <!-- Boutons d'action -->
        <div class="action-buttons">
            <a href="{{ route('subscriptions.edit', $subscription) }}" class="action-btn action-btn-secondary">
                <i class="bi bi-pencil"></i>
                Modifier
            </a>

            @if($subscription->payment_status !== 'paid')
                <button type="button" class="action-btn action-btn-success" data-bs-toggle="modal" data-bs-target="#markPaidModal">
                    <i class="bi bi-check-circle"></i>
                    Marquer comme payé
                </button>
            @endif

            @if($subscription->status === 'active')
                <button type="button" class="action-btn action-btn" data-bs-toggle="modal" data-bs-target="#renewModal">
                    <i class="bi bi-arrow-clockwise"></i>
                    Renouveler
                </button>

                <button type="button" class="action-btn action-btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal">
                    <i class="bi bi-pause-circle"></i>
                    Suspendre
                </button>

                <button type="button" class="action-btn action-btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </button>
            @endif

            <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}" class="d-inline" onsubmit="return confirm('Supprimer définitivement cet abonnement ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="action-btn action-btn-danger">
                    <i class="bi bi-trash"></i>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Marquer comme payé -->
<div class="modal fade" id="markPaidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('subscriptions.mark-paid', $subscription) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Marquer comme payé</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Méthode de paiement *</label>
                        <select class="form-select" name="payment_method" required>
                            @foreach(\App\Models\Subscription::getPaymentMethods() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Numéro de reçu</label>
                        <input type="text" class="form-control" name="receipt_number" placeholder="Ex: REC-2024-001">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">Référence de paiement</label>
                        <input type="text" class="form-control" name="payment_reference" placeholder="Ex: REF-2024-001">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn">Confirmer le paiement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Renouveler -->
<div class="modal fade" id="renewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('subscriptions.renew', $subscription) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Renouveler l'abonnement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Durée du renouvellement (mois) *</label>
                        <select class="form-select" name="months" required>
                            <option value="1">1 mois</option>
                            <option value="3">3 mois</option>
                            <option value="6">6 mois</option>
                            <option value="12" selected>12 mois</option>
                            <option value="24">24 mois</option>
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Le renouvellement étendra la date de fin de l'abonnement et réinitialisera le statut de paiement.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn">Renouveler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Suspendre -->
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('subscriptions.suspend', $subscription) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Suspendre l'abonnement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Raison de la suspension</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Expliquez la raison de la suspension..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Suspendre</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Annuler -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('subscriptions.cancel', $subscription) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Annuler l'abonnement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Raison de l'annulation</label>
                        <textarea class="form-control" name="reason" rows="3" placeholder="Expliquez la raison de l'annulation..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Cette action est irréversible. L'abonnement sera définitivement annulé.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
