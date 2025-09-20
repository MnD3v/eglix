@extends('layouts.app')

@section('content')
<style>
.renewal-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
}

.renewal-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.expired-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
    color: white;
}

.renewal-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.renewal-subtitle {
    font-size: 1.1rem;
    color: #718096;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.subscription-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: left;
}

.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.info-label {
    font-weight: 500;
    color: #4a5568;
}

.info-value {
    color: #2d3748;
}

.form-group {
    margin-bottom: 1.5rem;
    text-align: left;
}

.form-label {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control, .form-select {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-weight: 600;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    width: 100%;
    cursor: pointer;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn:active {
    transform: translateY(0);
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .renewal-container {
        padding: 1rem;
    }
    
    .renewal-card {
        padding: 2rem;
    }
    
    .renewal-title {
        font-size: 1.5rem;
    }
}
</style>

<div class="renewal-container">
    <div class="renewal-card">
        <div class="expired-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        
        <h1 class="renewal-title">Abonnement Expiré</h1>
        <p class="renewal-subtitle">
            L'abonnement de votre église <strong>{{ $church->name }}</strong> a expiré. 
            Veuillez renouveler votre abonnement pour continuer à utiliser la plateforme.
        </p>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="subscription-info">
            <div class="info-row">
                <span class="info-label">Église :</span>
                <span class="info-value">{{ $church->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dernière expiration :</span>
                <span class="info-value">{{ $church->subscription_end_date ? $church->subscription_end_date->format('d/m/Y') : 'Non défini' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut :</span>
                <span class="info-value">{!! $church->getSubscriptionStatusBadge() !!}</span>
            </div>
        </div>

        <form id="renewal-form">
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-credit-card me-2"></i>
                    Plan d'abonnement
                </label>
                <select class="form-select" id="subscription_plan" required>
                    <option value="basic" {{ old('subscription_plan', $church->subscription_plan) === 'basic' ? 'selected' : '' }}>Basique - 50,000 XOF</option>
                    <option value="premium" {{ old('subscription_plan', $church->subscription_plan) === 'premium' ? 'selected' : '' }}>Premium - 100,000 XOF</option>
                    <option value="enterprise" {{ old('subscription_plan', $church->subscription_plan) === 'enterprise' ? 'selected' : '' }}>Entreprise - 200,000 XOF</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-currency-exchange me-2"></i>
                    Montant payé (XOF)
                </label>
                <input type="number" 
                       class="form-control" 
                       id="subscription_amount"
                       value="{{ old('subscription_amount', $church->subscription_amount) }}"
                       placeholder="Montant effectivement payé"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-receipt me-2"></i>
                    Référence de paiement
                </label>
                <input type="text" 
                       class="form-control" 
                       id="payment_reference"
                       value="{{ old('payment_reference', $church->payment_reference) }}"
                       placeholder="Numéro de reçu ou référence">
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-chat-text me-2"></i>
                    Notes (optionnel)
                </label>
                <textarea class="form-control" 
                          id="subscription_notes"
                          rows="3"
                          placeholder="Notes sur le paiement">{{ old('subscription_notes', $church->subscription_notes) }}</textarea>
            </div>
            
            <button type="submit" class="btn" id="renewal-btn">
                <i class="bi bi-whatsapp me-2"></i>
                Renouveler via WhatsApp
            </button>
        </form>
        
        <div class="mt-4">
            <p class="text-muted small">
                <i class="bi bi-info-circle me-2"></i>
                Le renouvellement sera effectif immédiatement après validation du paiement.
            </p>
        </div>
    </div>
</div>

<script>
// Mise à jour automatique du montant selon le plan
document.querySelector('select[id="subscription_plan"]').addEventListener('change', function() {
    const amountInput = document.querySelector('input[id="subscription_amount"]');
    const planPrices = {
        'basic': 50000,
        'premium': 100000,
        'enterprise': 200000
    };
    
    amountInput.value = planPrices[this.value] || '';
});

// Gestion de la soumission du formulaire vers WhatsApp
document.getElementById('renewal-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Récupérer les données du formulaire
    const subscriptionPlan = document.getElementById('subscription_plan').value;
    const subscriptionAmount = document.getElementById('subscription_amount').value;
    const paymentReference = document.getElementById('payment_reference').value;
    const subscriptionNotes = document.getElementById('subscription_notes').value;
    
    // Validation des champs requis
    if (!subscriptionAmount.trim()) {
        alert('Veuillez saisir le montant payé.');
        return;
    }
    
    // Définir les informations du plan
    const planInfo = {
        'basic': { name: 'Plan Basique', price: '50,000 XOF' },
        'premium': { name: 'Plan Premium', price: '100,000 XOF' },
        'enterprise': { name: 'Plan Entreprise', price: '200,000 XOF' }
    };
    
    const selectedPlanInfo = planInfo[subscriptionPlan];
    
    // Construire le message WhatsApp
    let whatsappMessage = `Bonjour ! Je souhaite renouveler mon abonnement Eglix.\n\n`;
    whatsappMessage += `📋 *Informations du renouvellement :*\n`;
    whatsappMessage += `• Église : {{ $church->name }}\n`;
    whatsappMessage += `• Plan sélectionné : ${selectedPlanInfo.name} (${selectedPlanInfo.price})\n`;
    whatsappMessage += `• Montant payé : ${subscriptionAmount} XOF\n`;
    
    if (paymentReference.trim()) {
        whatsappMessage += `• Référence de paiement : ${paymentReference}\n`;
    }
    
    if (subscriptionNotes.trim()) {
        whatsappMessage += `• Notes : ${subscriptionNotes}\n`;
    }
    
    whatsappMessage += `\nMerci de valider le renouvellement de mon abonnement.`;
    
    // Encoder le message pour l'URL
    const encodedMessage = encodeURIComponent(whatsappMessage);
    
    // Numéro WhatsApp (22898784589)
    const whatsappNumber = '22898784589';
    
    // Construire l'URL WhatsApp
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
    
    // Ouvrir WhatsApp dans un nouvel onglet
    window.open(whatsappUrl, '_blank');
    
    // Afficher un message de confirmation
    const renewalBtn = document.getElementById('renewal-btn');
    const originalText = renewalBtn.innerHTML;
    renewalBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Redirection vers WhatsApp...';
    renewalBtn.disabled = true;
    
    // Remettre le bouton normal après 2 secondes
    setTimeout(() => {
        renewalBtn.innerHTML = originalText;
        renewalBtn.disabled = false;
    }, 2000);
});
</script>
@endsection
