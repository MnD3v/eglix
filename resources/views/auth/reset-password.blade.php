<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Eglix</title>
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
            background: #f5f5f5;
            background-image: 
                linear-gradient(rgba(0,0,0,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.02) 1px, transparent 1px);
            background-size: 20px 20px;
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
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        .reset-container {
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
            margin-bottom: 2rem;
        }

        .logo img {
            height: 48px;
            width: auto;
            filter: none;
        }

        .title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
            margin-bottom: 0.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .subtitle {
            font-size: 1rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .form-control {
            height: 52px;
            border-radius: 16px;
            padding: 0 20px;
            font-size: 16px;
            border: 1px solid #e2e8f0;
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
            color: rgba(0, 0, 0, 0.3);
            opacity: 1;
        }

        .btn-primary {
            width: 100%;
            height: 52px;
            background: #FFCC00;
            border: 1px solid #FFCC00;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            transition: all 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-transform: lowercase;
        }

        .btn-primary:hover {
            background: #e6b800;
            border-color: #e6b800;
            color: #000000;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 4px rgba(255, 204, 0, 0.2);
        }

        .btn-link {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn-link:hover {
            color: #FFCC00;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px;
            margin-bottom: 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: capitalize;
            letter-spacing: 0.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .back-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .back-link span {
            padding: 0 16px;
            background: #ffffff;
            color: #64748b;
            font-size: 0.875rem;
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
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #FFCC00;
        }

        .input-group {
            position: relative;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .reset-container {
                padding: 32px 24px;
                margin: 0 16px;
            }

            .title {
                font-size: 1.75rem;
            }

            .subtitle {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="logo">
            <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix" onerror="this.src='{{ asset('images/eglix-logo.png') }}'">
        </div>
        
        <h1 class="title">réinitialiser le mot de passe</h1>
        <p class="subtitle">entrez votre nouveau mot de passe</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="mb-3">
                <label for="email" class="form-label">adresse email</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    value="{{ $email }}" 
                    readonly
                    style="background-color: #f8f9fa;"
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">nouveau mot de passe</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="minimum 8 caractères"
                        required 
                        autofocus
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="password-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">confirmer le mot de passe</label>
                <div class="input-group">
                    <input 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="répétez le mot de passe"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="bi bi-eye" id="password_confirmation-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>
                réinitialiser le mot de passe
            </button>
        </form>

        <div class="back-link">
            <span>
                <a href="{{ route('login') }}" class="btn-link">
                    <i class="bi bi-arrow-left me-1"></i>
                    retour à la connexion
                </a>
            </span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eye = document.getElementById(fieldId + '-eye');
            
            if (field.type === 'password') {
                field.type = 'text';
                eye.classList.remove('bi-eye');
                eye.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                eye.classList.remove('bi-eye-slash');
                eye.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
