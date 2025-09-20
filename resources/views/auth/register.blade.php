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
        }

        .left-panel {
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
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
        }

        .welcome-subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 40px;
        }

        .form-group {
            margin-bottom: 24px;
        }

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
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #FF2600;
            box-shadow: 0 0 0 4px rgba(255, 38, 0, 0.12);
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
            transition: all 0.2s ease;
            background: white;
            resize: vertical;
            min-height: 100px;
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #FF2600;
            box-shadow: 0 0 0 4px rgba(255, 38, 0, 0.12);
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
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 38, 0, 0.3);
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
        }

        /* Styles supprimés car remplacés par l'image */

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
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
            }
            
            .right-panel {
                display: none;
            }
            
            .left-panel {
                padding: 40px 30px;
            }
            
            .welcome-title {
                font-size: 28px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
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
