# Sécurité de l'application Eglix

Ce document décrit les mesures de sécurité mises en place pour protéger l'application Eglix, en particulier pour les formulaires et les connexions en production.

## Configuration HTTPS

L'application est configurée pour utiliser HTTPS en production afin de sécuriser toutes les communications entre le navigateur et le serveur. Cela est particulièrement important pour les formulaires qui transmettent des données sensibles.

### Mesures implémentées

1. **Forçage HTTPS** : Toutes les URL générées par l'application utilisent le schéma HTTPS en production.
   - Implémenté dans `AppServiceProvider::boot()` via `$url->forceScheme('https')`

2. **Cookies sécurisés** : Tous les cookies de session sont marqués comme sécurisés en production.
   - Configuration via `config(['session.secure' => true])`
   - Attribut `SameSite=Lax` pour protéger contre les attaques CSRF

3. **En-têtes de sécurité** : Des en-têtes HTTP de sécurité sont ajoutés à toutes les réponses.
   - Middleware `SecureHeaders` appliqué globalement
   - Inclut HSTS, CSP, X-Content-Type-Options, etc.

4. **Configuration des proxys** : L'application est configurée pour faire confiance aux en-têtes de proxy en production.
   - Important pour les déploiements derrière des CDN ou des services comme Render
   - Configuration via `trustedproxy.proxies` et `trustedproxy.headers`

## Protection CSRF

La protection contre les attaques Cross-Site Request Forgery (CSRF) est activée par défaut dans Laravel et renforcée dans notre application.

### Mesures implémentées

1. **Token CSRF** : Tous les formulaires incluent automatiquement un token CSRF.
   - Meta tag `<meta name="csrf-token" content="{{ csrf_token() }}">` ajouté au layout principal
   - Configuration AJAX pour inclure le token dans toutes les requêtes

2. **Validation des tokens** : Middleware Laravel `VerifyCsrfToken` vérifie tous les tokens pour les requêtes POST, PUT, DELETE, etc.

3. **SameSite Cookies** : Configuration des cookies de session avec `SameSite=Lax` pour limiter les requêtes cross-origin.

## Autres mesures de sécurité

1. **Content Security Policy (CSP)** : Limite les sources de contenu pour prévenir les attaques XSS.
   - Configuration dans le middleware `SecureHeaders`

2. **HTTP Strict Transport Security (HSTS)** : Force les navigateurs à utiliser HTTPS pour l'application.
   - En-tête `Strict-Transport-Security: max-age=31536000; includeSubDomains`

3. **X-Content-Type-Options** : Empêche le MIME-sniffing des réponses.
   - En-tête `X-Content-Type-Options: nosniff`

4. **X-Frame-Options** : Protège contre le clickjacking.
   - En-tête `X-Frame-Options: SAMEORIGIN`

## Configuration en production

Pour que ces mesures de sécurité fonctionnent correctement en production, assurez-vous que :

1. La variable d'environnement `APP_ENV` est définie sur `production`
2. Le site est accessible via HTTPS
3. Les certificats SSL sont correctement configurés
4. Les en-têtes de proxy sont correctement transmis (si derrière un CDN ou un service comme Render)

## Résolution des problèmes

Si vous rencontrez des avertissements de sécurité dans les formulaires :

1. Vérifiez que le site est bien accessible en HTTPS
2. Assurez-vous que tous les assets (CSS, JS, images) sont chargés en HTTPS
3. Vérifiez que les tokens CSRF sont correctement générés et validés
4. Consultez les logs du serveur pour détecter d'éventuelles erreurs liées à la sécurité
