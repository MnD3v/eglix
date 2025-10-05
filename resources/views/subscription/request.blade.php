@extends('layouts.app')

@section('content')
<style>
.subscription-request-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
}

.subscription-request-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.request-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.request-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.request-subtitle {
    color: #6b7280;
    font-size: 1rem;
}

.church-info {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #FFCC00;
}

.church-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.church-details {
    color: #6b7280;
    font-size: 0.875rem;
    margin: 0;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.plan-card {
    background: #f8fafc;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.plan-card:hover {
    border-color: #FFCC00;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 38, 0, 0.1);
}

.plan-card.selected {
    border-color: #FFCC00;
    background: #fff5f5;
}

.plan-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.plan-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #FFCC00;
    margin-bottom: 0.25rem;
}

.plan-period {
    font-size: 0.9rem;
    color: #6b7280;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.plan-features {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
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
    border-color: #FFCC00;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 38, 0, 0.1);
}

.btn {
    background: linear-gradient(135deg, #FFCC00 0%, #cc1f00 100%);
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

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background: #d1fae5;
    border: 1px solid #a7f3d0;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

@media (max-width: 768px) {
    .subscription-request-container {
        padding: 1rem;
    }
    
    .subscription-request-card {
        padding: 1.5rem;
    }
    
    .plans-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="subscription-request-container">
    <div class="subscription-request-card">
        <div class="request-header">
            <h1 class="request-title">Demande d'Abonnement</h1>
            <p class="request-subtitle">Sélectionnez un plan d'abonnement pour votre église</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="church-info">
            <h3 class="church-name">{{ $church->name }}</h3>
            <p class="church-details">
                @if($church->address)
                    <i class="bi bi-geo-alt me-2"></i>{{ $church->address }}
                @endif
                @if($church->phone)
                    <i class="bi bi-telephone ms-3 me-2"></i>{{ $church->phone }}
                @endif
                @if($church->email)
                    <i class="bi bi-envelope ms-3 me-2"></i>{{ $church->email }}
                @endif
            </p>
        </div>

        <form id="subscription-form">
            <div class="plans-grid">
                <div class="plan-card" data-plan="basic">
                    <div class="plan-name">Plan Basique</div>
                    <div class="plan-price">39,000 XOF</div>
                    <div class="plan-period">/semestre (6 mois)</div>
                    <p class="plan-features">Accès complet à la plateforme<br>Gestion des membres<br>Rapports financiers</p>
                </div>
                
                <div class="plan-card" data-plan="premium">
                    <div class="plan-name">Plan Premium</div>
                    <div class="plan-price">39,000 XOF</div>
                    <div class="plan-period">/semestre (6 mois)</div>
                    <p class="plan-features">Tout du plan Basique<br>Rapports avancés<br>Support prioritaire</p>
                </div>
                
                <div class="plan-card" data-plan="enterprise">
                    <div class="plan-name">Plan Entreprise</div>
                    <div class="plan-price">39,000 XOF</div>
                    <div class="plan-period">/semestre (6 mois)</div>
                    <p class="plan-features">Tout du plan Premium<br>API personnalisée<br>Formation dédiée</p>
                </div>
            </div>
            
            <input type="hidden" name="subscription_plan" id="selected-plan" value="basic" required>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-telephone me-2"></i>
                    Téléphone de contact
                </label>
                <input type="text" 
                       class="form-control" 
                       name="contact_phone" 
                       id="contact_phone"
                       value="{{ old('contact_phone', $church->phone) }}"
                       placeholder="Numéro de téléphone"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-envelope me-2"></i>
                    Email de contact
                </label>
                <input type="email" 
                       class="form-control" 
                       name="contact_email" 
                       id="contact_email"
                       value="{{ old('contact_email', $church->email) }}"
                       placeholder="Adresse email"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-chat-text me-2"></i>
                    Message (optionnel)
                </label>
                <textarea class="form-control" 
                          name="message" 
                          id="message"
                          rows="4"
                          placeholder="Ajoutez des informations supplémentaires...">{{ old('message') }}</textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn" id="submit-btn">
                    <i class="bi bi-whatsapp me-2"></i>
                    Envoyer via WhatsApp
                </button>
                <a href="{{ route('logout') }}" class="btn-secondary" onclick="addLogoutLoader(this)">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Se déconnecter
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Sélection du plan
document.querySelectorAll('.plan-card').forEach(card => {
    card.addEventListener('click', function() {
        // Retirer la sélection de toutes les cartes
        document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
        
        // Ajouter la sélection à la carte cliquée
        this.classList.add('selected');
        
        // Mettre à jour le champ caché
        document.getElementById('selected-plan').value = this.dataset.plan;
    });
});

// Sélectionner le plan basique par défaut
document.querySelector('.plan-card[data-plan="basic"]').classList.add('selected');

// Gestion de la soumission du formulaire vers WhatsApp
document.getElementById('subscription-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Récupérer les données du formulaire
    const selectedPlan = document.getElementById('selected-plan').value;
    const contactPhone = document.getElementById('contact_phone').value;
    const contactEmail = document.getElementById('contact_email').value;
    const message = document.getElementById('message').value;
    
    // Validation des champs requis
    if (!contactPhone.trim()) {
        alert('Veuillez saisir votre numéro de téléphone.');
        return;
    }
    
    if (!contactEmail.trim()) {
        alert('Veuillez saisir votre adresse email.');
        return;
    }
    
    // Définir les informations du plan
    const planInfo = {
        'basic': { name: 'Plan Basique', price: '50,000 XOF' },
        'premium': { name: 'Plan Premium', price: '100,000 XOF' },
        'enterprise': { name: 'Plan Entreprise', price: '200,000 XOF' }
    };
    
    const selectedPlanInfo = planInfo[selectedPlan];
    
    // Construire le message WhatsApp
    let whatsappMessage = `Bonjour ! Je souhaite souscrire à un abonnement Eglix.\n\n`;
    whatsappMessage += `📋 *Informations de la demande :*\n`;
    whatsappMessage += `• Église : {{ $church->name }}\n`;
    whatsappMessage += `• Plan sélectionné : ${selectedPlanInfo.name} (${selectedPlanInfo.price})\n`;
    whatsappMessage += `• Téléphone : ${contactPhone}\n`;
    whatsappMessage += `• Email : ${contactEmail}\n`;
    
    if (message.trim()) {
        whatsappMessage += `• Message : ${message}\n`;
    }
    
    whatsappMessage += `\nMerci de me contacter pour finaliser l'abonnement.`;
    
    // Encoder le message pour l'URL
    const encodedMessage = encodeURIComponent(whatsappMessage);
    
    // Numéro WhatsApp (22898784589)
    const whatsappNumber = '22898784589';
    
    // Construire l'URL WhatsApp
    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
    
    // Ouvrir WhatsApp dans un nouvel onglet
    window.open(whatsappUrl, '_blank');
    
    // Afficher un message de confirmation
    const submitBtn = document.getElementById('submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Redirection vers WhatsApp...';
    submitBtn.disabled = true;
    
    // Remettre le bouton normal après 2 secondes
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);
});
</script>
@endsection
