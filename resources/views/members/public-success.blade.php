<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription réussie - {{ $church->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            background-image: 
                linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
            min-height: 100vh;
            font-family: 'DM Sans', system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        
        .success-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        
        .success-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            margin: 0 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        
        .success-header {
            background: #ffffff;
            color: #000000;
            padding: 3rem 2rem;
            position: relative;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .success-header::before {
            display: none;
        }
        
        .eglix-logo {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
        }
        
        .eglix-logo-img {
            height: 40px;
            width: auto;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .eglix-logo-img:hover {
            opacity: 1;
            transform: scale(1.05);
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            animation: bounce 2s infinite;
            position: relative;
            z-index: 1;
            border: 3px solid #e2e8f0;
            color: #000000;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
            color: #000000;
        }
        
        .success-subtitle {
            font-size: 1.1rem;
            opacity: 0.8;
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
            color: #000000;
        }
        
        .success-content {
            padding: 2rem;
            background: #ffffff;
        }
        
        .church-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }
        
        .church-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: #000000;
            font-size: 1.5rem;
        }
        
        .church-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #000000;
            margin-bottom: 0.5rem;
        }
        
        .church-description {
            color: #000000;
            font-size: 0.95rem;
            opacity: 0.8;
        }
        
        .next-steps {
            text-align: left;
            margin-bottom: 2rem;
        }
        
        .next-steps h4 {
            color: #000000;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .step-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
            padding: 0.5rem 0;
        }
        
        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #FFCC00;
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(255, 204, 0, 0.3);
        }
        
        .step-text {
            color: #000000;
            font-size: 0.95rem;
            opacity: 0.8;
        }
        
        .btn-primary {
            background: #FFCC00;
            color: #000000;
            border: 1px solid #FFCC00;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: lowercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3);
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-primary:hover {
            background: #e6b800;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 204, 0, 0.4);
        }
        
        .btn-outline-primary {
            border: 1px solid #e2e8f0;
            color: #000000;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: lowercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: #ffffff;
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-outline-primary:hover {
            background: #f8f9fa;
            border-color: #e2e8f0;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            color: #000000;
        }
        
        .text-muted {
            color: #000000 !important;
            opacity: 0.6;
        }
        
        .text-danger {
            color: #000000 !important;
        }
        
        @media (max-width: 768px) {
            .success-card {
                margin: 0.5rem;
            }
            
            .success-header, .success-content {
                padding: 1.5rem;
            }
            
            .success-title {
                font-size: 1.6rem;
            }
            
            .success-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <!-- En-tête de succès -->
            <div class="success-header">
                <!-- Logo Eglix -->
                <div class="eglix-logo">
                    <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix" class="eglix-logo-img">
                </div>
                
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">inscription réussie !</h1>
                <p class="success-subtitle">bienvenue dans notre communauté</p>
            </div>
            
            <!-- Contenu principal -->
            <div class="success-content">
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Informations sur l'église -->
                <div class="church-info">
                    <div class="church-logo">
                        <i class="fas fa-church"></i>
                    </div>
                    <h3 class="church-name">{{ $church->name }}</h3>
                    @if($church->description)
                        <p class="church-description">{{ $church->description }}</p>
                    @endif
                    @if($church->address)
                        <p class="church-description mt-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ $church->address }}
                        </p>
                    @endif
                    @if($church->phone)
                        <p class="church-description">
                            <i class="fas fa-phone me-1"></i>
                            {{ $church->phone }}
                        </p>
                    @endif
                </div>
                
                <!-- Prochaines étapes -->
                <div class="next-steps">
                    <h4><i class="fas fa-list-check me-2"></i>prochaines étapes</h4>
                    
                    <div class="step-item">
                        <div class="step-icon">1</div>
                        <div class="step-text">votre inscription a été enregistrée avec succès</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">2</div>
                        <div class="step-text">l'équipe de l'église vous contactera bientôt</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">3</div>
                        <div class="step-text">vous recevrez les informations sur les activités</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">4</div>
                        <div class="step-text">participez aux cultes et événements de l'église</div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('members.public.create', $church->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        inscrire un autre membre
                    </a>
                    @if($church->website)
                        <a href="{{ $church->website }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>
                            visiter le site web
                        </a>
                    @endif
                </div>
                
                <!-- Message de remerciement -->
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">
                        <i class="fas fa-heart text-danger me-1"></i>
                        merci de faire partie de notre famille spirituelle !
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.success-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>