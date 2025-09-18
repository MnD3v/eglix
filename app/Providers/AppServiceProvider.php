<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(UrlGenerator $url)
    {
        // Forcer HTTPS en production ou si configuré
        if (env('APP_ENV') == 'production' || config('secure.force_https')) {
            $url->forceScheme('https');
            
            // Forcer les cookies sécurisés
            if (config('secure.secure_cookies', true)) {
                config([
                    'session.secure' => true,
                    'session.same_site' => 'lax',
                    'session.http_only' => true,
                ]);
            }
            
            // Ajouter le token CSRF à tous les formulaires
            \Illuminate\Support\Facades\Blade::directive('csrf_meta', function () {
                return '<?php echo \'<meta name="csrf-token" content="\' . csrf_token() . \'">\'; ?>';
            });
        }
    }
    
    /**
     * Register any application services.
     */
    public function register()
    {
        // Configurer l'application pour faire confiance aux proxys en production
        if (env('APP_ENV') == 'production' || config('secure.force_https')) {
            // Utiliser la configuration du fichier secure.php
            $trustedProxies = config('secure.trusted_proxies', '*');
            $trustedHeaders = config('secure.trusted_headers', [
                'X-Forwarded-For',
                'X-Forwarded-Host',
                'X-Forwarded-Port',
                'X-Forwarded-Proto',
            ]);
            
            // Définir les en-têtes de proxy à faire confiance
            $this->app['config']->set('trustedproxy.proxies', $trustedProxies === '*' ? ['*'] : explode(',', $trustedProxies));
            $this->app['config']->set('trustedproxy.headers', $trustedHeaders);
        }
    }
}
