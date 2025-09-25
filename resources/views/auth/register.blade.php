<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Eglix</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: url('{{ asset("images/auth-background.png") }}') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 48px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo img {
            height: 48px;
            width: auto;
        }

        .title {
            font-size: 28px;
            font-weight: 600;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 15px;
            color: #666666;
            text-align: center;
            margin-bottom: 32px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333333;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid #e1e5e9;
            border-radius: 12px;
            font-size: 15px;
            background-color: #ffffff;
            color: #333333;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #ff2600;
            box-shadow: 0 0 0 3px rgba(255, 38, 0, 0.1);
        }

        .form-control::placeholder {
            color: #999999;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .form-check-input {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            accent-color: #ff2600;
        }

        .form-check-label {
            font-size: 14px;
            color: #666666;
            cursor: pointer;
        }

        .register-btn {
            width: 100%;
            padding: 16px 20px;
            background: #ff2600;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 24px;
        }

        .register-btn:hover {
            background: #e02200;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.25);
        }

        .auth-links {
            text-align: center;
        }

        .auth-links a {
            color: #ff2600;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .auth-links a:hover {
            color: #e02200;
            text-decoration: underline;
        }

        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #c33;
        }

        .success-message {
            background: #efe;
            color: #3c3;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #3c3;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 32px 24px;
                margin: 10px;
            }

            .title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix">
        </div>

        <!-- Titre -->
        <h1 class="title">Rejoignez Eglix</h1>
        <p class="subtitle">Créez votre compte pour commencer</p>

        <!-- Messages d'erreur/succès -->
        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulaire -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="church_name" class="form-label">Nom de l'église</label>
                <input type="text" class="form-control" id="church_name" name="church_name" value="{{ old('church_name') }}" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">
                    J'accepte les <a href="#" style="color: #ff2600;">conditions d'utilisation</a>
                </label>
            </div>

            <button type="submit" class="register-btn">
                Créer mon compte
            </button>
        </form>

        <!-- Liens -->
        <div class="auth-links">
            <a href="{{ route('login') }}">Déjà un compte ? Se connecter</a>
        </div>
    </div>
</body>
</html>