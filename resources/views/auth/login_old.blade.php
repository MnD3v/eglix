<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Eglix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --fond:#ffffff; --panneau:#ffffff; --bord:#e5e7eb; --muted:#6B7280; --accent:#00FFB2; --important:#FF2600; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:grid; place-items:center; background:var(--fond); color:#1f2937; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; }
        .grille { width:min(1100px,95vw); background:var(--panneau); border-radius:28px; overflow:hidden; display:grid; grid-template-columns: 1fr 1fr; box-shadow: 0 10px 40px rgba(0,0,0,.08); border:1px solid var(--bord); }
        .gauche { background:#ffffff; padding:40px 36px; display:flex; flex-direction:column; justify-content:center; gap:18px; }
        .marque { display:flex; align-items:center; gap:10px; opacity:.9; }
        .marque img { width:28px; height:28px; }
        .titre { font-size:28px; font-weight:800; color:var(--important); }
        .soustitre { font-size:13px; color:var(--muted); margin-top:-6px; }
        .marque strong { color: var(--important); }
        .champ { display:flex; flex-direction:column; gap:8px; }
        .saisie { width:100%; padding:14px; border-radius:12px; border:1px solid var(--bord); background:#ffffff; color:#111827; outline:none; transition:border .2s, box-shadow .2s; }
        .saisie:focus { border-color:#3b82f6; box-shadow: 0 0 0 4px rgba(59,130,246,.12); }
        .astuces { display:flex; justify-content:space-between; align-items:center; margin-top:-6px; font-size:12px; color:#6b7280; }
        .btn { width:100%; padding:14px 16px; background:var(--important); color:#fff; border:0; border-radius:12px; font-weight:600; cursor:pointer; }
        .btn:hover { filter:brightness(1.05); }
        .ou { display:flex; align-items:center; gap:10px; color:#6b7280; font-size:12px; }
        .ou::before,.ou::after{content:""; height:1px; flex:1; background:var(--bord);} 
        .btn-outline { background:#ffffff; border:1px solid var(--bord); color:#111827; display:flex; align-items:center; justify-content:center; gap:8px; }
        .pied { text-align:center; font-size:12px; color:#6b7280; }
        .droite { background:#ffffff; padding:40px; display:flex; align-items:center; justify-content:center; }
        .device { width: clamp(260px, 90%, 420px); background:#ffffff; border-radius:36px; padding:28px 24px 36px; box-shadow: inset 0 0 0 1px var(--bord), 0 20px 40px rgba(0,0,0,.06); }
        .hero { height:280px; border-radius:24px; background: linear-gradient(145deg,#f8fafc,#eef2f7); display:flex; align-items:center; justify-content:center; position:relative; }
        .hero img { width:58%; height:auto; object-fit:contain; filter: drop-shadow(0 20px 40px rgba(0,0,0,.35)); }
        .puce { position:absolute; top:16px; left:16px; width:14px; height:14px; background:#7c3aed; border-radius:50%; }
        .anneau { position:absolute; right:-14px; bottom:-14px; width:82px; height:82px; border-radius:50%; box-shadow: 0 0 0 10px rgba(16,185,129,.14) inset; }
        .device h2 { text-align:center; margin:14px 0 2px; font-size:18px; }
        .device p { text-align:center; margin:0; color:#6b7280; font-size:12px; }
        .alert { padding:10px 12px; border-radius:10px; font-size:13px; }
        .alert-danger { background:#fff7f7; border:1px solid #fecaca; color:#b91c1c; }
        .alert-success { background:#ecfdf5; border:1px solid #bbf7d0; color:#065f46; }
        @media (max-width:980px){ .grille{ grid-template-columns:1fr; } .droite{ display:none; } }
    </style>
</head>
<body>
    <div class="grille">
        <div class="gauche">
            <div class="marque">
                <img src="/images/eglix.png" alt="Eglix">
                <strong>EGLIX</strong>
            </div>
            <div class="titre">Bon retour&nbsp;!</div>
            <div class="soustitre">Veuillez saisir vos informations de connexion ci-dessous</div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0" style="padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:12px;">
                @csrf
                <div class="champ">
                    <label style="font-size:12px; color:#cbd5e1;">Email</label>
                    <input class="saisie @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@domaine.com" required autofocus>
                </div>
                <div class="champ">
                    <label style="font-size:12px; color:#cbd5e1;">Mot de passe</label>
                    <input class="saisie @error('password') is-invalid @enderror" type="password" name="password" placeholder="••••••••" required>
                    <div class="astuces">
                        <label style="display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="remember" style="accent-color:#10b981;"> Se souvenir de moi
                    </label>
                        <a href="#" style="color:#cbd5e1; text-decoration:none;">Mot de passe oublié&nbsp;?</a>
                </div>
                </div>
                <button class="btn" type="submit">Se connecter</button>
            </form>
            <div class="pied">Pas de compte&nbsp;? <a href="{{ route('register') }}" style="color:#fff; text-decoration:none; font-weight:600;">Créer un compte</a></div>
        </div>

        <div class="droite">
            <div class="device">
                <div class="hero">
                    <span class="puce"></span>
                    <span class="anneau"></span>
                    <img src="https://img.freepik.com/vecteurs-libre/illustration-batiment-eglise-design-plat_52683-86593.jpg" alt="Illustration">
                </div>
                <h2 style="color:#111827;">Gérez votre Église partout</h2>
                <p>Solution complète de gestion d'église: membres, finances, activités.</p>
            </div>
        </div>
    </div>
</body>
</html>


