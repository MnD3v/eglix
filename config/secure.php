<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Force HTTPS
    |--------------------------------------------------------------------------
    |
    | Cette option force l'utilisation de HTTPS pour toutes les URL générées.
    | DÉSACTIVÉ pour éviter les boucles de redirection avec Render.
    | Les redirections HTTPS sont gérées par le serveur/proxy.
    |
    */
    'force_https' => false,

    /*
    |--------------------------------------------------------------------------
    | Secure Cookies
    |--------------------------------------------------------------------------
    |
    | Cette option force l'utilisation de cookies sécurisés (HTTPS uniquement).
    | DÉSACTIVÉ pour éviter les conflits avec Render.
    |
    */
    'secure_cookies' => false,

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
