<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion d'église</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Syne:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #FF2600; /* brand red */
            --bs-primary-rgb: 255, 38, 0;
            --bs-secondary: #64748B; /* slate-500 */
            --bs-link-color: #FF2600;
            --bs-link-hover-color: #cc1e00;
            --bs-primary-text-emphasis: #661000;
            --bs-primary-bg-subtle: #FFE1DB;
            --bs-primary-border-subtle: #FFC0B5;
        }
        body { font-family: 'DM Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", sans-serif; background-color: #F8FAFC; }
        /* Numeric typography using Syne across the app */
        .numeric, .kpi-value, .badge, .text-end, .amount, .money, .stat-number, .table td.text-end, .card .card-body strong {
            font-family: 'Syne', 'DM Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            font-variant-numeric: tabular-nums lining-nums;
            letter-spacing: .02em;
        }
        h1, h2, h3, h4, h5, h6 { font-weight: 600; }
        .navbar-brand { font-weight: 700; }
        .nav-link.active { color: var(--bs-primary) !important; }

        /* Styles globaux pour les titres de page */
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: white;
        }

        .page-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
            color: white;
        }

        .page-header .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .page-header i {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Force primary color across common components */
        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: #FF2600;
            --bs-btn-border-color: #FF2600;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #e52200;
            --bs-btn-hover-border-color: #e52200;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #cc1e00;
            --bs-btn-active-border-color: #cc1e00;
            --bs-btn-disabled-bg: #FF2600;
            --bs-btn-disabled-border-color: #FF2600;
        }
        .btn-outline-primary {
            --bs-btn-color: #FF2600;
            --bs-btn-border-color: #FF2600;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #FF2600;
            --bs-btn-hover-border-color: #FF2600;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #e52200;
            --bs-btn-active-border-color: #e52200;
        }
        .text-primary { color: #FF2600 !important; }
        .bg-primary { background-color: #FF2600 !important; }
        .border-primary { border-color: #FF2600 !important; }
        .link-primary { color: #FF2600 !important; }
        .page-item.active .page-link {
            background-color: #FF2600;
            border-color: #FF2600;
        }
        .form-check-input:checked { background-color: #FF2600; border-color: #FF2600; }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link { background-color: #FF2600; }
        .progress-bar.bg-primary { background-color: #FF2600 !important; }
        .badge.bg-primary { background-color: #FF2600 !important; }
        .alert-primary { color: #661000; background-color: #FFE1DB; border-color: #FFC0B5; }

        /* Buttons: show icon + text on desktop, icon-only on mobile (outside sidebar) */
        @media (max-width: 991.98px) {
            main.dashboard-main .btn .btn-label { display: none; }
        }

        /* Accents and cards */
        .kpi-card { position: relative; border: 1px solid #eef2f7; box-shadow: 0 6px 16px rgba(2, 6, 23, .05); border-radius: 16px; background: #fff; overflow: hidden; }
        .kpi-card:before { content:""; position:absolute; top:0; left:0; right:0; height:4px; background: var(--accent, #FF2600); }
        .kpi-card .kpi-label { color: #6b7280; letter-spacing: .04em; font-size: .78rem; text-transform: uppercase; }
        .kpi-card .kpi-value { font-weight: 800; font-size: 1.6rem; color: #0f172a; }
        .kpi-icon { width: 40px; height: 40px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size: 1.2rem; }
        .accent-primary { --accent: linear-gradient(90deg,#FF2600,#e52200); }
        .accent-success { --accent: linear-gradient(90deg,#22C55E,#16A34A); }
        .accent-info { --accent: linear-gradient(90deg,#38BDF8,#0EA5E9); }
        .accent-warning { --accent: linear-gradient(90deg,#F59E0B,#F97316); }
        .accent-purple { --accent: linear-gradient(90deg,#7C3AED,#6366F1); }
        .accent-primary .kpi-icon { background: rgba(255,38,0,.12); color:#FF2600; }
        .accent-success .kpi-icon { background: rgba(34,197,94,.12); color:#22C55E; }
        .accent-info .kpi-icon { background: rgba(56,189,248,.12); color:#0EA5E9; }
        .accent-warning .kpi-icon { background: rgba(245,158,11,.12); color:#F59E0B; }
        .accent-purple .kpi-icon { background: rgba(124,58,237,.12); color:#7C3AED; }

        /* KPI links */
        .kpi-link { text-decoration: none; color: inherit; display: block; }
        .kpi-link:focus { outline: none; }
        .kpi-link:focus .kpi-card { box-shadow: 0 0 0 4px rgba(255,38,0,.15), 0 6px 16px rgba(2,6,23,.06); }
        .kpi-card { transition: transform .15s ease, box-shadow .15s ease; }
        .kpi-link:hover .kpi-card { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(2,6,23,.08); }
        .card-soft { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; }
        .card-title { font-weight: 600; }

        /* Sidebar layout */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 220px;
            background-color: #0B1220; /* deeper slate */
            padding-top: 16px;
            z-index: 1030;
            overflow-y: auto; /* scrollable */
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            /* hide scrollbar but keep scroll */
            -ms-overflow-style: none; /* IE/Edge */
            scrollbar-width: none; /* Firefox */
        }
        .sidebar-header { overflow: visible; }
        .sidebar-header img { max-width: 130px; height: auto !important; display: inline-block; }
        @media (max-width: 991.98px) {
            .sidebar-header { padding-top: 24px; }
            .sidebar-header img { max-width: 100px; margin-top: 12px; }
        }
        .sidebar::-webkit-scrollbar { display: none; width: 0; height: 0; }
        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            width: 100%;
            height: 44px;
            padding: 0 16px;
            color: #94A3B8; /* slate-400 */
            text-decoration: none;
            font-weight: 500;
        }
        .sidebar a .sidebar-text {
            display: inline;
        }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: rgba(255,38,0,.14); }
        main.dashboard-main { margin-left: 220px; }

        /* Header hero band */
        .hero {
            background: linear-gradient(135deg, #FF2600 0%, #e52200 100%);
            color: #fff;
            border-radius: 14px;
        }
        /* Responsive sidebar */
        .sidebar-toggle { display: none; }
        .sidebar-backdrop { display: none; }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform .25s ease; width: 260px; }
            .sidebar.show { transform: translateX(0); }
            .sidebar-backdrop { position: fixed; inset: 0; background: rgba(2,6,23,.45); z-index: 1025; display: none; }
            .sidebar-backdrop.show { display: block; }
            main.dashboard-main { margin-left: 0; }
            .mobile-topbar { position: sticky; top: 0; z-index: 1040; background: #0B1220; color: #fff; height: 56px; display: flex; align-items: center; padding: 0 12px; }
            .sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 1px solid rgba(255,255,255,.15); border-radius: 8px; color: #fff; background: transparent; }
            .mobile-brand { display: inline-flex; align-items: center; gap: 10px; margin-left: 10px; }
            .mobile-brand img { height: 28px; }
            .mobile-brand span { font-weight: 700; letter-spacing: .3px; }
        }
    </style>
</head>
<body>
    <div class="mobile-topbar d-lg-none">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir le menu">
            <i class="bi bi-list"></i>
        </button>
       
    </div>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    
    <div class="sidebar">
        <div class="sidebar-header" style="padding: 20px 16px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px;">
            <img src="{{ asset('images/eglix.png') }}" alt="Eglix" style=" height: 50px; margin-bottom: 15px;">
            
            @auth
            <div class="user-info" style="color: white; font-size: 0.9rem;">
                <div style="font-weight: 600; margin-bottom: 5px; color: #fff;">
                    <i class="bi bi-person-circle me-2"></i>
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size: 0.8rem; opacity: 0.8; color: rgba(255,255,255,0.8);">
                    <i class="bi bi-building me-2"></i>
                    {{ Auth::user()->church->name ?? 'Église' }}
                </div>
            </div>
            @endauth
        </div>
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}" title="Accueil"><i class="bi bi-speedometer2"></i><span class="sidebar-text">Accueil</span></a>
        @if(Auth::user() && Auth::user()->hasPermission('members.view'))
        <a href="{{ route('members.index') }}" class="{{ request()->is('members*') ? 'active' : '' }}" title="Membres"><i class="bi bi-people"></i><span class="sidebar-text">Membres</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('tithes.view'))
        <a href="{{ route('tithes.index') }}" class="{{ request()->is('tithes*') ? 'active' : '' }}" title="Dîmes"><i class="bi bi-cash-coin"></i><span class="sidebar-text">Dîmes</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('offerings.view'))
        <a href="{{ route('offerings.index') }}" class="{{ request()->is('offerings*') ? 'active' : '' }}" title="Offrandes"><i class="bi bi-gift"></i><span class="sidebar-text">Offrandes</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('donations.view'))
        <a href="{{ route('donations.index') }}" class="{{ request()->is('donations*') ? 'active' : '' }}" title="Dons"><i class="bi bi-heart"></i><span class="sidebar-text">Dons</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('expenses.view'))
        <a href="{{ route('expenses.index') }}" class="{{ request()->is('expenses*') ? 'active' : '' }}" title="Dépenses"><i class="bi bi-credit-card"></i><span class="sidebar-text">Dépenses</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('projects.view'))
        <a href="{{ route('projects.index') }}" class="{{ request()->is('projects*') ? 'active' : '' }}" title="Projets"><i class="bi bi-kanban"></i><span class="sidebar-text">Projets</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('services.view'))
        <a href="{{ route('services.index') }}" class="{{ request()->is('services*') ? 'active' : '' }}" title="Cultes"><i class="bi bi-music-note-beamed"></i><span class="sidebar-text">Cultes</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('events.view'))
        <a href="{{ route('events.index') }}" class="{{ request()->is('events*') ? 'active' : '' }}" title="Événements"><i class="bi bi-calendar-event"></i><span class="sidebar-text">Événements</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('reports.view'))
        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}" title="Rapports"><i class="bi bi-graph-up"></i><span class="sidebar-text">Rapports</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('agenda.view'))
        <a href="{{ route('agenda.index') }}" class="{{ request()->is('agenda*') ? 'active' : '' }}" title="Agenda"><i class="bi bi-calendar4-week"></i><span class="sidebar-text">Agenda</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('journal.view'))
        <a href="{{ route('journal.index') }}" class="{{ request()->is('journal*') ? 'active' : '' }}" title="Journal"><i class="bi bi-journal-text"></i><span class="sidebar-text">Journal</span></a>
        @endif
        @if(Auth::user() && (Auth::user()->isChurchAdmin() || Auth::user()->hasPermission('administration.view')))
        <a href="{{ route('administration.index') }}" class="{{ request()->is('administration*') ? 'active' : '' }}" title="Administration"><i class="bi bi-person-badge"></i><span class="sidebar-text">Administration</span></a>
        @endif
        @if(Auth::user() && (Auth::user()->isChurchAdmin() || Auth::user()->hasPermission('users.view')))
        <a href="{{ route('user-management.index') }}" class="{{ request()->is('user-management*') ? 'active' : '' }}" title="Comptes"><i class="bi bi-people-fill"></i><span class="sidebar-text">Comptes</span></a>
        @endif
        
        <!-- Bouton de déconnexion -->
        <div style="margin-top: 20px; padding: 0 16px;">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100" style="border-radius: 8px; font-size: 0.875rem;" title="Se déconnecter">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span class="sidebar-text">Déconnexion</span>
                </button>
            </form>
        </div>
        
        <!-- Crédit développeur en bas de sidebar -->
        <div class="sidebar-footer" style="margin-top: auto; padding: 16px; border-top: 1px solid rgba(255,255,255,0.1); text-align: center;">
            <p class="text-muted mb-2 small" style="font-size: 0.75rem;">Développé par</p>
            <a href="#" target="_blank" rel="noopener" style="display: inline-block;">
                <img src="{{ asset('images/lafiatech-white.png') }}" alt="Lafiatech" style="height: 24px; opacity: 0.8; transition: opacity 0.2s;">
            </a>
        </div>
    </div>

    <main class="dashboard-main">
        @yield('content')
    </main>

    <!-- Global Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmModalMessage">Êtes-vous sûr ?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmModalOk">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Firebase (compat) for simple Storage uploads from views -->
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-storage-compat.js"></script>
    <script>
    // Auto-wrap button text into .btn-label so CSS can hide it on mobile (keeps sidebar untouched)
    (function(){
        document.addEventListener('DOMContentLoaded', function(){
            const candidates = document.querySelectorAll('main.dashboard-main .btn');
            candidates.forEach(btn => {
                if (btn.querySelector('.btn-label')) return; // already processed
                // skip if button has no text content besides whitespace
                const text = btn.childNodes;
                let hasText = false;
                text.forEach(n => { if (n.nodeType === Node.TEXT_NODE && n.nodeValue.trim().length) hasText = true; });
                if (!hasText) return;
                // Heuristic: if no icon exists, prepend one based on common keywords
                if (!btn.querySelector('i')) {
                    const raw = (btn.textContent || '').toLowerCase();
                    let icon = null;
                    if (/(ajouter|nouveau|add|create)/.test(raw)) icon = 'bi-plus-lg';
                    else if (/(modifier|edit)/.test(raw)) icon = 'bi-pencil';
                    else if (/(supprimer|delete)/.test(raw)) icon = 'bi-trash';
                    else if (/(filtrer|filter)/.test(raw)) icon = 'bi-funnel';
                    else if (/(réinitialiser|reset)/.test(raw)) icon = 'bi-arrow-counterclockwise';
                    else if (/(télécharger|download|pdf)/.test(raw)) icon = 'bi-file-earmark-arrow-down';
                    else if (/(export)/.test(raw)) icon = 'bi-upload';
                    if (icon) {
                        const i = document.createElement('i');
                        i.className = `bi ${icon}`;
                        btn.prepend(i);
                    }
                }
                const span = document.createElement('span');
                span.className = 'btn-label';
                const toMove = [];
                text.forEach(n => { if (n.nodeType === Node.TEXT_NODE && n.nodeValue.trim().length) toMove.push(n); });
                toMove.forEach(n => { span.appendChild(document.createTextNode(n.nodeValue.trimStart())); n.remove(); });
                // Insert a spacing if preceding sibling is an icon
                const first = btn.firstElementChild;
                if (first && first.tagName === 'I') {
                    const space = document.createTextNode(' ');
                    btn.appendChild(space);
                }
                btn.appendChild(span);
            });
        });
    })();
    </script>
    <script src="{{ asset('js/app-init.js') }}"></script>
    @stack('scripts')
    <script>
    (function(){
        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const toggle = document.getElementById('sidebarToggle');
        function openSidebar(){ sidebar.classList.add('show'); backdrop.classList.add('show'); }
        function closeSidebar(){ sidebar.classList.remove('show'); backdrop.classList.remove('show'); }
        if (toggle) {
            toggle.addEventListener('click', function(){
                if (sidebar.classList.contains('show')) { closeSidebar(); } else { openSidebar(); }
            });
        }
        if (backdrop) { backdrop.addEventListener('click', closeSidebar); }
        document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeSidebar(); });
    })();
    </script>
    <script>
    // Replace native confirm() prompts on delete forms with a Bootstrap modal
    (function(){
        let targetForm = null;
        const modalEl = document.getElementById('confirmModal');
        if (!modalEl) return;
        const modal = new bootstrap.Modal(modalEl);
        const okBtn = document.getElementById('confirmModalOk');
        const msgEl = document.getElementById('confirmModalMessage');

        function wireForm(form){
            // Remove inline confirm handler if present
            const onsubmit = form.getAttribute('onsubmit') || '';
            if (onsubmit.includes('confirm(')) {
                form.removeAttribute('onsubmit');
            }
            if (form.__confirmWired) return;
            form.addEventListener('click', function(e){ e.stopPropagation(); });
            form.addEventListener('submit', function(e){
                // Only intercept if explicitly marked or previously had confirm()
                const hadNative = onsubmit.includes('confirm(');
                const wantsConfirm = hadNative || form.hasAttribute('data-confirm');
                if (!wantsConfirm) return;
                // If already confirmed, let it submit
                if (form.__confirmBypass === true) return;
                e.preventDefault();
                targetForm = form;
                const message = form.getAttribute('data-confirm') || 'Supprimer ?';
                const actionText = form.getAttribute('data-confirm-ok') || 'Supprimer';
                if (msgEl) msgEl.textContent = message;
                if (okBtn) okBtn.textContent = actionText;
                modal.show();
            });
            form.__confirmWired = true;
        }

        // Wire existing forms
        document.querySelectorAll('form[data-confirm], form[onsubmit*="confirm("]').forEach(wireForm);
        // Wire dynamically added forms
        const observer = new MutationObserver(() => {
            document.querySelectorAll('form[data-confirm], form[onsubmit*="confirm("]').forEach(wireForm);
        });
        observer.observe(document.body, { childList: true, subtree: true });

        if (okBtn) {
            okBtn.addEventListener('click', function(){
                if (targetForm) {
                    modal.hide();
                    // submit via requestSubmit to trigger native validations
                    targetForm.__confirmBypass = true;
                    if (targetForm.requestSubmit) targetForm.requestSubmit(); else targetForm.submit();
                    // reset bypass after a tick to avoid leaking state if navigation is prevented
                    setTimeout(() => { if (targetForm) targetForm.__confirmBypass = false; }, 0);
                    targetForm = null;
                }
            });
        }
    })();
    </script>
    <script>
    // Make entire member card clickable without blocking inner buttons/forms
    (function(){
        document.addEventListener('click', function(e){
            const btn = e.target.closest('a, button, [data-bs-toggle], form');
            if (btn) return; // let interactive elements work
            const cardLink = e.target.closest('.card-link[data-href]');
            if (cardLink) {
                window.location.href = cardLink.getAttribute('data-href');
            }
        });
    })();
    </script>
</body>
 </html>


