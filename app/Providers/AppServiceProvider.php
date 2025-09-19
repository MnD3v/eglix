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
        
        // Configuration HTTPS désactivée pour éviter les boucles de redirection
        // Les redirections HTTPS sont gérées par le serveur/proxy (Render)
        
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
