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
            background: linear-gradient(135deg, #ff2600 0%, #000000 100%);
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
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            margin: 0 1rem;
            text-align: center;
            border: 2px solid #ff2600;
        }
        
        .success-header {
            background: linear-gradient(135deg, #ff2600 0%, #000000 100%);
            color: white;
            padding: 3rem 2rem;
            position: relative;
        }
        
        .success-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
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
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            animation: bounce 2s infinite;
            position: relative;
            z-index: 1;
            border: 3px solid rgba(255,255,255,0.3);
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
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
        }
        
        .success-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
            font-family: 'DM Sans', sans-serif;
        }
        
        .success-content {
            padding: 2rem;
        }
        
        .church-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .church-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .church-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .church-description {
            color: #666;
            font-size: 0.95rem;
        }
        
        .next-steps {
            text-align: left;
            margin-bottom: 2rem;
        }
        
        .next-steps h4 {
            color: #333;
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
            background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(255, 38, 0, 0.3);
        }
        
        .step-text {
            color: #555;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff2600 0%, #cc1f00 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 38, 0, 0.3);
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #cc1f00 0%, #ff2600 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.4);
        }
        
        .btn-outline-primary {
            border: 2px solid #ff2600;
            color: #ff2600;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: transparent;
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-outline-primary:hover {
            background: #ff2600;
            border-color: #ff2600;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.4);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: none;
            border-radius: 15px;
            color: #155724;
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
                    <img src="{{ asset('images/eglix.png') }}" alt="Eglix" class="eglix-logo-img">
                </div>
                
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h1 class="success-title">Inscription réussie !</h1>
                <p class="success-subtitle">Bienvenue dans notre communauté</p>
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
                    <h4><i class="fas fa-list-check me-2"></i>Prochaines étapes</h4>
                    
                    <div class="step-item">
                        <div class="step-icon">1</div>
                        <div class="step-text">Votre inscription a été enregistrée avec succès</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">2</div>
                        <div class="step-text">L'équipe de l'église vous contactera bientôt</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">3</div>
                        <div class="step-text">Vous recevrez les informations sur les activités</div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-icon">4</div>
                        <div class="step-text">Participez aux cultes et événements de l'église</div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('members.public.create', $church->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Inscrire un autre membre
                    </a>
                    @if($church->website)
                        <a href="{{ $church->website }}" target="_blank" class="btn btn-primary">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Visiter le site web
                        </a>
                    @endif
                </div>
                
                <!-- Message de remerciement -->
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted mb-0">
                        <i class="fas fa-heart text-danger me-1"></i>
                        Merci de faire partie de notre famille spirituelle !
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
