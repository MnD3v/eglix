<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Eglix - Application de gestion d'église</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.meta')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/appbar.css') }}" rel="stylesheet">
    <style>
        /* Couleurs personnalisées - Toutes les couleurs primaires sont maintenant #FFCC00 */
        .btn {
            background: #FFCC00 !important;
            border-color: #FFCC00 !important;
            color: black !important;
        }
        
        .btn:hover {
            background: #e6b800 !important;
            border-color: #e6b800 !important;
            color: black !important;
        }
        
        .bg-custom { 
            background-color: #FFCC00 !important; 
            color: black !important;
        }
        
        .text-custom { 
            color: #FFCC00 !important; 
        }
        
        .badge.bg-custom { 
            background-color: #FFCC00 !important; 
            color: white !important;
        }
        
        /* Boutons d'ajout - Design cohérent et moderne */
        .btn-add {
            background: linear-gradient(135deg, #FFCC00 0%, #e02200 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 38, 0, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.35);
            color: white;
            text-decoration: none;
        }
        
        .btn-add:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(255, 38, 0, 0.25);
        }
        
        .btn-add i {
            font-size: 16px;
            color: white;
        }
        
        .btn-add .btn-text {
            color: white;
            font-weight: 600;
        }
        
        /* Effet de brillance au survol */
        .btn-add::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-add:hover::before {
            left: 100%;
        }
        
        /* Variantes de taille */
        .btn-add.btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 8px;
        }
        
        .btn-add.btn-lg {
            padding: 16px 32px;
            font-size: 16px;
            border-radius: 16px;
        }
        
        /* Bouton d'état vide */
        .btn-add-empty {
            background: linear-gradient(135deg, #FFCC00 0%, #e02200 100%);
            border: none;
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            color: white;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 38, 0, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-top: 20px;
        }
        
        .btn-add-empty:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(255, 38, 0, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .btn-add-empty i {
            font-size: 20px;
            color: white;
        }
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        .btn-loading .btn-text {
            opacity: 0;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .form-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .form-loading::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -20px;
            margin-top: -20px;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #FFCC00;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 1001;
        }
        
        /* Loader pour les boutons de soumission */
        .submit-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        
        .submit-loading .btn-text {
            opacity: 0;
        }
        
        .submit-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .btn-secondary {
            background: #6c757d !important;
            border-color: #6c757d !important;
            color: white !important;
        }
        
        .btn-outline-secondary {
            background: transparent !important;
            border-color: #6c757d !important;
            color: #6c757d !important;
        }
        
        :root {
            --bs-primary: #FFCC00; /* brand red */
            --bs-primary-rgb: 255, 38, 0;
            --bs-secondary: #64748B; /* slate-500 */
            --bs-link-color: #FFCC00;
            --bs-link-hover-color: #cc1e00;
            --bs-primary-text-emphasis: #661000;
            --bs-primary-bg-subtle: #FFE1DB;
            --bs-primary-border-subtle: #FFC0B5;
        }
        body { 
            font-family: 'DM Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", sans-serif; 
            background: #ffffff;
            min-height: 100vh;
        }
        /* Numeric typography using Plus Jakarta Sans across the app */
        .numeric, .kpi-value, .badge, .text-end, .amount, .money, .stat-number, .table td.text-end, .card .card-body strong {
            font-family: 'Plus Jakarta Sans', 'DM Sans', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            font-variant-numeric: tabular-nums lining-nums;
            letter-spacing: .02em;
        }
        h1, h2, h3, h4, h5, h6 { font-weight: 600; }
        .navbar-brand { font-weight: 700; }
        .nav-link.active { color: var(--bs-primary) !important; }

        /* Styles globaux pour les titres de page - Style élégant et minimaliste */
        .page-header {
            background: #ffffff;
            color: #1e293b;
            border-radius: 0;
            padding: 2rem 0;
            margin: 0 0 2rem 0;
            position: relative;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Styles cohérents pour les contenants de titres */
        .card-header {
            padding: 1rem 1.25rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }

        .section-header {
            background: #ffffff;
            border-radius: 0;
            padding: 2rem 0;
            margin: 0 0 2rem 0;
            position: relative;
            border-bottom: 1px solid #f1f5f9;
        }

        .content-header {
            padding: 0.75rem 1rem;
            background: #f8f9fa;
            border-left: 4px solid #FFCC00;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        /* Styles cohérents pour les KPIs */
        .kpi-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .kpi-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .kpi-card {
            padding: 1.25rem;
            border-radius: 12px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* Boutons dans les page-headers */
        .page-header .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
            background: #FFCC00;
            border: none;
            color: white;
        }

        .page-header .btn-sm:hover {
            background: #e02200;
            color: white;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .page-title i {
            background: #f8fafc;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 1.25rem;
            color: #64748b !important;
        }

        /* Styles pour le total des dîmes */
        .total-amount {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .total-amount .h4 {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .total-amount small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .page-header .text-muted {
            color: rgba(255,255,255,0.7) !important;
        }

        .page-header i:not(.page-title i) {
            color: rgba(255,255,255,0.7) !important;
        }

        /* Responsive design pour les titres */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                margin: 0 0 1.5rem 0;
            }
            
            .page-title {
                font-size: 2rem;
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
            
            .page-subtitle {
                text-align: center;
            }
        }

        /* Icônes des boutons d'ajout en blanc */
        .btn i,
        .btn .bi-plus-lg,
        .btn .bi-plus-circle,
        .btn .bi-person-plus-fill,
        .btn-new-user i,
        .btn-new-user .bi-plus-lg,
        .btn-new-user .bi-person-plus-fill,
        /* Tous les boutons avec icônes d'ajout */
        .btn .bi-plus-lg,
        .btn .bi-plus-circle,
        .btn .bi-person-plus-fill {
            color: white !important;
        }

        /* Force primary color across common components */
        .btn {
            --bs-btn-color: #fff;
            --bs-btn-bg: #FFCC00;
            --bs-btn-border-color: #FFCC00;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #e52200;
            --bs-btn-hover-border-color: #e52200;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #cc1e00;
            --bs-btn-active-border-color: #cc1e00;
            --bs-btn-disabled-bg: #FFCC00;
            --bs-btn-disabled-border-color: #FFCC00;
        }
        .btn-outline-primary {
            --bs-btn-color: #FFCC00;
            --bs-btn-border-color: #FFCC00;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #FFCC00;
            --bs-btn-hover-border-color: #FFCC00;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #e52200;
            --bs-btn-active-border-color: #e52200;
        }
        .text-custom { color: #FFCC00 !important; }
        .bg-custom { background-color: #FFCC00 !important; }
        .border-primary { border-color: #FFCC00 !important; }
        .link-primary { color: #FFCC00 !important; }
        .page-item.active .page-link {
            background-color: #FFCC00;
            border-color: #FFCC00;
            color: black !important;
        }
        .form-check-input:checked { background-color: #FFCC00; border-color: #FFCC00; }
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link { background-color: #FFCC00; color: black !important; }
        .progress-bar.bg-custom { background-color: #FFCC00 !important; }
        .badge.bg-custom { background-color: #FFCC00 !important; }
        .alert-primary { color: #661000; background-color: #FFE1DB; border-color: #FFC0B5; }

        /* Buttons: show icon + text on desktop, icon-only on mobile (outside sidebar) */
        @media (max-width: 991.98px) {
            main.dashboard-main .btn .btn-label { display: none; }
        }

        /* Accents and cards */
        .kpi-card { position: relative; border: 1px solid #eef2f7; box-shadow: none; border-radius: 16px; background: #f8fafc; overflow: hidden; }
        .kpi-card:before { display: none; }
        .kpi-card .kpi-label { color: #6b7280; letter-spacing: .04em; font-size: .78rem; text-transform: uppercase; }
        .kpi-card .kpi-value { font-weight: 800; font-size: 1.6rem; color: #0f172a; }
        .kpi-icon { width: 40px; height: 40px; border-radius: 12px; display:flex; align-items:center; justify-content:center; font-size: 1.2rem; }
        .accent-primary { --accent: linear-gradient(90deg,#FFCC00,#e52200); }
        .accent-success { --accent: linear-gradient(90deg,#22C55E,#16A34A); }
        .accent-info { --accent: linear-gradient(90deg,#38BDF8,#0EA5E9); }
        .accent-warning { --accent: linear-gradient(90deg,#F59E0B,#F97316); }
        .accent-purple { --accent: linear-gradient(90deg,#7C3AED,#6366F1); }
        .accent-primary .kpi-icon { background: rgba(255,38,0,.12); color:#FFCC00; }
        .accent-success .kpi-icon { background: rgba(34,197,94,.12); color:#22C55E; }
        .accent-info .kpi-icon { background: rgba(56,189,248,.12); color:#0EA5E9; }
        .accent-warning .kpi-icon { background: rgba(245,158,11,.12); color:#F59E0B; }
        .accent-purple .kpi-icon { background: rgba(124,58,237,.12); color:#7C3AED; }
        
        /* Classes Bootstrap pour purple */
        .bg-purple { background-color: #7C3AED !important; }
        .text-purple { color: #7C3AED !important; }
        .bg-purple-subtle { background-color: rgba(124,58,237,.12) !important; }
        .text-purple { color: #7C3AED !important; }

        /* Classes supplémentaires pour les couleurs de documents */
        .bg-success-subtle { background-color: rgba(34,197,94,.12) !important; }
        .text-success { color: #22C55E !important; }
        .bg-danger-subtle { background-color: rgba(239,68,68,.12) !important; }
        .text-danger { color: #EF4444 !important; }
        .bg-primary-subtle { background-color: rgba(59,130,246,.12) !important; }
        .text-primary { color: #3B82F6 !important; }
        .bg-warning-subtle { background-color: rgba(245,158,11,.12) !important; }
        .text-warning { color: #F59E0B !important; }
        .bg-info-subtle { background-color: rgba(56,189,248,.12) !important; }
        .text-info { color: #38BDF8 !important; }
        .bg-secondary-subtle { background-color: rgba(107,114,128,.12) !important; }
        .text-secondary { color: #6B7280 !important; }

        /* Styles pour les cartes de documents */
        .document-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .document-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #d1d5db;
        }

        .document-icon-wrapper {
            position: relative;
        }

        .document-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .document-image-container {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }

        .document-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .document-image-container:hover .document-image {
            transform: scale(1.05);
        }

        .document-badges {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: flex-end;
        }

        .document-type-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .document-public-badge {
            font-size: 10px;
            padding: 4px 6px;
            border-radius: 6px;
        }

        .document-title {
            font-weight: 600;
            color: #1f2937;
            line-height: 1.4;
            font-size: 14px;
        }

        .document-description {
            line-height: 1.4;
            font-size: 12px;
        }

        .document-metadata {
            background: #f9fafb;
            border-radius: 8px;
            padding: 8px 12px;
            margin-top: 8px;
        }

        .document-folder {
            font-weight: 500;
            color: #6b7280;
        }

        .document-size {
            font-weight: 500;
            color: #6b7280;
        }

        .document-actions {
            background: #f9fafb;
            margin: 0 -16px -16px -16px;
            padding: 12px 16px;
        }

        .document-action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }

        .document-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .document-action-btn.btn-outline-primary:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .document-action-btn.btn-outline-success:hover {
            background-color: #10b981;
            border-color: #10b981;
            color: white;
        }

        .document-action-btn.btn-outline-warning:hover {
            background-color: #f59e0b;
            border-color: #f59e0b;
            color: white;
        }

        .document-action-btn.btn-outline-danger:hover {
            background-color: #ef4444;
            border-color: #ef4444;
            color: white;
        }

        /* Animation d'apparition */
        .document-card {
            animation: fadeInUp 0.6s ease-out;
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

        /* Responsive */
        @media (max-width: 768px) {
            .document-card {
                margin-bottom: 16px;
            }
            
            .document-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .document-image-container {
                width: 40px;
                height: 40px;
            }
            
            .document-title {
                font-size: 13px;
            }
            
            .document-action-btn {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }
        }

        /* Styles pour les titres de section - Style élégant et minimaliste */
        .section-header {
            background: #ffffff;
            border-radius: 0;
            padding: 2rem 0;
            margin: 0 0 2rem 0;
            position: relative;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-title {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .section-title-icon {
            width: 40px;
            height: 40px;
            background: #f8fafc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #64748b;
            flex-shrink: 0;
        }

        .section-subtitle {
            color: #64748b;
            font-size: 1rem;
            font-weight: 400;
            margin: 0.5rem 0 0 0;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-subtitle-icon {
            font-size: 14px;
            color: #94a3b8;
        }

        .section-actions {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .section-action-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .section-action-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .section-action-btn i {
            font-size: 1rem;
        }

        /* Responsive pour les titres */
        @media (max-width: 768px) {
            .section-header {
                padding: 20px;
                margin-top: 20px;
                margin-bottom: 20px;
            }
            
            .section-title {
                font-size: 1.8rem;
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .section-title-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .section-subtitle {
                margin: 8px 0 0 0;
                text-align: center;
                justify-content: center;
            }
            
            .section-actions {
                position: static;
                transform: none;
                margin-top: 20px;
                display: flex;
                justify-content: center;
            }
        }

        /* KPI links */
        .kpi-link { text-decoration: none; color: inherit; display: block; }
        .kpi-link:focus { outline: none; }
        .kpi-link:focus .kpi-card { box-shadow: 0 0 0 4px rgba(255,38,0,.15); }
        .kpi-card { transition: transform .15s ease, box-shadow .15s ease; }
        .kpi-link:hover .kpi-card { transform: translateY(-2px); box-shadow: none; }
        .card-soft { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; }
        .card-title { font-weight: 600; }

        /* Sidebar layout */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 220px;
            background-color: #F1F1F1; /* fond gris clair */
            padding-top: 0;
            z-index: 1030;
            overflow-y: auto; /* scrollable */
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            /* hide scrollbar but keep scroll */
            -ms-overflow-style: none; /* IE/Edge */
            scrollbar-width: none; /* Firefox */
            border-right: 1px solid #e2e8f0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
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
            gap: 12px;
            width: 100%;
            height: 48px;
            padding: 0 20px;
            color: #64748b; /* slate-500 */
            text-decoration: none;
            font-weight: 300;
            font-size: 14px;
            transition: all 0.2s ease;
            border-radius: 0;
            margin: 2px 12px;
            border-radius: 8px;
        }
        .sidebar a .sidebar-text {
            display: inline;
        }
        .sidebar a:hover { 
            color: #1e293b; 
            background-color: #f1f5f9; 
            transform: translateX(2px);
        }
        .sidebar a.active { 
            color: #1e293b; 
            background-color: #E0E0E0; 
            font-weight: 500;
            border-radius: 12px;
            margin: 4px 8px;
            padding: 12px 16px;
        }
        .sidebar a.active i {
            color: #FFD700;
        }

        main.dashboard-main { 
            margin-left: 220px; 
            background: #ffffff;
            min-height: 100vh;
            border-radius: 0;
        }

        /* Cartes avec fond blanc */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Container avec transparence */
        .container-fluid {
            background: transparent;
        }

        /* Cartes de statistiques modernes */
        .stats-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
        }

        .stats-card::before {
            display: none;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 24px;
            color: white;
            position: relative;
        }

        .stats-icon-primary {
            background: linear-gradient(135deg, #FFCC00, #e02200);
        }

        .stats-icon-success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stats-icon-danger {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
        }

        .stats-icon-info {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }

        .stats-number {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 8px 0;
            line-height: 1;
        }

        .stats-label {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: #666666;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Header hero band */
        .hero {
            background: linear-gradient(135deg, #FFCC00 0%, #e52200 100%);
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
            .mobile-topbar { position: sticky; top: 0; z-index: 1040; background: #ffffff; color: #1e293b; height: 56px; display: flex; align-items: center; padding: 0 12px; border-bottom: 1px solid #e2e8f0; }
            .sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; background: transparent; }
            .mobile-brand { display: inline-flex; align-items: center; gap: 10px; margin-left: 10px; }
            .mobile-brand img { height: 28px; }
            .mobile-brand span { font-weight: 700; letter-spacing: .3px; }
        }

        /* Animations douces */
        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        .slide-in-up {
            animation: slideInUp 0.8s ease-out;
        }

        .slide-in-left {
            animation: slideInLeft 0.7s ease-out;
        }

        .slide-in-right {
            animation: slideInRight 0.7s ease-out;
        }

        .scale-in {
            animation: scaleIn 0.5s ease-out;
        }

        .bounce-in {
            animation: bounceIn 0.8s ease-out;
        }

        .card-soft {
            transition: all 0.3s ease;
        }

        .card-soft:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 38, 0, 0.05);
            transform: scale(1.01);
        }

        .modal {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-dialog {
            animation: slideInUp 0.3s ease-out;
        }

        .alert {
            animation: slideInDown 0.5s ease-out;
        }

        .badge {
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.1);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            transform: translateX(5px);
        }

        .sidebar-nav .nav-link {
            transition: all 0.3s ease;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(8px);
        }

        .sidebar-nav .nav-link.active {
            transform: translateX(10px);
        }

        /* Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideInUp {
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
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

        @keyframes slideInDown {
            from { 
                opacity: 0; 
                transform: translateY(-30px); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        @keyframes scaleIn {
            from { 
                opacity: 0; 
                transform: scale(0.8); 
            }
            to { 
                opacity: 1; 
                transform: scale(1); 
            }
        }

        @keyframes bounceIn {
            0% { 
                opacity: 0; 
                transform: scale(0.3); 
            }
            50% { 
                opacity: 1; 
                transform: scale(1.05); 
            }
            70% { 
                transform: scale(0.9); 
            }
            100% { 
                opacity: 1; 
                transform: scale(1); 
            }
        }

        /* Animation pour les éléments qui apparaissent au scroll */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Animation pour les cartes de statistiques */
        .stat-card {
            transition: all 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Animation pour les boutons d'action */
        .btn-action {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-action::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-action:hover::before {
            left: 100%;
        }

        /* Animation pour les formulaires */
        .form-control {
            transition: all 0.3s ease;
        }

        .form-control:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 0.2rem rgba(255, 38, 0, 0.25);
        }

        /* Animation pour les icônes */
        .bi {
            transition: all 0.3s ease;
        }

        .btn:hover .bi {
            transform: scale(1.1);
        }

        /* Animation pour les tableaux */
        .table-responsive {
            animation: fadeIn 0.6s ease-out;
        }

        /* Animation pour les modals */
        .modal-content {
            animation: scaleIn 0.3s ease-out;
        }

        /* Animation pour les alertes */
        .alert {
            animation: slideInDown 0.5s ease-out;
        }

        .alert-dismissible .btn-close {
            transition: all 0.3s ease;
        }

        .alert-dismissible .btn-close:hover {
            transform: scale(1.2);
        }

        /* Styles pour la page de détail des membres */
        .member-profile-header {
            position: relative;
            background: #FFCC00;
            border-radius: 20px;
            margin-bottom: 2rem;
            overflow: hidden;
            min-height: 200px;
        }

        .profile-background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .profile-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
            color: white;
        }

        .profile-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .profile-info-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }

        .profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-inactive {
            background: rgba(107, 114, 128, 0.2);
            color: #6b7280;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .meta-item {
            font-size: 0.875rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
        }

        .profile-actions {
            display: flex;
            gap: 1rem;
        }

        .profile-actions .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Cartes d'informations */
        .info-card, .stats-card, .remarks-section, .tithes-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .info-header, .stats-header, .remarks-header, .tithes-header {
            background: #ffffff;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .info-title, .stats-title, .remarks-title, .tithes-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .info-content, .stats-content, .remarks-list, .tithes-content {
            padding: 1.5rem;
        }

        .info-item {
            margin-bottom: 1.5rem;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .info-value {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
        }

        /* Cartes de statistiques */
        .stat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .stat-item:last-child {
            margin-bottom: 0;
        }

        .stat-item:hover {
            background: #ffffff;
            transform: translateX(5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #FFCC00;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* Section des remarques */
        .remarks-header, .tithes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add-remark, .btn-add-tithe {
            background: #FFCC00;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-remark:hover, .btn-add-tithe:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 38, 0, 0.3);
        }

        .remark-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid #FFCC00;
            transition: all 0.3s ease;
        }

        .remark-item:last-child {
            margin-bottom: 0;
        }

        .remark-item:hover {
            background: #ffffff;
            transform: translateX(5px);
        }

        .remark-content {
            flex: 1;
        }

        .remark-text {
            font-size: 1rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .remark-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .remark-date, .remark-author {
            display: flex;
            align-items: center;
        }

        .btn-remove-remark {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-remove-remark:hover {
            background: #dc2626;
            color: white;
            transform: scale(1.1);
        }

        /* Section des dîmes */
        .tithe-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: #ffffff;
            border-radius: 12px;
            margin-bottom: 1rem;
            border-left: 4px solid #22c55e;
            transition: all 0.3s ease;
        }

        .tithe-item:last-child {
            margin-bottom: 0;
        }

        .tithe-item:hover {
            background: #ffffff;
            transform: translateX(5px);
        }

        .tithe-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .tithe-date, .tithe-method, .tithe-reference {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #64748b;
        }

        .tithe-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #22c55e;
        }

        /* États vides */
        .remarks-empty, .tithes-empty {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #94a3b8;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .empty-description {
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-content {
                padding: 1.5rem;
            }

            .profile-main {
                flex-direction: column;
                align-items: stretch;
                gap: 1.5rem;
            }

            .profile-info-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 1rem;
            }

            .profile-name {
                font-size: 1.5rem;
                text-align: center;
                width: 100%;
            }

            .profile-meta {
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
                width: 100%;
            }

            .profile-actions {
                flex-direction: column;
                width: 100%;
                gap: 0.75rem;
            }

            .profile-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .remarks-header, .tithes-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .remarks-header .btn, .tithes-header .btn {
                width: 100%;
                justify-content: center;
            }

            .remark-item, .tithe-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .remark-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .tithe-item {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .tithe-info {
                order: 1;
            }

            .tithe-amount {
                order: 2;
                text-align: center;
                font-size: 1.5rem;
            }

            /* Amélioration des cartes sur mobile */
            .info-card, .stats-card, .remarks-section, .tithes-section {
                margin-bottom: 1.5rem;
            }

            .info-header, .stats-header, .remarks-header, .tithes-header {
                padding: 1rem;
            }

            .info-content, .stats-content, .remarks-list, .tithes-content {
                padding: 1rem;
            }

            /* Espacement des éléments d'information */
            .info-item {
                margin-bottom: 1rem;
            }

            .stat-item {
                padding: 0.75rem;
            }

            .remark-item {
                padding: 1rem;
            }

            .tithe-item {
                padding: 1rem;
            }
        }
        
        /* Amélioration générale de l'organisation mobile */
        @media (max-width: 991.98px) {
            /* Container et padding */
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            /* Page header mobile */
            .page-header {
                padding: 1rem 1.25rem !important;
                margin: 0 0 1.5rem 0 !important;
            }
            
            .page-title {
                font-size: 1.75rem !important;
                margin-bottom: 0.5rem;
            }
            
            .page-subtitle {
                font-size: 0.9rem !important;
            }
            
            /* Contenants de titres mobile */
            .card-header {
                padding: 0.75rem 1rem !important;
            }
            
            .section-header {
                padding: 0.75rem 1rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            .content-header {
                padding: 0.5rem 0.75rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            /* KPIs mobile */
            .kpi-header {
                gap: 0.75rem !important;
                margin-bottom: 0.75rem !important;
            }
            
            .kpi-title {
                font-size: 0.9rem !important;
            }
            
            .kpi-card {
                padding: 1rem !important;
            }
            
            /* Boutons dans page-headers mobile */
            .page-header .btn-sm {
                padding: 0.375rem 0.75rem !important;
                font-size: 0.8rem !important;
                border-radius: 10px !important;
            }
            
            .page-header .btn {
                padding: 0.5rem 1rem !important;
                font-size: 0.875rem !important;
            }
            
            /* Total amount mobile */
            .total-amount {
                padding: 0.75rem 1rem !important;
                border-radius: 6px !important;
            }
            
            .total-amount .h4 {
                font-size: 1.5rem !important;
            }
            
            /* Cartes et grilles */
            .row.g-3 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }
            
            .card {
                margin-bottom: 0.75rem;
            }
            
            .card-body {
                padding: 1rem !important;
            }
            
            /* Boutons mobile */
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 0.8rem;
            }
            
            /* Formulaires mobile */
            .form-control, .form-select {
                font-size: 16px; /* Évite le zoom sur iOS */
                padding: 0.75rem;
            }
            
            .input-group .form-control {
                padding: 0.75rem;
            }
            
            /* Tables responsive */
            .table-responsive {
                border-radius: 8px;
                margin-bottom: 1rem;
            }
            
            .table {
                font-size: 0.875rem;
            }
            
            /* Navigation mobile */
            .mobile-topbar {
                padding: 0 16px;
            }
            
            /* Espacement des sections */
            .mb-4 {
                margin-bottom: 1.5rem !important;
            }
            
            .mb-3 {
                margin-bottom: 1rem !important;
            }
            
            /* KPIs et statistiques */
            .kpi-card {
                margin-bottom: 0.75rem;
            }
            
            .kpi-value {
                font-size: 1.25rem !important;
            }
            
            /* Modals mobile */
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 12px;
            }
            
            /* Alertes mobile */
            .alert {
                padding: 0.75rem;
                font-size: 0.875rem;
                margin-bottom: 1rem;
            }
            
            /* Filtres et formulaires de recherche */
            .filter-tabs {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .filter-tab {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            /* Grilles responsive */
            .row-cols-1.row-cols-sm-2.row-cols-lg-3 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }
            
            /* Pagination mobile */
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .pagination .page-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }
        }
        
        /* Optimisations desktop */
        @media (min-width: 992px) {
            /* Container desktop */
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            /* Page header desktop */
            .page-header {
                padding: 1.75rem 2.25rem !important;
                margin: 0 0 2rem 0 !important;
            }
            
            .page-title {
                font-size: 2.25rem !important;
                margin-bottom: 0.75rem;
            }
            
            .page-subtitle {
                font-size: 1rem !important;
            }
            
            /* Contenants de titres desktop */
            .card-header {
                padding: 1.25rem 1.5rem !important;
            }
            
            .section-header {
                padding: 1.25rem 1.5rem !important;
                margin-bottom: 1.25rem !important;
            }
            
            .content-header {
                padding: 1rem 1.25rem !important;
                margin-bottom: 1.25rem !important;
            }
            
            /* KPIs desktop */
            .kpi-header {
                gap: 1.25rem !important;
                margin-bottom: 1.25rem !important;
            }
            
            .kpi-title {
                font-size: 1.1rem !important;
            }
            
            .kpi-card {
                padding: 1.5rem !important;
            }
            
            /* Boutons dans page-headers desktop */
            .page-header .btn-sm {
                padding: 0.5rem 1rem !important;
                font-size: 0.875rem !important;
                border-radius: 12px !important;
            }
            
            .page-header .btn {
                padding: 0.75rem 1.5rem !important;
                font-size: 1rem !important;
            }
            
            /* Total amount desktop */
            .total-amount {
                padding: 1rem 1.5rem !important;
                border-radius: 8px !important;
            }
            
            .total-amount .h4 {
                font-size: 1.75rem !important;
            }
            
            /* Cartes et grilles desktop */
            .row.g-3 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1.25rem !important;
            }
            
            /* Boutons desktop */
            .btn {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            /* Formulaires desktop */
            .form-control, .form-select {
                font-size: 1rem;
                padding: 0.75rem;
            }
            
            .input-group .form-control {
                padding: 0.75rem;
            }
            
            /* Tables desktop */
            .table-responsive {
                border-radius: 12px;
                margin-bottom: 1.5rem;
            }
            
            .table {
                font-size: 1rem;
            }
            
            /* Espacement des sections desktop */
            .mb-4 {
                margin-bottom: 2rem !important;
            }
            
            .mb-3 {
                margin-bottom: 1.5rem !important;
            }
            
            /* KPIs et statistiques desktop */
            .kpi-card {
                margin-bottom: 1rem;
            }
            
            .kpi-value {
                font-size: 1.5rem !important;
            }
            
            /* Modals desktop */
            .modal-dialog {
                margin: 1.75rem auto;
            }
            
            .modal-content {
                border-radius: 16px;
            }
            
            /* Alertes desktop */
            .alert {
                padding: 1rem 1.25rem;
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }
            
            /* Filtres et formulaires de recherche desktop */
            .filter-tabs {
                flex-wrap: nowrap;
                gap: 0.75rem;
            }
            
            .filter-tab {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
            }
            
            /* Grilles responsive desktop */
            .row-cols-1.row-cols-sm-2.row-cols-lg-3 {
                --bs-gutter-x: 1rem;
                --bs-gutter-y: 1rem;
            }
            
            /* Pagination desktop */
            .pagination {
                justify-content: flex-start;
                flex-wrap: nowrap;
            }
            
            .pagination .page-link {
                padding: 0.75rem 1rem;
                font-size: 1rem;
            }
        }

        /* Très petits écrans */
        @media (max-width: 480px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .profile-content {
                padding: 1rem;
            }

            .profile-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.4rem;
            }

            .profile-name {
                font-size: 1.25rem;
            }

            .info-header, .stats-header, .remarks-header, .tithes-header {
                padding: 0.75rem;
            }

            .info-content, .stats-content, .remarks-list, .tithes-content {
                padding: 0.75rem;
            }

            .info-title, .stats-title, .remarks-title, .tithes-title {
                font-size: 1.1rem;
            }
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
        <!-- Barre jaune en haut -->
        <div style="height: 4px; background: linear-gradient(90deg, #f59e0b, #fbbf24);"></div>
        
        <div class="sidebar-header" style="padding: 20px 16px; text-align: center; border-bottom: 1px solid #e2e8f0; margin-bottom: 16px;">
            <img src="{{ asset('images/eglix-black.png') }}" alt="Eglix" style="height: 50px; margin-bottom: 15px;">
            
            @auth
            <div style="background-color: #000000; color: white; font-size: 0.9rem; padding: 12px; border-radius: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700;">
                <i class="bi bi-shop" style="color: #FFCC00; font-size: 16px;"></i>
                <span>{{ Auth::user()->church->name ?? 'Église' }}</span>
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
        @if(Auth::user() && Auth::user()->hasPermission('subscriptions.view'))
        <a href="{{ route('subscriptions.index') }}" class="{{ request()->is('subscriptions*') ? 'active' : '' }}" title="Abonnements"><i class="bi bi-calendar-check"></i><span class="sidebar-text">Abonnements</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('expenses.view'))
        <a href="{{ route('expenses.index') }}" class="{{ request()->is('expenses*') ? 'active' : '' }}" title="Dépenses"><i class="bi bi-credit-card"></i><span class="sidebar-text">Dépenses</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('projects.view'))
        <a href="{{ route('projects.index') }}" class="{{ request()->is('projects*') ? 'active' : '' }}" title="Projets"><i class="bi bi-kanban"></i><span class="sidebar-text">Projets</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('reports.view'))
        <a href="{{ route('reports.index') }}" class="{{ request()->is('reports*') ? 'active' : '' }}" title="Rapports"><i class="bi bi-graph-up"></i><span class="sidebar-text">Rapports</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('journal.view'))
        <a href="{{ route('journal.index') }}" class="{{ request()->is('journal*') ? 'active' : '' }}" title="Journal"><i class="bi bi-journal-text"></i><span class="sidebar-text">Journal</span></a>
        @endif
        @if(Auth::user() && Auth::user()->hasPermission('documents.view'))
        <a href="{{ route('documents.index') }}" class="{{ request()->is('documents*') || request()->is('document-folders*') ? 'active' : '' }}" title="Documents"><i class="bi bi-folder2-open"></i><span class="sidebar-text">Documents</span></a>
        @endif
        @if(Auth::user() && (Auth::user()->isChurchAdmin() || Auth::user()->hasPermission('administration.view')))
        <a href="{{ route('administration.index') }}" class="{{ request()->is('administration*') ? 'active' : '' }}" title="Administration"><i class="bi bi-person-badge"></i><span class="sidebar-text">Administration</span></a>
        @endif
        @if(Auth::user() && (Auth::user()->isChurchAdmin() || Auth::user()->hasPermission('users.view')))
        <a href="{{ route('user-management.index') }}" class="{{ request()->is('user-management*') ? 'active' : '' }}" title="Comptes"><i class="bi bi-people-fill"></i><span class="sidebar-text">Comptes</span></a>
        @endif
        
        <!-- Informations utilisateur -->
        <div style="margin-top: 20px; padding: 0 16px;">
            <div class="user-info-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 16px;">
                <div class="d-flex flex-column align-items-center mb-3">
                    <div class="user-avatar" style="width: 40px; height: 40px; background: #FFD700; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px;">
                        <i class="bi bi-person-fill" style="color: #000000; font-size: 1.2rem;"></i>
                    </div>
                    <div class="user-details text-center">
                        <h6 class="mb-1" style="font-weight: 600; color: #1e293b; font-size: 0.9rem;">{{ Auth::user()->name }}</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->email }}</small>
                    </div>
                </div>
                <div class="user-role" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shield-check me-2" style="color: #FFD700; font-size: 0.9rem;"></i>
                        <span style="font-size: 0.8rem; font-weight: 500; color: #64748b;">
                            @if(Auth::user()->isChurchAdmin())
                                Administrateur
                            @elseif(Auth::user()->hasPermission('users.view'))
                                Gestionnaire
                            @else
                                Utilisateur
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bouton de déconnexion -->
        <div style="padding: 0 16px;">
            <button type="button" class="btn btn-sm w-100" style="border-radius: 8px; font-size: 0.875rem; background-color: #000000 !important; border: 1px solid #000000 !important; color: #ffffff !important; font-weight: 700;" title="Se déconnecter" onclick="confirmLogout()">
                <i class="bi bi-box-arrow-right me-2" style="color: #ffffff !important;"></i>
                <span class="sidebar-text" style="color: #ffffff !important; font-weight: 700;">Déconnexion</span>
            </button>
        </div>
        
        <!-- Crédit développeur en bas de sidebar -->
        <div class="sidebar-footer" style="margin-top: auto; padding: 16px; border-top: 1px solid #e2e8f0; text-align: center;">
            <p class="text-muted mb-2 small" style="font-size: 0.75rem; color: #94a3b8;">Développé par</p>
            <a href="https://lafia.tech" target="_blank" rel="noopener" style="display: inline-block;">
                <img src="{{ asset('images/lafiatech.png') }}" alt="Lafiatech" style="height: 24px; opacity: 0.6; transition: opacity 0.2s;">
            </a>
        </div>
    </div>

    <main class="dashboard-main">
        @yield('content')
    </main>

    <!-- Global Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);">
                <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; padding: 20px 24px 16px;">
                    <h5 class="modal-title" style="font-weight: 600; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif;">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.2rem; color: #64748b;"></button>
                </div>
                <div class="modal-body" id="confirmModalMessage" style="padding: 20px 24px;">Êtes-vous sûr ?</div>
                <div class="modal-footer" style="border-top: 1px solid #e2e8f0; padding: 16px 24px 20px; gap: 12px;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500; padding: 8px 16px; border: 1px solid #e2e8f0; color: #64748b; background: transparent;">Annuler</button>
                    <button type="button" class="btn btn-danger" id="confirmModalOk" style="border-radius: 8px; font-weight: 700; padding: 8px 16px; background-color: #000000; border: 1px solid #000000; color: #ffffff;">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery et Select2 pour les listes déroulantes avec recherche -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Configuration AJAX pour inclure le token CSRF -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Fonction pour ajouter un loader aux boutons de soumission
        function addSubmitLoader(form) {
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('submit-loading');
                submitBtn.disabled = true;
                
                // Ajouter une classe au formulaire
                form.classList.add('form-loading');
            }
        }
        
        // Fonction pour ajouter un loader aux boutons spécifiques
        function addButtonLoader(button) {
            button.classList.add('btn-loading');
            button.disabled = true;
        }
        
        // Fonction pour retirer les loaders
        function removeLoaders() {
            document.querySelectorAll('.btn-loading, .submit-loading, .form-loading').forEach(element => {
                element.classList.remove('btn-loading', 'submit-loading', 'form-loading');
                element.disabled = false;
            });
        }
        
        // Ajouter des loaders automatiquement aux formulaires
        document.addEventListener('DOMContentLoaded', function() {
            // Tous les formulaires de création/modification
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    addSubmitLoader(this);
                });
            });
            
            // Boutons spécifiques avec classe .btn-submit
            document.querySelectorAll('.btn-submit').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    addButtonLoader(this);
                });
            });
        });
        
        // Fonction globale pour ajouter un loader aux liens de déconnexion
        function addLogoutLoader(element) {
            const originalText = element.innerHTML;
            element.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Déconnexion...';
            element.style.pointerEvents = 'none';
            
            // Créer un formulaire temporaire pour la déconnexion
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
        
        // Fonction de confirmation de déconnexion
        function confirmLogout() {
            const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
            const modalMessage = document.getElementById('confirmModalMessage');
            const modalOk = document.getElementById('confirmModalOk');
            
            // Personnaliser le modal pour la déconnexion
            modalMessage.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p class="mb-0">Êtes-vous sûr de vouloir vous déconnecter ?</p>
                    <small class="text-muted">Vous devrez vous reconnecter pour accéder à nouveau à l'application.</small>
                </div>
            `;
            
            modalOk.innerHTML = '<i class="bi bi-box-arrow-right me-2"></i>Se déconnecter';
            modalOk.className = 'btn';
            modalOk.style.cssText = 'border-radius: 8px; font-weight: 700; padding: 8px 16px; background-color: #000000; border: 1px solid #000000; color: #ffffff;';
            
            // Supprimer les anciens événements
            modalOk.replaceWith(modalOk.cloneNode(true));
            const newModalOk = document.getElementById('confirmModalOk');
            
            // Ajouter le nouvel événement
            newModalOk.addEventListener('click', function() {
                // Ajouter le loader au bouton
                const originalText = newModalOk.innerHTML;
                newModalOk.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Déconnexion...';
                newModalOk.disabled = true;
                
                // Créer un formulaire temporaire pour la déconnexion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            });
            
            modal.show();
        }
    </script>
    <style>
        /* Styles personnalisés pour Select2 */
        .select2-container {
            width: 100% !important;
        }
        
        .select2-container .select2-selection--single {
            height: 42px;
            padding: 6px 16px;
            font-size: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            background-color: #fff;
        }
        
        .select2-container .select2-selection--single:hover {
            border-color: #cbd5e1;
        }
        
        .select2-container.select2-container--focus .select2-selection--single,
        .select2-container.select2-container--open .select2-selection--single {
            border-color: #FFCC00;
            box-shadow: 0 0 0 3px rgba(255, 38, 0, 0.15);
        }
        
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 12px;
            top: 0;
        }
        
        .select2-container .select2-selection--single .select2-selection__arrow b {
            border-color: #94a3b8 transparent transparent transparent;
            border-width: 5px 5px 0 5px;
        }
        
        .select2-container.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #94a3b8 transparent;
            border-width: 0 5px 5px 5px;
        }
        
        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            padding-left: 0;
            color: #334155;
            padding-right: 30px;
        }
        
        .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8;
        }
        
        .select2-container .select2-results__option--highlighted[aria-selected] {
            background-color: #fef2f2;
            color: #374151;
            border-left: 3px solid #FFCC00;
        }
        
        .select2-container .select2-search--dropdown .select2-search__field {
            border-radius: 6px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        
        .select2-container .select2-search--dropdown .select2-search__field:focus {
            border-color: #FFCC00;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 38, 0, 0.15);
        }
        
        .select2-dropdown {
            border-color: #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 3px;
        }
        
        .select2-container .select2-results__option {
            padding: 10px 16px;
            transition: background-color 0.15s ease;
        }
        
        .select2-container .select2-results__option[aria-selected=true] {
            background-color: #ffffff;
            font-weight: 500;
        }
        
        .select2-container .select2-results__group {
            padding: 8px 16px;
            font-weight: 600;
            color: #64748b;
            background-color: #ffffff;
        }
        
        /* Icône de recherche dans le champ */
        .select2-container .select2-selection--single {
            position: relative;
        }
        
        .select2-container .select2-selection--single::before {
            content: "\F52A"; /* Code pour l'icône de recherche Bootstrap Icons */
            font-family: "bootstrap-icons";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 1;
            pointer-events: none;
            font-size: 14px;
        }
        
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 28px; /* Espace pour l'icône */
        }
        
        /* Styles améliorés pour les éléments Select2 */
        .select2-result-item {
            display: flex;
            align-items: center;
            padding: 6px 0;
        }
        
        .select2-result-item__text {
            flex: 1;
        }
        
        /* Style pour le champ de recherche dans le dropdown */
        .select2-search--dropdown {
            padding: 12px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
        
        /* Animation lors de l'ouverture du dropdown */
        .select2-dropdown {
            animation: select2FadeIn 0.2s ease-out;
        }
        
        @keyframes select2FadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Style pour le texte surligné dans les résultats */
        .select2-results__option .select2-highlighted {
            background-color: rgba(255, 38, 0, 0.1);
            font-weight: 500;
            padding: 0 2px;
            border-radius: 2px;
        }
        
        /* Correction pour les formulaires avec validation Bootstrap */
        .is-invalid + .select2-container .select2-selection--single {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }
        
        /* Styles spécifiques pour corriger l'apparence */
        .select2-container {
            z-index: 100;
        }
        
        .select2-container--open {
            z-index: 9999;
        }
        
        /* Style pour les options de résultats */
        .select2-results__option {
            position: relative;
            padding-left: 16px !important;
        }
        
        .select2-results__option[aria-selected=true]::before {
            content: "\F26B"; /* Code pour l'icône de coche Bootstrap Icons */
            font-family: "bootstrap-icons";
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #FFCC00;
            font-size: 14px;
        }

        /* Correction pour les listes déroulantes natives HTML */
        select, select option {
            color: #374151 !important;
            background-color: #ffffff !important;
        }
        
        select option:hover {
            background-color: #fef2f2 !important;
            color: #374151 !important;
        }
        
        select option:checked {
            background-color: #fef2f2 !important;
            color: #374151 !important;
        }
        
        /* Correction pour les listes déroulantes Bootstrap */
        .form-select option {
            color: #374151 !important;
            background-color: #ffffff !important;
        }
        
        .form-select option:hover {
            background-color: #fef2f2 !important;
            color: #374151 !important;
        }
        
        .form-select option:checked {
            background-color: #fef2f2 !important;
            color: #374151 !important;
        }
    </style>
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
    // Animations au scroll et d'entrée
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les éléments principaux
        const mainElements = document.querySelectorAll('.page-header, .card-soft, .btn');
        mainElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease-out';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Animation au scroll pour les éléments avec la classe animate-on-scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Animation pour les cartes de statistiques
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.8s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });

        // Animation pour les boutons d'action
        const actionButtons = document.querySelectorAll('.btn-action');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animation pour les modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                const modalDialog = this.querySelector('.modal-dialog');
                modalDialog.style.transform = 'scale(0.8)';
                modalDialog.style.opacity = '0';
                setTimeout(() => {
                    modalDialog.style.transition = 'all 0.3s ease-out';
                    modalDialog.style.transform = 'scale(1)';
                    modalDialog.style.opacity = '1';
                }, 10);
            });
        });

        // Animation pour les alertes
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transform = 'translateY(-20px)';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.transition = 'all 0.5s ease-out';
                alert.style.transform = 'translateY(0)';
                alert.style.opacity = '1';
            }, 100);
        });

        // Animation pour les tableaux
        const tables = document.querySelectorAll('.table-responsive');
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease-out';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 50);
            });
        });
    });

    // Fonction pour créer des animations personnalisées
    function animateElement(element, animationType = 'fadeIn', delay = 0) {
        setTimeout(() => {
            element.classList.add(animationType);
        }, delay);
    }

    // Fonction pour animer une liste d'éléments avec un délai
    function animateList(elements, animationType = 'slide-in-up', staggerDelay = 100) {
        elements.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add(animationType);
            }, index * staggerDelay);
        });
    }
    </script>
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

    <script>
    // Initialisation globale de Select2 pour toutes les listes déroulantes
    $(document).ready(function() {
        // Configuration commune pour Select2
        const select2Config = {
            placeholder: "Rechercher...",
            allowClear: false,
            width: '100%',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                },
                inputTooShort: function() {
                    return "Commencez à taper pour rechercher...";
                }
            },
            templateResult: formatResult,
            templateSelection: formatSelection,
            escapeMarkup: function(m) { return m; }
        };
        
        // Fonction pour formater les résultats avec mise en évidence
        function formatResult(result) {
            if (!result.id) return result.text;
            
            // Récupérer le terme recherché
            const term = $('.select2-search__field').val();
            let text = result.text;
            
            // Si un terme de recherche existe, le mettre en surbrillance
            if (term && term.length > 0) {
                const regex = new RegExp('(' + term.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&') + ')', 'gi');
                text = text.replace(regex, '<span class="select2-highlighted">$1</span>');
            }
            
            // Ajouter des classes pour le style
            let $result = $(
                '<div class="select2-result-item">' +
                    '<span class="select2-result-item__text">' + text + '</span>' +
                '</div>'
            );
            
            return $result;
        }
        
        // Fonction pour formater la sélection
        function formatSelection(result) {
            if (!result.id) return result.text;
            return result.text;
        }
        
        // Appliquer Select2 à tous les select sauf ceux avec la classe .no-select2
        $('select:not(.no-select2)').each(function() {
            // Vérifier si ce n'est pas déjà un Select2
            if (!$(this).hasClass('select2-hidden-accessible')) {
                // Récupérer le placeholder depuis l'option vide ou le premier élément
                const firstOption = $(this).find('option:first');
                const placeholder = firstOption.text() || "Rechercher...";
                
                // Créer une configuration spécifique pour ce select
                const config = {
                    ...select2Config,
                    placeholder: placeholder,
                    allowClear: false
                };
                
                $(this).select2(config);
            }
        });

        // Réinitialiser Select2 après les changements dynamiques du DOM
        $(document).on('DOMNodeInserted', function(e) {
            var target = $(e.target);
            if (target.find('select').length > 0) {
                setTimeout(function() {
                    target.find('select:not(.select2-hidden-accessible):not(.no-select2)').each(function() {
                        // Récupérer le placeholder depuis l'option vide ou le premier élément
                        const firstOption = $(this).find('option:first');
                        const placeholder = firstOption.text() || "Rechercher...";
                        
                        // Créer une configuration spécifique pour ce select
                        const config = {
                            ...select2Config,
                            placeholder: placeholder,
                            allowClear: false
                        };
                        
                        $(this).select2(config);
                    });
                }, 100);
            }
        });
    });

    
    });
    </script>
    
    <!-- Section pour les scripts spécifiques aux pages -->
    @yield('script')
</body>
 </html>


