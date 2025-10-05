<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Eglix</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        .register-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 48px;
            width: 100%;
            max-width: 420px;
            border: 1px solid #e2e8f0;
            position: relative;
            z-index: 1;
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo img {
            height: 48px;
            width: auto;
            filter: none;
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
            margin-bottom: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .subtitle {
            font-size: 16px;
            color: #64748b;
            text-align: center;
            margin-bottom: 32px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 500;
            color: #1e293b;
            background: #ffffff;
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #FFCC00;
            box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.1);
            background: #ffffff;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .input-group {
            position: relative;
        }

        .input-group .form-control {
            padding-right: 50px;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: #1e293b;
            background: #f1f5f9;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #FFCC00;
            border-color: #FFCC00;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.1);
        }

        .form-check-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
            margin: 0;
        }

        .register-btn {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, #FFCC00 0%, #e6b800 100%);
            border: none;
            border-radius: 16px;
            color: #000000;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 204, 0, 0.4);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .register-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }

        .footer a {
            color: #FFCC00;
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .register-container {
                padding: 32px 24px;
                margin: 16px;
            }

            .title {
                font-size: 24px;
            }

            .form-control {
                padding: 14px 16px;
                font-size: 16px;
            }

            .register-btn {
                padding: 14px 20px;
            }
        }

        /* Loading state */
        .loading {
            position: relative;
            color: transparent;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix">
        </div>

        <h1 class="title">Inscription</h1>
        <p class="subtitle">Créez votre compte Eglix</p>

        @if ($errors->any())
            <div class="error-message">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="success-message">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       placeholder="Jean Dupont"
                       required 
                       autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="votre@email.com"
                       required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <div class="input-group">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Votre mot de passe"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="passwordToggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-control" 
                           placeholder="Confirmez votre mot de passe"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePasswordConfirmation()">
                        <i class="bi bi-eye" id="passwordConfirmationToggleIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Informations de l'église -->
            <div class="form-group">
                <label for="church_name" class="form-label">Nom de l'église</label>
                <input type="text" 
                       id="church_name" 
                       name="church_name" 
                       class="form-control @error('church_name') is-invalid @enderror" 
                       value="{{ old('church_name') }}" 
                       placeholder="Ex: Église Adventiste de Dongoyo"
                       required>
            </div>

            <div class="form-group">
                <label for="church_description" class="form-label">Description de l'église (optionnel)</label>
                <textarea id="church_description" 
                          name="church_description" 
                          class="form-control @error('church_description') is-invalid @enderror" 
                          placeholder="Décrivez votre église..."
                          rows="3">{{ old('church_description') }}</textarea>
            </div>

            <div class="form-check">
                <input type="checkbox" 
                       id="terms" 
                       name="terms" 
                       class="form-check-input" 
                       required>
                <label for="terms" class="form-check-label">
                    J'accepte les <a href="#" class="text-decoration-none" style="color: #FFCC00;">conditions d'utilisation</a>
                </label>
            </div>

            <button type="submit" class="register-btn" id="registerBtn">
                <i class="bi bi-person-plus me-2"></i>
                Créer un compte
            </button>
        </form>

        <div class="footer">
            <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye';
            }
        }

        function togglePasswordConfirmation() {
            const passwordInput = document.getElementById('password_confirmation');
            const toggleIcon = document.getElementById('passwordConfirmationToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'bi bi-eye';
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function() {
            const registerBtn = document.getElementById('registerBtn');
            registerBtn.classList.add('loading');
            registerBtn.disabled = true;
        });

        // Auto-focus on name field
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            if (nameInput && !nameInput.value) {
                nameInput.focus();
            }
        });

        // Handle Enter key
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const form = document.getElementById('registerForm');
                if (form) {
                    form.submit();
                }
            }
        });
    </script>
</body>
</html>