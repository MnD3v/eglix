@extends('layouts.app')

@section('content')
<style>
/* Variables CSS pour la cohérence */
:root {
    --primary-color: #FFCC00;
    --primary-dark: #E6B800;
    --secondary-color: #1f2937;
    --text-muted: #6b7280;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --background-light: #f8fafc;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

/* Container principal */
.subscription-container {
    min-height: 100vh;
    background: white;
    padding: 2rem 1rem;
}

/* Carte principale */
.subscription-card {
    max-width: 900px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

/* En-tête */
.subscription-header {
    background: var(--primary-color);
    padding: 3rem 2rem;
    text-align: center;
}

.subscription-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
}

.subscription-subtitle {
    font-size: 1.125rem;
    color: var(--text-muted);
    font-weight: 500;
    margin: 0;
}

/* Informations de l'église */
.church-info-card {
    background: var(--background-light);
    border-radius: 16px;
    padding: 2rem;
    margin: 2rem;
    border-left: 4px solid var(--primary-color);
    position: relative;
}


.church-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.church-details {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    color: var(--text-muted);
    font-size: 0.95rem;
}

.church-detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
}

/* Grille des plans */
.plans-section {
    padding: 2rem;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
}

.section-title p {
    color: var(--text-muted);
    font-size: 1.125rem;
}

.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.plan-card {
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.plan-card:hover {
    border-color: var(--primary-color);
    box-shadow: var(--shadow-xl);
}

.plan-card.selected {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #fff5f5 0%, #fef7f7 100%);
    box-shadow: var(--shadow-lg);
}

.plan-badge {
    position: absolute;
    top: -10px;
    right: 20px;
    background: var(--primary-color);
    color: var(--secondary-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}


.plan-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
}

.plan-price {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.plan-period {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.plan-duration {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    padding: 0.5rem 1rem;
    background: var(--background-light);
    border-radius: 8px;
    display: inline-block;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: left;
}

.plan-features li {
    padding: 0.5rem 0;
    color: var(--text-muted);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.plan-features li::before {
    content: '✓';
    color: var(--success-color);
    font-weight: bold;
    font-size: 1.1rem;
}

/* Formulaire */
.form-section {
    background: var(--background-light);
    padding: 2rem;
    margin: 2rem;
    border-radius: 16px;
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1.5rem;
    text-align: center;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.1);
}

.form-control::placeholder {
    color: var(--text-muted);
}

/* Bouton de soumission */
.submit-section {
    text-align: center;
    padding: 2rem;
    background: white;
    margin: 2rem;
    border-radius: 16px;
}

.submit-btn {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: var(--secondary-color);
    border: none;
    padding: 1rem 3rem;
    border-radius: 50px;
    font-size: 1.125rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: var(--shadow-md);
}

.submit-btn:hover {
    box-shadow: var(--shadow-lg);
}

/* Messages d'alerte */
.alert {
    margin: 2rem;
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

/* Responsive */
@media (max-width: 768px) {
    .subscription-container {
        padding: 1rem;
    }
    
    .subscription-title {
        font-size: 2rem;
    }
    
    .plans-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .church-details {
        flex-direction: column;
        gap: 1rem;
    }
    
    .church-info-card,
    .form-section,
    .submit-section,
    .alert {
        margin: 1rem;
    }
}

</style>

<div class="subscription-container">
    <div class="subscription-card">
        <!-- En-tête -->
        <div class="subscription-header">
            <div class="header-content">
                <h1 class="subscription-title">Abonnement Eglix</h1>
                <p class="subscription-subtitle" style="color: black;">Choisissez le plan parfait pour votre église</p>
            </div>
        </div>

        <!-- Messages de session -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif


        <!-- Plans d'abonnement -->
        <div class="plans-section">
            <div class="section-title" style=" display: flex; justify-content: center; align-items: center; border-radius: 16px;">
                <h2 style="text-align: center;">Plan d'abonnement disponible</h2>
            </div>

            <div class="plans-grid" style="grid-template-columns: 1fr; max-width: 400px; margin: 0 auto;">
                <div class="plan-card" data-plan="basic">
                    <div class="plan-badge">Disponible</div>
                    <div class="plan-name">Plan Basique</div>
                    <div class="plan-price">6,500 XOF</div>
                    <div class="plan-period">par mois</div>
                    <div class="plan-duration">6 mois • 39,000 XOF</div>
                    <ul class="plan-features">
                        <li>Gestion complète des membres</li>
                        <li>Rapports financiers détaillés</li>
                        <li>Gestion des dîmes et offrandes</li>
                        <li>Support technique inclus</li>
                        <li>Mises à jour automatiques</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Formulaire de contact -->
        <form id="subscription-form">
            <input type="hidden" name="subscription_plan" id="selected-plan" value="basic" required>
            
            <div class="form-section">
                <h3 class="form-title">
                    Informations de contact
                </h3>
                
                <div class="form-grid">
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
                               placeholder="Ex: +228 98 78 45 89"
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
                               placeholder="Ex: contact@eglise.com"
                               required>
                    </div>
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
                              placeholder="Décrivez vos besoins spécifiques ou posez vos questions..."></textarea>
                </div>
            </div>
            
            <div class="submit-section">
                <button type="submit" class="submit-btn">
                    Demander l'abonnement via WhatsApp
                </button>
                <p style="margin-top: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                    Nous vous contacterons dans les plus brefs délais pour finaliser votre abonnement
                </p>
            </div>
        </form>
    </div>
</div>

<script>
// Gestion de la sélection des plans
document.querySelectorAll('.plan-card').forEach(card => {
    card.addEventListener('click', function() {
        // Retirer la sélection de toutes les cartes
        document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
        
        // Ajouter la sélection à la carte cliquée
        this.classList.add('selected');
        
        // Mettre à jour le plan sélectionné
        const plan = this.dataset.plan;
        document.getElementById('selected-plan').value = plan;
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
        'basic': { name: 'Plan Basique', price: '6,500 XOF/mois', total: '39,000 XOF' }
    };
    
    const selectedPlanInfo = planInfo[selectedPlan];
    
    // Construire le message WhatsApp
    let whatsappMessage = `*Demande d'abonnement Eglix*\n\n`;
    whatsappMessage += `*Informations de la demande :*\n`;
    whatsappMessage += `• Église : {{ $church->name }}\n`;
    whatsappMessage += `• Plan sélectionné : ${selectedPlanInfo.name}\n`;
    whatsappMessage += `• Prix : ${selectedPlanInfo.price}\n`;
    whatsappMessage += `• Total 6 mois : ${selectedPlanInfo.total}\n`;
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
    
    // Ouvrir WhatsApp
    window.open(whatsappUrl, '_blank');
    
    // Feedback du bouton
    const submitBtn = document.querySelector('.submit-btn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = 'Redirection vers WhatsApp...';
    submitBtn.disabled = true;
    
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);
});
</script>
@endsection