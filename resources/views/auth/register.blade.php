<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Eglix</title>
    @include('partials.meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            animation: fadeInBody 0.8s ease-out forwards;
        }

        @keyframes fadeInBody {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 700px;
            opacity: 0;
            transform: scale(0.95) translateY(30px);
            animation: slideInContainer 0.6s ease-out 0.2s forwards;
        }

        @keyframes slideInContainer {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(30px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .left-panel {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
            opacity: 0;
            transform: translateX(-30px);
            animation: slideInLeft 0.8s ease-out 0.4s forwards;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out 0.6s forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #1f2937;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out 0.8s forwards;
        }

        .welcome-subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 40px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out 1s forwards;
        }

        .form-group {
            margin-bottom: 24px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .form-group:nth-child(1) { animation-delay: 1.2s; }
        .form-group:nth-child(2) { animation-delay: 1.4s; }
        .form-group:nth-child(3) { animation-delay: 1.6s; }
        .form-group:nth-child(4) { animation-delay: 1.8s; }
        .form-group:nth-child(5) { animation-delay: 2s; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            transform: translateZ(0);
        }

        .form-input:focus {
            outline: none;
            border-color: #FF2600;
            box-shadow: 0 0 0 4px rgba(255, 38, 0, 0.12);
            transform: translateY(-2px) translateZ(0);
        }

        .form-input:hover {
            border-color: #d1d5db;
            transform: translateY(-1px) translateZ(0);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            resize: vertical;
            min-height: 100px;
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            transform: translateZ(0);
        }

        .form-textarea:focus {
            outline: none;
            border-color: #FF2600;
            box-shadow: 0 0 0 4px rgba(255, 38, 0, 0.12);
            transform: translateY(-2px) translateZ(0);
        }

        .form-textarea:hover {
            border-color: #d1d5db;
            transform: translateY(-1px) translateZ(0);
        }

        .form-textarea::placeholder {
            color: #9ca3af;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            font-weight: 500;
        }

        .strength-weak { color: #dc2626; }
        .strength-medium { color: #f59e0b; }
        .strength-strong { color: #059669; }

        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #FF2600 0%, #ff4d33 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out 2.2s forwards;
        }

        .btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 35px rgba(255, 38, 0, 0.4);
        }

        .btn:active {
            transform: translateY(-1px) scale(0.98);
            transition: all 0.1s ease;
        }

        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .btn-loader {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .spinner {
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        }

        .login-link {
            text-align: center;
            margin-top: 32px;
            color: #6b7280;
            font-size: 14px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease-out 2.4s forwards;
        }

        .login-link a {
            color: #FF2600;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .right-panel {
            display: flex;
            overflow: hidden;
            opacity: 0;
            transform: translateX(30px);
            animation: slideInRight 0.8s ease-out 0.6s forwards;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .image-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .login-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transform: scale(1.1);
            animation: imageZoomIn 1.2s ease-out 0.8s forwards;
        }

        @keyframes imageZoomIn {
            from {
                opacity: 0;
                transform: scale(1.1);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Styles supprimés car remplacés par l'image */

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
            animation: alertSlideIn 0.5s ease-out forwards;
        }

        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
                margin: 10px;
                animation: slideInContainerMobile 0.6s ease-out 0.2s forwards;
            }
            
            @keyframes slideInContainerMobile {
                from {
                    opacity: 0;
                    transform: scale(0.9) translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }
            
            .right-panel {
                display: none;
            }
            
            .left-panel {
                padding: 40px 30px;
                animation: slideInLeftMobile 0.8s ease-out 0.4s forwards;
            }
            
            @keyframes slideInLeftMobile {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .welcome-title {
                font-size: 28px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            /* Réduire les délais d'animation sur mobile */
            .form-group:nth-child(1) { animation-delay: 0.8s; }
            .form-group:nth-child(2) { animation-delay: 1s; }
            .form-group:nth-child(3) { animation-delay: 1.2s; }
            .form-group:nth-child(4) { animation-delay: 1.4s; }
            .form-group:nth-child(5) { animation-delay: 1.6s; }
            
            .btn { animation-delay: 1.8s; }
            .login-link { animation-delay: 2s; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="logo-section">
                <img src="/images/eglix-black.png" alt="Eglix" style="height:48px;">
            </div>

            <h1 class="welcome-title">Créer votre église</h1>
            <p class="welcome-subtitle">Créez votre église et votre compte administrateur</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" autocomplete="on">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" 
                           class="form-input @error('name') is-invalid @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Votre nom complet" 
                           required 
                           autocomplete="name"
                           autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse email</label>
                    <input type="email" 
                           class="form-input @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="exemple@domaine.com" 
                            required
                            autocomplete="email">
                </div>

                <div class="form-group">
                    <label class="form-label">Nom de l'église</label>
                    <input type="text" 
                           class="form-input @error('church_name') is-invalid @enderror" 
                           name="church_name" 
                           value="{{ old('church_name') }}" 
                           placeholder="Nom de votre église" 
                           required
                           autocomplete="organization">
                </div>

                <div class="form-group">
                    <label class="form-label">Description de l'église (optionnel)</label>
                    <textarea class="form-textarea @error('church_description') is-invalid @enderror" 
                              name="church_description" 
                              placeholder="Décrivez brièvement votre église...">{{ old('church_description') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" 
                               class="form-input @error('password') is-invalid @enderror" 
                               name="password" 
                               id="password"
                               placeholder="••••••••" 
                               required
                               autocomplete="new-password">
                        <div class="password-strength" id="password-strength"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" 
                               class="form-input" 
                               name="password_confirmation" 
                               placeholder="••••••••" 
                               required
                               autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="btn" id="registerBtn">
                    <span class="btn-text">
                        <i class="bi bi-building"></i>
                        Créer mon église
                    </span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-dasharray="60" stroke-dashoffset="60">
                                <animate attributeName="stroke-dasharray" dur="1.5s" values="0 60;60 0" repeatCount="indefinite"/>
                                <animate attributeName="stroke-dashoffset" dur="1.5s" values="0;-60" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                        Création en cours...
                    </span>
                </button>
            </form>

            <div class="login-link">
                Déjà une église ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>

        <div class="right-panel">
            <div class="image-container">
                <!-- Image d'inscription avec fallback -->
               <img src="https://images.pexels.com/photos/33953535/pexels-photo-33953535.jpeg" 
                    alt="Inscription" 
                    class="login-image"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <!-- Gradient de remplacement si l'image ne charge pas -->
                <div class="gradient-backup" style="display:none; width:100%; height:100%; background: linear-gradient(135deg, #FF2600 0%, #ff4d33 50%, #ff6b47 100%); align-items:center; justify-content:center; color:white; font-size:24px; font-weight:600;">
                    <div style="text-align:center;">
                        <div style="font-size:48px; margin-bottom:16px;">⛪</div>
                        <div>Eglix</div>
                        <div style="font-size:16px; margin-top:8px; opacity:0.9;">Gestion d'Église</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Vérification de la force du mot de passe
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                return;
            }
            
            let strength = 0;
            let strengthText = '';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            if (strength < 3) {
                strengthText = 'Faible';
                strengthDiv.className = 'password-strength strength-weak';
            } else if (strength < 4) {
                strengthText = 'Moyen';
                strengthDiv.className = 'password-strength strength-medium';
            } else {
                strengthText = 'Fort';
                strengthDiv.className = 'password-strength strength-strong';
            }
            
            strengthDiv.textContent = `Force du mot de passe: ${strengthText}`;
        });

        // Gestion du loading lors de la soumission du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('registerBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            
            // Désactiver le bouton et afficher le loading
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'flex';
            
            // Empêcher la double soumission
            submitBtn.style.pointerEvents = 'none';
        });
    </script>
</body>
</html>
