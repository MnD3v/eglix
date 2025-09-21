<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        
        // Auto-correction des colonnes subscription au démarrage
        $this->autoFixSubscriptionColumns();
    }
    
    /**
     * Register any application services.
     */
    public function register()
    {
        // Configuration des proxies désactivée pour éviter les conflits
        // Les proxies sont gérés automatiquement par Render
    }
    
    /**
     * Auto-correction des colonnes subscription manquantes
     */
    private function autoFixSubscriptionColumns()
    {
        try {
            // Vérifier si les colonnes subscription existent
            $columns = DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'churches' 
                AND column_name IN ('subscription_status', 'subscription_end_date', 'subscription_amount')
            ");
            
            $existingColumns = array_column($columns, 'column_name');
            
            // Si les colonnes critiques manquent, les ajouter
            if (count($existingColumns) < 3) {
                Log::info('🔧 Auto-correction des colonnes subscription manquantes...');
                
                // Ajouter subscription_start_date si elle n'existe pas
                if (!in_array('subscription_start_date', $existingColumns)) {
                    DB::statement('ALTER TABLE churches ADD COLUMN subscription_start_date DATE NULL');
                    Log::info('✅ Colonne subscription_start_date ajoutée');
                }
                
                // Ajouter subscription_end_date si elle n'existe pas
                if (!in_array('subscription_end_date', $existingColumns)) {
                    DB::statement('ALTER TABLE churches ADD COLUMN subscription_end_date DATE NULL');
                    Log::info('✅ Colonne subscription_end_date ajoutée');
                }
                
                // Ajouter subscription_status si elle n'existe pas
                if (!in_array('subscription_status', $existingColumns)) {
                    DB::statement("ALTER TABLE churches ADD COLUMN subscription_status VARCHAR(20) DEFAULT 'active'");
                    Log::info('✅ Colonne subscription_status ajoutée');
                }
                
                // Ajouter subscription_amount si elle n'existe pas
                if (!in_array('subscription_amount', $existingColumns)) {
                    DB::statement('ALTER TABLE churches ADD COLUMN subscription_amount DECIMAL(10,2) NULL');
                    Log::info('✅ Colonne subscription_amount ajoutée');
                }
                
                // Ajouter les autres colonnes optionnelles
                $optionalColumns = [
                    'subscription_currency' => "VARCHAR(3) DEFAULT 'XOF'",
                    'subscription_plan' => "VARCHAR(50) DEFAULT 'basic'",
                    'subscription_notes' => 'TEXT NULL',
                    'payment_reference' => 'VARCHAR(255) NULL',
                    'payment_date' => 'DATE NULL'
                ];
                
                foreach ($optionalColumns as $column => $definition) {
                    $checkColumn = DB::select("
                        SELECT column_name 
                        FROM information_schema.columns 
                        WHERE table_name = 'churches' AND column_name = '$column'
                    ");
                    
                    if (empty($checkColumn)) {
                        DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
                        Log::info("✅ Colonne $column ajoutée");
                    }
                }
                
                Log::info('🎉 Auto-correction des colonnes subscription terminée!');
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'auto-correction des colonnes subscription: ' . $e->getMessage());
        }
    }
}
