@extends('layouts.app')

@section('content')
<style>
.subscription-form-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 2rem;
}

.subscription-form-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.form-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control, .form-select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn {
    background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 38, 0, 0.3);
}

.btn-secondary {
    background: #6b7280;
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-secondary:hover {
    background: #4b5563;
    color: white;
    text-decoration: none;
}

.church-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #3b82f6;
}

.church-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.church-meta {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

.amount-display {
    background: #f8fafc;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.amount-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ff2600;
    display: block;
    margin-bottom: 0.25rem;
}

.amount-note {
    color: #6b7280;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .subscription-form-container {
        padding: 1rem;
    }
    
    .subscription-form-card {
        padding: 1.5rem;
    }
}
</style>

<div class="subscription-form-container">
    <div class="subscription-form-card">
        <div class="form-header">
            <h1 class="form-title">Créer un Abonnement</h1>
            <p class="form-subtitle">Attribuer un abonnement à une église</p>
        </div>

        <div class="church-info">
            <h3 class="church-name">{{ $church->name }}</h3>
            <p class="church-meta">
                @if($church->address)
                    <i class="bi bi-geo-alt me-2"></i>{{ $church->address }}
                @endif
                @if($church->phone)
                    <i class="bi bi-telephone ms-3 me-2"></i>{{ $church->phone }}
                @endif
            </p>
        </div>

        <form method="POST" action="{{ route('admin.store-subscription', $church) }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-credit-card me-2"></i>
                    Plan d'abonnement
                </label>
                <select class="form-select" name="subscription_plan" required>
                    <option value="basic" {{ old('subscription_plan') === 'basic' ? 'selected' : '' }}>Basique - 50,000 XOF</option>
                    <option value="premium" {{ old('subscription_plan') === 'premium' ? 'selected' : '' }}>Premium - 100,000 XOF</option>
                    <option value="enterprise" {{ old('subscription_plan') === 'enterprise' ? 'selected' : '' }}>Entreprise - 200,000 XOF</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-currency-exchange me-2"></i>
                    Montant (XOF)
                </label>
                <div class="amount-display">
                    <span class="amount-value" id="amount-display">50,000 XOF</span>
                    <small class="amount-note">Montant automatique selon le plan</small>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-calendar-event me-2"></i>
                    Date de début
                </label>
                <input type="date" 
                       class="form-control" 
                       name="subscription_start_date" 
                       value="{{ old('subscription_start_date', now()->format('Y-m-d')) }}"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-calendar-check me-2"></i>
                    Date d'expiration
                </label>
                <input type="date" 
                       class="form-control" 
                       name="subscription_end_date" 
                       value="{{ old('subscription_end_date', now()->addYear()->format('Y-m-d')) }}"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-receipt me-2"></i>
                    Référence de paiement
                </label>
                <input type="text" 
                       class="form-control" 
                       name="payment_reference" 
                       value="{{ old('payment_reference') }}"
                       placeholder="Numéro de reçu ou référence">
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-chat-text me-2"></i>
                    Notes
                </label>
                <textarea class="form-control" 
                          name="subscription_notes" 
                          rows="3"
                          placeholder="Notes sur l'abonnement">{{ old('subscription_notes') }}</textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn">
                    <i class="bi bi-check-circle me-2"></i>
                    Créer l'abonnement
                </button>
                <a href="{{ route('admin.index') }}" class="btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Mise à jour automatique du montant selon le plan
document.querySelector('select[name="subscription_plan"]').addEventListener('change', function() {
    const amountDisplay = document.querySelector('#amount-display');
    const planPrices = {
        'basic': '50,000 XOF',
        'premium': '100,000 XOF',
        'enterprise': '200,000 XOF'
    };
    
    amountDisplay.textContent = planPrices[this.value] || '50,000 XOF';
});

// Mise à jour automatique de la date d'expiration selon la date de début
document.querySelector('input[name="subscription_start_date"]').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const endDate = new Date(startDate);
    endDate.setFullYear(endDate.getFullYear() + 1);
    
    document.querySelector('input[name="subscription_end_date"]').value = endDate.toISOString().split('T')[0];
});
</script>
@endsection