{{-- 
    Exemple d'utilisation du composant AppBar
    Ce fichier montre comment utiliser le composant appbar dans différentes sections
--}}

{{-- Exemple 1: Administration --}}
@include('components.appbar', [
    'title' => 'Gestion des Fonctions',
    'subtitle' => 'Gérez les fonctions d\'administration et leurs permissions',
    'icon' => 'bi-person-badge',
    'color' => 'administration',
    'actions' => [
        [
            'type' => 'secondary',
            'url' => route('administration-function-types.index'),
            'icon' => 'bi-tags',
            'label' => 'Types de fonctions'
        ],
        [
            'type' => 'primary',
            'url' => route('administration.create'),
            'icon' => 'bi-person-plus',
            'label' => 'Nouvelle Fonction'
        ]
    ]
])

{{-- Exemple 2: Comptes/Users --}}
@include('components.appbar', [
    'title' => 'Gestion des Comptes',
    'subtitle' => 'Gérez les utilisateurs et leurs permissions',
    'icon' => 'bi-people-fill',
    'color' => 'accounts',
    'actions' => [
        [
            'type' => 'primary',
            'url' => route('user-management.create'),
            'icon' => 'bi-person-plus',
            'label' => 'Nouvel Utilisateur'
        ]
    ]
])

{{-- Exemple 3: Membres --}}
@include('components.appbar', [
    'title' => 'Membres',
    'subtitle' => 'Gérez les membres de votre église',
    'icon' => 'bi-people',
    'color' => 'members',
    'actions' => [
        [
            'type' => 'primary',
            'url' => route('members.create'),
            'icon' => 'bi-person-plus',
            'label' => 'Nouveau membre'
        ]
    ]
])

{{-- Exemple 4: Finances --}}
@include('components.appbar', [
    'title' => 'Gestion Financière',
    'subtitle' => 'Suivez les dîmes, offrandes et dépenses',
    'icon' => 'bi-cash-stack',
    'color' => 'finance',
    'actions' => [
        [
            'type' => 'secondary',
            'url' => route('tithes.index'),
            'icon' => 'bi-cash-coin',
            'label' => 'Dîmes'
        ],
        [
            'type' => 'secondary',
            'url' => route('offerings.index'),
            'icon' => 'bi-gift',
            'label' => 'Offrandes'
        ],
        [
            'type' => 'primary',
            'url' => route('expenses.create'),
            'icon' => 'bi-plus',
            'label' => 'Nouvelle Dépense'
        ]
    ]
])

{{-- Exemple 5: Événements --}}
@include('components.appbar', [
    'title' => 'Événements',
    'subtitle' => 'Organisez et gérez les événements de l\'église',
    'icon' => 'bi-calendar-event',
    'color' => 'events',
    'actions' => [
        [
            'type' => 'primary',
            'url' => route('events.create'),
            'icon' => 'bi-plus',
            'label' => 'Nouvel Événement'
        ]
    ]
])

{{-- Exemple 6: Rapports --}}
@include('components.appbar', [
    'title' => 'Rapports Avancés',
    'subtitle' => 'Analysez les données financières et générez des rapports',
    'icon' => 'bi-graph-up',
    'color' => 'reports',
    'actions' => [
        [
            'type' => 'secondary',
            'url' => route('reports.index'),
            'icon' => 'bi-file-earmark-text',
            'label' => 'Rapports Standards'
        ],
        [
            'type' => 'primary',
            'url' => route('advanced-reports.dashboard'),
            'icon' => 'bi-graph-up',
            'label' => 'Rapports Avancés'
        ]
    ]
])
