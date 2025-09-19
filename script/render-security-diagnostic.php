<?php

// Script de diagnostic de sécurité pour Render
// À exécuter en production pour vérifier la configuration

echo "🔒 DIAGNOSTIC DE SÉCURITÉ - RENDER PRODUCTION\n";
echo "===============================================\n\n";

// 1. Vérifier l'environnement
echo "1. ENVIRONNEMENT:\n";
echo "   APP_ENV: " . env('APP_ENV', 'non défini') . "\n";
echo "   APP_DEBUG: " . (env('APP_DEBUG', false) ? 'true' : 'false') . "\n";
echo "   APP_URL: " . env('APP_URL', 'non défini') . "\n";
echo "   HTTPS forcé: " . (config('secure.force_https') ? 'OUI' : 'NON') . "\n\n";

// 2. Vérifier la configuration HTTPS
echo "2. CONFIGURATION HTTPS:\n";
echo "   URL Generator Scheme: " . app('url')->getScheme() . "\n";
echo "   Request Secure: " . (request()->secure() ? 'OUI' : 'NON') . "\n";
echo "   Request Scheme: " . request()->getScheme() . "\n";
echo "   Request Host: " . request()->getHost() . "\n";
echo "   Request Port: " . request()->getPort() . "\n\n";

// 3. Vérifier les cookies sécurisés
echo "3. CONFIGURATION DES COOKIES:\n";
echo "   Session Secure: " . (config('session.secure') ? 'OUI' : 'NON') . "\n";
echo "   Session HTTP Only: " . (config('session.http_only') ? 'OUI' : 'NON') . "\n";
echo "   Session Same Site: " . config('session.same_site', 'non défini') . "\n";
echo "   Cookie Secure: " . (config('session.cookie_secure') ? 'OUI' : 'NON') . "\n\n";

// 4. Vérifier les headers de sécurité
echo "4. HEADERS DE SÉCURITÉ:\n";
$headers = [
    'Strict-Transport-Security',
    'X-Frame-Options',
    'X-Content-Type-Options',
    'X-XSS-Protection',
    'Content-Security-Policy'
];

foreach ($headers as $header) {
    $value = request()->header($header);
    echo "   $header: " . ($value ? $value : 'MANQUANT') . "\n";
}
echo "\n";

// 5. Vérifier les proxies
echo "5. CONFIGURATION DES PROXIES:\n";
echo "   Trusted Proxies: " . config('trustedproxy.proxies', 'non défini') . "\n";
echo "   Trusted Headers: " . implode(', ', config('trustedproxy.headers', [])) . "\n";
echo "   X-Forwarded-Proto: " . request()->header('X-Forwarded-Proto', 'non défini') . "\n";
echo "   X-Forwarded-For: " . request()->header('X-Forwarded-For', 'non défini') . "\n\n";

// 6. Vérifier CSRF
echo "6. CONFIGURATION CSRF:\n";
echo "   CSRF Cookie Secure: " . (config('session.cookie_secure') ? 'OUI' : 'NON') . "\n";
echo "   CSRF Token: " . (csrf_token() ? 'PRÉSENT' : 'MANQUANT') . "\n";
echo "   CSRF Token Length: " . strlen(csrf_token()) . " caractères\n\n";

// 7. Recommandations
echo "7. RECOMMANDATIONS POUR RENDER:\n";
echo "   Variables d'environnement à ajouter dans Render:\n";
echo "   - APP_ENV=production\n";
echo "   - APP_DEBUG=false\n";
echo "   - APP_URL=https://eglix.lafia.tech\n";
echo "   - FORCE_HTTPS=true\n";
echo "   - SECURE_COOKIES=true\n";
echo "   - SESSION_SECURE_COOKIE=true\n";
echo "   - SESSION_HTTP_ONLY=true\n";
echo "   - SESSION_SAME_SITE=lax\n";
echo "   - CSRF_COOKIE_SECURE=true\n";
echo "   - CSRF_COOKIE_HTTP_ONLY=true\n";
echo "   - CSRF_COOKIE_SAME_SITE=lax\n\n";

echo "===============================================\n";
echo "🔒 DIAGNOSTIC TERMINÉ\n";
echo "Si des valeurs sont 'NON' ou 'MANQUANT', configurez les variables Render.\n";
