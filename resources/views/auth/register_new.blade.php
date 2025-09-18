<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Eglix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
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
        }

        .form-textarea:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1);
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

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
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

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            text-align: center;
            margin-top: 32px;
            color: #6b7280;
            font-size: 14px;
        }

        .login-link a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .right-panel {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-illustration {
            width: 280px;
            height: 280px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #8b5cf6;
        }

        .floating-icons {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .floating-icon {
            position: absolute;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .icon-1 { top: 20%; left: 10%; animation: float 3s ease-in-out infinite; }
        .icon-2 { top: 15%; right: 15%; animation: float 3s ease-in-out infinite 0.5s; }
        .icon-3 { bottom: 30%; left: 5%; animation: float 3s ease-in-out infinite 1s; }
        .icon-4 { bottom: 20%; right: 10%; animation: float 3s ease-in-out infinite 1.5s; }
        .icon-5 { top: 50%; left: 0%; animation: float 3s ease-in-out infinite 2s; }
        .icon-6 { top: 60%; right: 0%; animation: float 3s ease-in-out infinite 2.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .success-checkmark {
            position: absolute;
            top: 20%;
            left: 20%;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            font-size: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

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
                <div class="logo-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="logo-text">Eglix</div>
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

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Nom complet</label>
                    <input type="text" 
                           class="form-input @error('name') is-invalid @enderror" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Votre nom complet" 
                           required 
                           autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Adresse email</label>
                    <input type="email" 
                           class="form-input @error('email') is-invalid @enderror" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="exemple@domaine.com" 
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nom de l'église</label>
                    <input type="text" 
                           class="form-input @error('church_name') is-invalid @enderror" 
                           name="church_name" 
                           value="{{ old('church_name') }}" 
                           placeholder="Nom de votre église" 
                           required>
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
                               required>
                        <div class="password-strength" id="password-strength"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" 
                               class="form-input" 
                               name="password_confirmation" 
                               placeholder="••••••••" 
                               required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="bi bi-building"></i>
                    Créer mon église
                </button>
            </form>

            <div class="login-link">
                Déjà une église ? <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>

        <div class="right-panel">
            <div class="illustration-container">
                <div class="main-illustration">
                    <div class="avatar">
                        <i class="bi bi-person-plus"></i>
                    </div>
                </div>
                
                <div class="floating-icons">
                    <div class="floating-icon icon-1">
                        <i class="bi bi-globe"></i>
                    </div>
                    <div class="floating-icon icon-2">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <div class="floating-icon icon-3">
                        <i class="bi bi-headphones"></i>
                    </div>
                    <div class="floating-icon icon-4">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <div class="floating-icon icon-5">
                        <i class="bi bi-phone"></i>
                    </div>
                    <div class="floating-icon icon-6">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>

                <div class="success-checkmark">
                    <i class="bi bi-check"></i>
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
    </script>
</body>
</html>
