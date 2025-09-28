<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Définir les métadonnées SEO par défaut
        $seoData = [
            'pageTitle' => 'Eglix - Application de gestion d\'église',
            'pageDescription' => 'Solution complète de gestion d\'église. Gérez vos membres, finances, dîmes, offrandes, événements et rapports avec une interface moderne et intuitive.',
            'pageImage' => asset('images/eglix.png'),
            'pageKeywords' => 'gestion église, logiciel église, application église, gestion membres église, finances église, dîmes, offrandes, gestion paroissiale, logiciel paroisse, application paroisse, gestion communauté religieuse'
        ];

        // Personnaliser selon la route
        $routeName = $request->route()?->getName();
        
        switch ($routeName) {
            case 'login':
                $seoData['pageTitle'] = 'Connexion - Eglix';
                $seoData['pageDescription'] = 'Connectez-vous à votre compte Eglix pour accéder à votre tableau de bord de gestion d\'église.';
                break;
                
            case 'register':
                $seoData['pageTitle'] = 'Inscription - Eglix';
                $seoData['pageDescription'] = 'Créez votre compte Eglix et commencez à gérer votre église efficacement.';
                break;
                
            case 'members.index':
                $seoData['pageTitle'] = 'Gestion des membres - Eglix';
                $seoData['pageDescription'] = 'Gérez efficacement les membres de votre église avec Eglix. Ajoutez, modifiez et suivez les informations de vos fidèles.';
                break;
                
            case 'tithes.index':
                $seoData['pageTitle'] = 'Gestion des dîmes - Eglix';
                $seoData['pageDescription'] = 'Suivez et gérez les dîmes de votre église avec Eglix. Interface intuitive pour la gestion financière religieuse.';
                break;
                
            case 'offerings.index':
                $seoData['pageTitle'] = 'Gestion des offrandes - Eglix';
                $seoData['pageDescription'] = 'Organisez et suivez les offrandes de votre église avec Eglix. Gestion complète des contributions financières.';
                break;
                
            case 'donations.index':
                $seoData['pageTitle'] = 'Gestion des dons - Eglix';
                $seoData['pageDescription'] = 'Gérez les dons et contributions de votre église avec Eglix. Suivi détaillé des générosités.';
                break;
                
            case 'expenses.index':
                $seoData['pageTitle'] = 'Gestion des dépenses - Eglix';
                $seoData['pageDescription'] = 'Contrôlez les dépenses de votre église avec Eglix. Budget et suivi des coûts religieux.';
                break;
                
            case 'projects.index':
                $seoData['pageTitle'] = 'Gestion des projets - Eglix';
                $seoData['pageDescription'] = 'Planifiez et suivez les projets de votre église avec Eglix. Organisation des activités religieuses.';
                break;
                
            case 'documents.index':
                $seoData['pageTitle'] = 'Gestion des documents - Eglix';
                $seoData['pageDescription'] = 'Organisez et partagez les documents de votre église avec Eglix. Archivage numérique sécurisé.';
                break;
                
            case 'administration.index':
                $seoData['pageTitle'] = 'Administration - Eglix';
                $seoData['pageDescription'] = 'Administrez votre église avec Eglix. Configuration et gestion des paramètres de votre communauté religieuse.';
                break;
        }

        // Partager les données SEO avec toutes les vues
        View::share($seoData);

        return $next($request);
    }
}
