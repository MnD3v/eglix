<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Force HTTPS
    |--------------------------------------------------------------------------
    |
    | Cette option force l'utilisation de HTTPS pour toutes les URL générées.
    | Activé en production pour la sécurité des formulaires.
    |
    */
    'force_https' => env('APP_ENV') === 'production',

    /*
    |--------------------------------------------------------------------------
    | Secure Cookies
    |--------------------------------------------------------------------------
    |
    | Cette option force l'utilisation de cookies sécurisés (HTTPS uniquement).
    | Activé en production pour la sécurité des sessions.
    |
    */
    'secure_cookies' => env('APP_ENV') === 'production',

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | Les adresses IP des proxies de confiance (comme Cloudflare, Render, etc.)
    | '*' fait confiance à tous les proxies, ce qui est utile derrière un CDN.
    |
    */
    'trusted_proxies' => env('TRUSTED_PROXIES', '*'),

    /*
    |--------------------------------------------------------------------------
    | Headers de confiance
    |--------------------------------------------------------------------------
    |
    | Les en-têtes HTTP à faire confiance pour les informations de proxy.
    |
    */
    'trusted_headers' => [
        'X-Forwarded-For',
        'X-Forwarded-Host',
        'X-Forwarded-Port',
        'X-Forwarded-Proto',
    ],
];
