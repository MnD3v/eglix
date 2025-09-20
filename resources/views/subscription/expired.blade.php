@extends('layouts.app')

@section('content')
<style>
.expired-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
}

.expired-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.expired-icon {
    width: 80px;
    height: 80px;
    background: #ff6b6b;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
    color: white;
}

.expired-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.expired-subtitle {
    font-size: 1.1rem;
    color: #718096;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.expired-info {
    background: #f7fafc;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #ff6b6b;
}

.expired-info-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.expired-info-text {
    color: #718096;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    background: #667eea;
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-weight: 600;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn:hover {
    background: #5a67d8;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #e2e8f0;
    border: none;
    border-radius: 12px;
    padding: 1rem 2rem;
    font-weight: 600;
    color: #4a5568;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background: #cbd5e0;
    color: #2d3748;
    text-decoration: none;
    transform: translateY(-2px);
}

.contact-info {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.contact-text {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.contact-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.contact-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .expired-container {
        padding: 1rem;
    }
    
    .expired-card {
        padding: 2rem;
    }
    
    .expired-title {
        font-size: 1.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="expired-container">
    <div class="expired-card">
        <div class="expired-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
        
        <h1 class="expired-title">Abonnement Expiré</h1>
        <p class="expired-subtitle">
            Votre abonnement à la plateforme Eglix a expiré. 
            Pour continuer à utiliser tous les services, veuillez renouveler votre abonnement.
        </p>
        
        <div class="expired-info">
            <div class="expired-info-title">
                <i class="bi bi-info-circle me-2"></i>
                Que se passe-t-il maintenant ?
            </div>
            <div class="expired-info-text">
                • Votre accès à la plateforme est temporairement suspendu<br>
                • Toutes vos données sont sauvegardées en sécurité<br>
                • Le renouvellement vous donnera accès immédiat à tous les services
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('subscriptions.create') }}" class="btn">
                <i class="bi bi-credit-card"></i>
                Renouveler l'abonnement
            </a>
            <a href="{{ route('logout') }}" class="btn-secondary" onclick="addLogoutLoader(this)">
                <i class="bi bi-box-arrow-right"></i>
                Se déconnecter
            </a>
        </div>
        
        <div class="contact-info">
            <p class="contact-text">Besoin d'aide ?</p>
            <a href="mailto:support@eglix.com" class="contact-link">
                <i class="bi bi-envelope me-2"></i>
                support@eglix.com
            </a>
        </div>
    </div>
</div>
@endsection
