<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Eglix</title>
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

        .forgot-container {
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

        .alert-success {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
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

        /* Responsive design */
        @media (max-width: 768px) {
            .forgot-container {
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
    <div class="forgot-container">
        <div class="logo">
            <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix" onerror="this.src='{{ asset('images/eglix-logo.png') }}'">
        </div>
        
        <h1 class="title">mot de passe oublié</h1>
        <p class="subtitle">entrez votre adresse email pour recevoir un lien de réinitialisation</p>

        @if (session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('status') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">adresse email</label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="votre@email.com"
                    required 
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope me-2"></i>
                envoyer le lien de réinitialisation
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
</body>
</html>
