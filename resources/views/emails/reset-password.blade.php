<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe - Eglix</title>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: #ffffff;
            padding: 40px 30px 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .logo {
            margin-bottom: 20px;
        }
        
        .logo img {
            height: 48px;
            width: auto;
        }
        
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 0;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #475569;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .reset-button {
            display: inline-block;
            background: #FFCC00;
            color: #000000;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 16px;
            text-transform: lowercase;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(255, 204, 0, 0.3);
        }
        
        .reset-button:hover {
            background: #e6b800;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 204, 0, 0.4);
        }
        
        .security-note {
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .security-note h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .security-note p {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            font-size: 14px;
            color: #64748b;
            margin: 0 0 10px 0;
        }
        
        .footer a {
            color: #FFCC00;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .expiry-note {
            font-size: 14px;
            color: #dc2626;
            font-weight: 500;
            margin-top: 20px;
            text-align: center;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .title {
                font-size: 20px;
            }
            
            .reset-button {
                padding: 14px 28px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix" onerror="this.src='{{ asset('images/eglix-logo.png') }}'">
            </div>
            <h1 class="title">réinitialisation de mot de passe</h1>
            <p class="subtitle">demande de récupération de compte</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Bonjour{{ $user ? ' ' . $user->name : '' }},
            </div>
            
            <div class="message">
                <p>Vous avez demandé la réinitialisation de votre mot de passe pour votre compte Eglix.</p>
                
                <p>Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>
            </div>
            
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    réinitialiser mon mot de passe
                </a>
            </div>
            
            <div class="security-note">
                <h3><i class="bi bi-shield-check"></i> Note de sécurité</h3>
                <p>Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email. Votre mot de passe restera inchangé.</p>
            </div>
            
            <div class="expiry-note">
                ⏰ Ce lien expire dans 60 minutes pour votre sécurité.
            </div>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement par Eglix</p>
            <p>Si vous rencontrez des problèmes, contactez notre <a href="mailto:support@eglix.com">support technique</a></p>
            <p style="margin-top: 20px; font-size: 12px; color: #94a3b8;">
                © {{ date('Y') }} Eglix. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
