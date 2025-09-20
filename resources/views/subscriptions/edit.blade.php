@extends('layouts.app')

@section('content')
<style>
.form-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border: 1px solid #f1f3f4;
    margin-bottom: 2rem;
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #202124;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e8f0fe;
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

.form-text {
    font-size: 0.8rem;
    color: #5f6368;
    margin-top: 0.25rem;
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

.member-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 3px solid #1a73e8;
}

.member-name {
    font-weight: 600;
    color: #202124;
    margin: 0;
}

.member-details {
    color: #5f6368;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.date-input-group {
    display: flex;
    gap: 1rem;
    align-items: end;
}

.date-input-group .form-group {
    flex: 1;
}

.amount-input-group {
    display: flex;
    gap: 0.5rem;
    align-items: end;
}

.currency-select {
    width: 100px;
}

.help-text {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    border-left: 3px solid #1a73e8;
}

.help-text-title {
    font-weight: 600;
    color: #1a73e8;
    margin-bottom: 0.5rem;
}

.help-text-content {
    color: #5f6368;
    font-size: 0.9rem;
    margin: 0;
}
</style>

<div class="container py-4">
    <!-- AppBar Abonnements -->
    <div class="appbar subscriptions-appbar">
        <div class="appbar-content">
            <div class="appbar-left">
                <div class="appbar-icon">
                    <i class="bi bi-pencil"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Modifier l'abonnement</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-person appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">{{ $subscription->member->last_name }} {{ $subscription->member->first_name }}</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('subscriptions.show', $subscription) }}" class="appbar-btn-secondary">
                    <i class="bi bi-eye"></i>
                    <span>Voir</span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="appbar-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('subscriptions.update', $subscription) }}">
        @csrf
        @method('PUT')

        <!-- Informations du membre -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-person me-2"></i>
                Membre
            </h3>
            
            <div class="member-info">
                <h5 class="member-name">{{ $subscription->member->last_name }} {{ $subscription->member->first_name }}</h5>
                <p class="member-details">
                    @if($subscription->member->phone)
                        <i class="bi bi-telephone me-2"></i>{{ $subscription->member->phone }}
                    @endif
                    @if($subscription->member->email)
                        <i class="bi bi-envelope ms-3 me-2"></i>{{ $subscription->member->email }}
                    @endif
                </p>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">Changer de membre</label>
                <select class="form-select" name="member_id" id="member_id">
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ $subscription->member_id == $member->id ? 'selected' : '' }}>
                            {{ $member->last_name }} {{ $member->first_name }}
                            @if($member->phone)
                                - {{ $member->phone }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('member_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Informations de l'abonnement -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-credit-card me-2"></i>
                Détails de l'abonnement
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Type d'abonnement *</label>
                        <select class="form-select" name="subscription_type" id="subscription_type" required>
                            @foreach($subscriptionTypes as $key => $label)
                                <option value="{{ $key }}" {{ $subscription->subscription_type == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Montant *</label>
                        <div class="amount-input-group">
                            <input type="number" class="form-control" name="amount" id="amount" 
                                   value="{{ old('amount', $subscription->amount) }}" min="0" step="0.01" required>
                            <select class="form-select currency-select" name="currency">
                                <option value="XOF" {{ $subscription->currency == 'XOF' ? 'selected' : '' }}>XOF</option>
                                <option value="EUR" {{ $subscription->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ $subscription->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        @error('amount')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Date de début *</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" 
                               value="{{ old('start_date', $subscription->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Date de fin *</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" 
                               value="{{ old('end_date', $subscription->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Statut *</label>
                        <select class="form-select" name="status" required>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ $subscription->status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Statut du paiement *</label>
                        <select class="form-select" name="payment_status" required>
                            @foreach($paymentStatuses as $key => $label)
                                <option value="{{ $key }}" {{ $subscription->payment_status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_status')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de paiement -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-cash me-2"></i>
                Informations de paiement
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Méthode de paiement *</label>
                        <select class="form-select" name="payment_method" required>
                            @foreach($paymentMethods as $key => $label)
                                <option value="{{ $key }}" {{ $subscription->payment_method == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Date de paiement</label>
                        <input type="date" class="form-control" name="payment_date" 
                               value="{{ old('payment_date', $subscription->payment_date ? $subscription->payment_date->format('Y-m-d') : '') }}">
                        @error('payment_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Numéro de reçu</label>
                        <input type="text" class="form-control" name="receipt_number" 
                               value="{{ old('receipt_number', $subscription->receipt_number) }}" placeholder="Ex: REC-2024-001">
                        @error('receipt_number')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Référence de paiement</label>
                        <input type="text" class="form-control" name="payment_reference" 
                               value="{{ old('payment_reference', $subscription->payment_reference) }}" placeholder="Ex: REF-2024-001">
                        @error('payment_reference')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-sticky me-2"></i>
                Notes et informations supplémentaires
            </h3>
            
            <div class="form-group mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="4" 
                          placeholder="Notes additionnelles sur cet abonnement...">{{ old('notes', $subscription->notes) }}</textarea>
                @error('notes')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Aide -->
        <div class="help-text">
            <div class="help-text-title">
                <i class="bi bi-info-circle me-2"></i>
                Informations importantes
            </div>
            <p class="help-text-content">
                • Modifiez les dates avec précaution car elles affectent la validité de l'abonnement<br>
                • Le statut "Payé" nécessite une date de paiement<br>
                • Les modifications sont tracées avec votre nom et la date
            </p>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex justify-content-end gap-3 mt-4">
            <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-2"></i>
                Annuler
            </a>
            <button type="submit" class="btn btn">
                <i class="bi bi-check-circle me-2"></i>
                Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subscriptionTypeSelect = document.getElementById('subscription_type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Calcul automatique de la date de fin selon le type d'abonnement
    function calculateEndDate() {
        const startDate = new Date(startDateInput.value);
        const subscriptionType = subscriptionTypeSelect.value;
        
        if (startDate && subscriptionType) {
            let endDate = new Date(startDate);
            
            switch(subscriptionType) {
                case 'monthly':
                    endDate.setMonth(endDate.getMonth() + 1);
                    break;
                case 'quarterly':
                    endDate.setMonth(endDate.getMonth() + 3);
                    break;
                case 'annual':
                    endDate.setFullYear(endDate.getFullYear() + 1);
                    break;
            }
            
            endDateInput.value = endDate.toISOString().split('T')[0];
        }
    }

    subscriptionTypeSelect.addEventListener('change', calculateEndDate);
    startDateInput.addEventListener('change', calculateEndDate);
});
</script>
@endsection
