<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(UrlGenerator $url)
    {
        // Configuration de la pagination
        Paginator::useBootstrap();
        
        // Configurer le resolver de vue pour la pagination
        LengthAwarePaginator::viewFactoryResolver(function () {
            return app('view');
        });
        
        // Configuration HTTPS pour la sécurité des formulaires en production
        if (env('APP_ENV') === 'production') {
            // Forcer HTTPS pour tous les assets et URLs
            $url->forceScheme('https');
            
            // Configurer les cookies sécurisés
            config([
                'session.secure' => true,
                'session.same_site' => 'lax',
                'session.http_only' => true,
                'session.cookie_secure' => true,
            ]);
            
            // Configurer les cookies de session sécurisés
            if (config('session.driver') === 'cookie') {
                config([
                    'session.cookie_secure' => true,
                    'session.cookie_http_only' => true,
                    'session.cookie_same_site' => 'lax',
                ]);
            }
        }
        
        // Ajouter le token CSRF à tous les formulaires
        \Illuminate\Support\Facades\Blade::directive('csrf_meta', function () {
            return '<?php echo \'<meta name="csrf-token" content="\' . csrf_token() . \'">\'; ?>';
        });
    }
    
    /**
     * Register any application services.
     */
    public function register()
    {
        // Configuration des proxies désactivée pour éviter les conflits
        // Les proxies sont gérés automatiquement par Render
    }
}
