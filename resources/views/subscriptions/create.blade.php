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

.plan-card {
    border: 2px solid #e8eaed;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.plan-card:hover {
    border-color: #1a73e8;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.15);
}

.plan-card.selected {
    border-color: #1a73e8;
    background: #f8f9ff;
}

.plan-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #202124;
    margin-bottom: 0.5rem;
}

.plan-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a73e8;
    margin-bottom: 0.5rem;
}

.plan-period {
    font-size: 0.9rem;
    color: #5f6368;
    font-weight: 500;
    margin-bottom: 1rem;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.plan-features li {
    padding: 0.25rem 0;
    color: #5f6368;
    font-size: 0.9rem;
}

.plan-features li i {
    color: #34a853;
    margin-right: 0.5rem;
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
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div class="appbar-title-section">
                    <h1 class="appbar-title">Nouvel Abonnement</h1>
                    <div class="appbar-subtitle">
                        <i class="bi bi-building appbar-subtitle-icon"></i>
                        <span class="appbar-subtitle-text">Créer un abonnement pour votre église</span>
                    </div>
                </div>
            </div>
            <div class="appbar-right">
                <a href="{{ route('subscriptions.index') }}" class="appbar-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('subscriptions.store') }}">
        @csrf

        <!-- Sélection du plan -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-star me-2"></i>
                Choisir un plan
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="plan-card" onclick="selectPlan('basic')">
                        <div class="plan-name">Plan Basique</div>
                        <div class="plan-price">39 000 XOF</div>
                        <div class="plan-period">/semestre (6 mois)</div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check"></i>Jusqu'à 50 membres</li>
                            <li><i class="bi bi-check"></i>Gestion des dîmes et offrandes</li>
                            <li><i class="bi bi-check"></i>Rapports de base</li>
                            <li><i class="bi bi-check"></i>Support email</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plan-card" onclick="selectPlan('premium')">
                        <div class="plan-name">Plan Premium</div>
                        <div class="plan-price">39 000 XOF</div>
                        <div class="plan-period">/semestre (6 mois)</div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check"></i>Jusqu'à 200 membres</li>
                            <li><i class="bi bi-check"></i>Toutes les fonctionnalités</li>
                            <li><i class="bi bi-check"></i>Rapports avancés</li>
                            <li><i class="bi bi-check"></i>Support prioritaire</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="plan-card" onclick="selectPlan('enterprise')">
                        <div class="plan-name">Plan Entreprise</div>
                        <div class="plan-price">39 000 XOF</div>
                        <div class="plan-period">/semestre (6 mois)</div>
                        <ul class="plan-features">
                            <li><i class="bi bi-check"></i>Membres illimités</li>
                            <li><i class="bi bi-check"></i>Toutes les fonctionnalités</li>
                            <li><i class="bi bi-check"></i>API d'accès</li>
                            <li><i class="bi bi-check"></i>Support dédié</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="plan_name" id="plan_name" value="basic" required>
        </div>

        <!-- Configuration de l'abonnement -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-gear me-2"></i>
                Configuration
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Montant personnalisé</label>
                        <div class="amount-input-group">
                            <input type="number" class="form-control" name="amount" id="amount" 
                                   value="{{ old('amount', 50000) }}" min="0" step="1000" required>
                            <select class="form-select currency-select" name="currency">
                                <option value="XOF" {{ old('currency', 'XOF') == 'XOF' ? 'selected' : '' }}>XOF</option>
                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        @error('amount')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Limite de membres</label>
                        <input type="number" class="form-control" name="max_members" id="max_members" 
                               value="{{ old('max_members', 50) }}" min="1" required>
                        @error('max_members')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_advanced_reports" id="has_advanced_reports" 
                                   value="1" {{ old('has_advanced_reports') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_advanced_reports">
                                Rapports avancés
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_api_access" id="has_api_access" 
                                   value="1" {{ old('has_api_access') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_api_access">
                                Accès API
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dates -->
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="bi bi-calendar me-2"></i>
                Période d'abonnement
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Date de début *</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" 
                               value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Date de fin *</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" 
                               value="{{ old('end_date', now()->addYear()->format('Y-m-d')) }}" required>
                        @error('end_date')
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
                                <option value="{{ $key }}" {{ old('payment_method') == $key ? 'selected' : '' }}>
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
                        <label class="form-label">Statut du paiement *</label>
                        <select class="form-select" name="payment_status" required>
                            <option value="pending" {{ old('payment_status', 'pending') == 'pending' ? 'selected' : '' }}>
                                En attente
                            </option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>
                                Payé
                            </option>
                        </select>
                        @error('payment_status')
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
                               value="{{ old('receipt_number') }}" placeholder="Ex: REC-2024-001">
                        @error('receipt_number')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Référence de paiement</label>
                        <input type="text" class="form-control" name="payment_reference" 
                               value="{{ old('payment_reference') }}" placeholder="Ex: REF-2024-001">
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
                Notes
            </h3>
            
            <div class="form-group mb-3">
                <label class="form-label">Notes additionnelles</label>
                <textarea class="form-control" name="notes" rows="4" 
                          placeholder="Notes sur cet abonnement...">{{ old('notes') }}</textarea>
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
                • L'abonnement donne accès à toute la plateforme Eglix pour votre église<br>
                • Vous pouvez personnaliser le montant selon vos besoins<br>
                • Le paiement peut être effectué en espèces ou par virement<br>
                • Une fois payé, l'accès est immédiatement activé
            </p>
        </div>

        <!-- Boutons d'action -->
        <div class="d-flex justify-content-end gap-3 mt-4">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle me-2"></i>
                Annuler
            </a>
            <button type="submit" class="btn btn">
                <i class="bi bi-check-circle me-2"></i>
                Créer l'abonnement
            </button>
        </div>
    </form>
</div>

<script>
function selectPlan(plan) {
    // Retirer la sélection de tous les plans
    document.querySelectorAll('.plan-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Sélectionner le plan cliqué
    event.currentTarget.classList.add('selected');
    
    // Mettre à jour les valeurs selon le plan
    const planInput = document.getElementById('plan_name');
    const amountInput = document.getElementById('amount');
    const maxMembersInput = document.getElementById('max_members');
    const advancedReportsCheck = document.getElementById('has_advanced_reports');
    const apiAccessCheck = document.getElementById('has_api_access');
    
    planInput.value = plan;
    
    switch(plan) {
        case 'basic':
            amountInput.value = 50000;
            maxMembersInput.value = 50;
            advancedReportsCheck.checked = false;
            apiAccessCheck.checked = false;
            break;
        case 'premium':
            amountInput.value = 100000;
            maxMembersInput.value = 200;
            advancedReportsCheck.checked = true;
            apiAccessCheck.checked = false;
            break;
        case 'enterprise':
            amountInput.value = 200000;
            maxMembersInput.value = 1000;
            advancedReportsCheck.checked = true;
            apiAccessCheck.checked = true;
            break;
    }
}

// Sélectionner le plan basique par défaut
document.addEventListener('DOMContentLoaded', function() {
    selectPlan('basic');
});
</script>
@endsection