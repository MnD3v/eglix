<!-- Métadonnées SEO optimisées -->
<meta name="description" content="Eglix - Solution complète de gestion d'église. Gérez vos membres, finances, dîmes, offrandes, événements et rapports avec une interface moderne et intuitive.">
<meta name="keywords" content="gestion église, logiciel église, application église, gestion membres église, finances église, dîmes, offrandes, gestion paroissiale, logiciel paroisse, application paroisse, gestion communauté religieuse">
<meta name="author" content="Lafiatech">
<meta name="robots" content="index, follow">
<meta name="language" content="fr">
<meta name="revisit-after" content="7 days">
<meta name="rating" content="general">

<!-- Open Graph / Facebook optimisé -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://eglix.lafia.tech{{ request()->getRequestUri() }}">
<meta property="og:title" content="{{ $pageTitle ?? 'Eglix - Application de gestion d\'église' }}">
<meta property="og:description" content="{{ $pageDescription ?? 'Solution complète de gestion d\'église. Gérez vos membres, finances, dîmes, offrandes, événements et rapports avec une interface moderne et intuitive.' }}">
<meta property="og:image" content="{{ $pageImage ?? 'https://eglix.lafia.tech/images/eglix.png' }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="Eglix">
<meta property="og:locale" content="fr_FR">

<!-- Twitter Cards optimisées -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://eglix.lafia.tech{{ request()->getRequestUri() }}">
<meta property="twitter:title" content="{{ $pageTitle ?? 'Eglix - Application de gestion d\'église' }}">
<meta property="twitter:description" content="{{ $pageDescription ?? 'Solution complète de gestion d\'église. Gérez vos membres, finances, dîmes, offrandes, événements et rapports avec une interface moderne et intuitive.' }}">
<meta property="twitter:image" content="{{ $pageImage ?? 'https://eglix.lafia.tech/images/eglix.png' }}">
<meta property="twitter:creator" content="@lafiatech">
<meta property="twitter:site" content="@lafiatech">

<!-- Favicon et icônes -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/eglix.png') }}">

<!-- Canonical URL -->
<link rel="canonical" href="https://eglix.lafia.tech{{ request()->getRequestUri() }}">

<!-- Données structurées JSON-LD temporairement désactivées -->
