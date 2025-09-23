<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

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
        
        // Vérifier si nous ne sommes pas en phase de build
        if (!$this->isBuildPhase()) {
            // Auto-correction des colonnes subscription au démarrage
            $this->autoFixSubscriptionColumns();
            
            // Auto-correction des colonnes manquantes dans les tables
            $this->autoFixMissingColumns();
            
            // Correction du stockage des sessions pour Render
            $this->fixSessionStorage();
            
            // Enregistrer les politiques d'autorisation
            $this->registerPolicies();
            
            // Déclencheur automatique pour Laravel Cloud
            $this->triggerLaravelCloudDeployment();
        }
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
    
    /**
     * Auto-correction des colonnes manquantes dans toutes les tables
     */
    private function autoFixMissingColumns()
    {
        try {
            Log::info('🔧 Vérification des colonnes manquantes...');
            
            // Définir les colonnes critiques par table
            $criticalColumns = [
                'expenses' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'donations' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'offerings' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'tithes' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'projects' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'members' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL',
                    'function' => 'VARCHAR(255) NULL'
                ],
                'journal_entries' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'administration_function_types' => [
                    'slug' => 'VARCHAR(255) NULL',
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'administration_functions' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'offering_types' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'church_events' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'services' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'service_roles' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'service_assignments' => [
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ]
            ];
            
            foreach ($criticalColumns as $table => $columns) {
                // Vérifier si la table existe
                try {
                    DB::select("SELECT 1 FROM $table LIMIT 1");
                } catch (\Exception $e) {
                    Log::info("⚠️ Table $table n'existe pas, ignorée");
                    continue;
                }
                
                foreach ($columns as $column => $definition) {
                    // Vérifier si la colonne existe
                    $checkColumn = DB::select("
                        SELECT column_name 
                        FROM information_schema.columns 
                        WHERE table_name = '$table' AND column_name = '$column'
                    ");
                    
                    if (empty($checkColumn)) {
                        // Gérer les deadlocks avec retry
                        $maxRetries = 3;
                        $retryCount = 0;
                        
                        while ($retryCount < $maxRetries) {
                            try {
                                DB::statement("ALTER TABLE $table ADD COLUMN $column $definition");
                                Log::info("✅ Colonne $column ajoutée à la table $table");
                                break;
                            } catch (\Exception $e) {
                                $retryCount++;
                                
                                if (strpos($e->getMessage(), 'Deadlock found') !== false && $retryCount < $maxRetries) {
                                    Log::warning("⚠️ Deadlock détecté pour $column dans $table, tentative $retryCount/$maxRetries...");
                                    sleep(rand(1, 3));
                                    continue;
                                }
                                
                                Log::error("❌ Erreur lors de l'ajout de $column à $table: " . $e->getMessage());
                                break;
                            }
                        }
                    }
                }
            }
            
            Log::info('🎉 Vérification des colonnes manquantes terminée!');
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'auto-correction des colonnes: ' . $e->getMessage());
        }
    }
    
    /**
     * Correction du stockage des sessions pour Render et Laravel Cloud
     */
    private function fixSessionStorage()
    {
        try {
            // Sur Render, forcer l'utilisation des sessions en base de données
            if (env('RENDER', false) || env('APP_ENV') === 'production') {
                $platform = env('RENDER', false) ? 'Render' : 'Production';
                Log::info("🔧 Configuration des sessions pour $platform...");
                
                // Vérifier si la table sessions existe
                try {
                    DB::select('SELECT 1 FROM sessions LIMIT 1');
                    Log::info('✅ Table sessions existe');
                } catch (\Exception $e) {
                    Log::info('❌ Table sessions n\'existe pas, création...');
                    
                    // Créer la table sessions seulement si elle n'existe pas
                    DB::statement('
                        CREATE TABLE IF NOT EXISTS sessions (
                            id VARCHAR(255) PRIMARY KEY,
                            user_id BIGINT NULL,
                            ip_address VARCHAR(45) NULL,
                            user_agent TEXT NULL,
                            payload TEXT NOT NULL,
                            last_activity INTEGER NOT NULL
                        )
                    ');
                    
                    Log::info('✅ Table sessions créée');
                }
                
                // Forcer la configuration des sessions
                config(['session.driver' => 'database']);
                config(['session.table' => 'sessions']);
                config(['session.lifetime' => 120]);
                config(['session.expire_on_close' => false]);
                config(['session.secure' => true]);
                config(['session.http_only' => true]);
                config(['session.same_site' => 'lax']);
                
                Log::info("✅ Configuration des sessions mise à jour pour $platform");
            }
            
            // Sur Laravel Cloud, seulement configurer les sessions sans créer la table
            if (env('LARAVEL_CLOUD', false)) {
                Log::info('🔧 Configuration des sessions pour Laravel Cloud...');
                
                // Vérifier si la table sessions existe
                try {
                    DB::select('SELECT 1 FROM sessions LIMIT 1');
                    Log::info('✅ Table sessions existe');
                } catch (\Exception $e) {
                    Log::info('ℹ️ Table sessions n\'existe pas - sera créée par les migrations');
                }
                
                // Configurer les sessions sans créer la table
                config(['session.driver' => 'database']);
                config(['session.table' => 'sessions']);
                config(['session.lifetime' => 120]);
                config(['session.expire_on_close' => false]);
                config(['session.secure' => true]);
                config(['session.http_only' => true]);
                config(['session.same_site' => 'lax']);
                
                Log::info('✅ Configuration des sessions mise à jour pour Laravel Cloud');
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la correction des sessions: ' . $e->getMessage());
        }
    }
    
    /**
     * Enregistrer les politiques d'autorisation
     */
    private function registerPolicies()
    {
        try {
            Log::info('🔧 Enregistrement des politiques d\'autorisation...');
            
            // Enregistrer les politiques pour les documents
            Gate::policy(\App\Models\Document::class, \App\Policies\DocumentPolicy::class);
            Gate::policy(\App\Models\DocumentFolder::class, \App\Policies\DocumentFolderPolicy::class);
            
            Log::info('✅ Politiques d\'autorisation enregistrées');
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'enregistrement des politiques: ' . $e->getMessage());
        }
    }
    
    /**
     * Déclencheur automatique pour Laravel Cloud
     */
    private function triggerLaravelCloudDeployment()
    {
        try {
            // Vérifier si nous sommes sur Laravel Cloud
            if (env('LARAVEL_CLOUD', false) || env('APP_ENV') === 'production') {
                Log::info('🚀 Déclenchement automatique des corrections Laravel Cloud...');
                
                // Tester la connexion PostgreSQL d'abord
                $this->testPostgreSQLConnection();
                
                // Exécuter les corrections de déploiement
                $this->executeLaravelCloudFixes();
                
                Log::info('✅ Corrections Laravel Cloud exécutées automatiquement');
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors du déclenchement Laravel Cloud: ' . $e->getMessage());
        }
    }
    
    /**
     * Exécuter les corrections Laravel Cloud
     */
    private function executeLaravelCloudFixes()
    {
        try {
            Log::info('🔧 Exécution des corrections Laravel Cloud...');
            
            // 1. Vérifier et corriger la table sessions
            $this->fixSessionsTableForLaravelCloud();
            
            // 2. Vérifier et corriger les colonnes subscription
            $this->fixSubscriptionColumnsForLaravelCloud();
            
            // 3. Vérifier et corriger les colonnes manquantes
            $this->fixMissingColumnsForLaravelCloud();
            
            // 4. Optimiser l'application
            $this->optimizeApplicationForLaravelCloud();
            
            Log::info('✅ Toutes les corrections Laravel Cloud terminées');
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors des corrections Laravel Cloud: ' . $e->getMessage());
        }
    }
    
    /**
     * Corriger la table sessions pour Laravel Cloud
     */
    private function fixSessionsTableForLaravelCloud()
    {
        try {
            Log::info('🔧 Vérification de la table sessions pour Laravel Cloud...');
            
            // Vérifier si la table sessions existe
            if (Schema::hasTable('sessions')) {
                Log::info('✅ Table sessions existe déjà');
                
                // Vérifier la structure de la table
                $columns = DB::select("
                    SELECT column_name, data_type, is_nullable
                    FROM information_schema.columns 
                    WHERE table_name = 'sessions'
                    ORDER BY ordinal_position
                ");
                
                Log::info('📋 Structure de la table sessions: ' . count($columns) . ' colonnes');
                
                // Vérifier si la table a des enregistrements
                $count = DB::table('sessions')->count();
                Log::info("📊 Nombre d'enregistrements sessions: $count");
                
            } else {
                Log::info('ℹ️ Table sessions n\'existe pas - sera créée par les migrations');
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la vérification de la table sessions: ' . $e->getMessage());
        }
    }
    
    /**
     * Corriger les colonnes subscription pour Laravel Cloud
     */
    private function fixSubscriptionColumnsForLaravelCloud()
    {
        try {
            Log::info('🔧 Vérification des colonnes subscription pour Laravel Cloud...');
            
            // Vérifier si la table churches existe
            if (!Schema::hasTable('churches')) {
                Log::info('⚠️ Table churches n\'existe pas encore - sera créée par les migrations');
                return;
            }
            
            // Vérifier les colonnes subscription
            $columns = DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'churches' 
                AND column_name IN ('subscription_status', 'subscription_end_date', 'subscription_amount', 'subscription_start_date')
            ");
            
            $existingColumns = array_column($columns, 'column_name');
            Log::info('📋 Colonnes subscription existantes: ' . implode(', ', $existingColumns));
            
            // Ajouter les colonnes manquantes
            $requiredColumns = [
                'subscription_start_date' => 'DATE NULL',
                'subscription_end_date' => 'DATE NULL',
                'subscription_status' => "VARCHAR(20) DEFAULT 'active'",
                'subscription_amount' => 'DECIMAL(10,2) NULL'
            ];
            
            foreach ($requiredColumns as $column => $definition) {
                if (!in_array($column, $existingColumns)) {
                    try {
                        DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
                        Log::info("✅ Colonne $column ajoutée");
                    } catch (\Exception $e) {
                        Log::error("❌ Erreur lors de l'ajout de $column: " . $e->getMessage());
                    }
                } else {
                    Log::info("✅ Colonne $column existe déjà");
                }
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la vérification des colonnes subscription: ' . $e->getMessage());
        }
    }
    
    /**
     * Corriger les colonnes manquantes pour Laravel Cloud
     */
    private function fixMissingColumnsForLaravelCloud()
    {
        try {
            Log::info('🔧 Vérification des colonnes manquantes pour Laravel Cloud...');
            
            // Tables et colonnes à vérifier
            $tablesToCheck = [
                'expenses' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'donations' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'offerings' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'tithes' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'projects' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'members' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'journal_entries' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'administration_function_types' => [
                    'slug' => 'VARCHAR(255) NULL',
                    'created_by' => 'BIGINT NULL',
                    'updated_by' => 'BIGINT NULL'
                ],
                'administration_functions' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'offering_types' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'church_events' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'services' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'service_roles' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL'],
                'service_assignments' => ['created_by' => 'BIGINT NULL', 'updated_by' => 'BIGINT NULL']
            ];
            
            foreach ($tablesToCheck as $table => $columns) {
                try {
                    // Vérifier si la table existe
                    if (!Schema::hasTable($table)) {
                        Log::info("⚠️ Table $table n'existe pas encore - sera créée par les migrations");
                        continue;
                    }
                    
                    Log::info("🔍 Vérification de la table $table...");
                    
                    foreach ($columns as $column => $definition) {
                        // Vérifier si la colonne existe
                        $checkColumn = DB::select("
                            SELECT column_name 
                            FROM information_schema.columns 
                            WHERE table_name = '$table' AND column_name = '$column'
                        ");
                        
                        if (empty($checkColumn)) {
                            try {
                                DB::statement("ALTER TABLE $table ADD COLUMN $column $definition");
                                Log::info("✅ Colonne $column ajoutée à la table $table");
                            } catch (\Exception $e) {
                                Log::error("❌ Erreur lors de l'ajout de $column à $table: " . $e->getMessage());
                            }
                        } else {
                            Log::info("✅ Colonne $column existe déjà dans $table");
                        }
                    }
                    
                } catch (\Exception $e) {
                    Log::error("❌ Erreur lors de la vérification de $table: " . $e->getMessage());
                }
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de la vérification des colonnes manquantes: ' . $e->getMessage());
        }
    }
    
    /**
     * Optimiser l'application pour Laravel Cloud
     */
    private function optimizeApplicationForLaravelCloud()
    {
        try {
            Log::info('🔧 Optimisation de l\'application pour Laravel Cloud...');
            
            // Nettoyer le cache
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('route:clear');
            \Artisan::call('view:clear');
            
            Log::info('✅ Cache nettoyé');
            
            // Optimiser l'application
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            
            Log::info('✅ Application optimisée');
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur lors de l\'optimisation: ' . $e->getMessage());
        }
    }
    
    /**
     * Tester la connexion à la base de données
     */
    private function testPostgreSQLConnection()
    {
        try {
            $connection = config('database.default');
            Log::info("🔍 Test de connexion $connection...");
            
            // Test de connexion basique
            $pdo = DB::connection()->getPdo();
            Log::info("✅ Connexion $connection réussie");
            
            // Vérifier la version de la base de données
            if ($connection === 'pgsql') {
                $version = $pdo->query('SELECT version()')->fetchColumn();
                Log::info("📋 Version PostgreSQL: " . substr($version, 0, 50) . "...");
                
                // Vérifier le statut SSL
                try {
                    $sslStatus = $pdo->query('SELECT ssl_is_used()')->fetchColumn();
                    Log::info("🔒 SSL utilisé: " . ($sslStatus ? 'Oui' : 'Non'));
                    
                    if (!$sslStatus) {
                        Log::warning('⚠️ SSL non utilisé - vérifiez la configuration');
                    }
                } catch (\Exception $e) {
                    Log::warning('⚠️ Impossible de vérifier le statut SSL: ' . $e->getMessage());
                }
            } elseif ($connection === 'mysql') {
                $version = $pdo->query('SELECT VERSION()')->fetchColumn();
                Log::info("📋 Version MySQL: " . substr($version, 0, 50) . "...");
            }
            
            // Test de requête simple
            $result = DB::select('SELECT 1 as test_value');
            Log::info('✅ Requête de test réussie: ' . $result[0]->test_value);
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur de connexion base de données: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Vérifier si nous sommes en phase de build
     */
    private function isBuildPhase()
    {
        // Vérifier les variables d'environnement de build
        if (env('NIXPACKS_BUILD', false) || env('BUILD_PHASE', false)) {
            return true;
        }
        
        // Vérifier si nous sommes en mode CLI et que c'est une commande de build
        if (php_sapi_name() === 'cli') {
            $command = $_SERVER['argv'][0] ?? '';
            $buildCommands = ['artisan', 'composer', 'php'];
            
            foreach ($buildCommands as $buildCommand) {
                if (strpos($command, $buildCommand) !== false) {
                    return true;
                }
            }
        }
        
        // Vérifier si nous sommes en mode artisan
        if (defined('ARTISAN_BINARY') || (isset($_SERVER['argv'][0]) && strpos($_SERVER['argv'][0], 'artisan') !== false)) {
            return true;
        }
        
        return false;
    }
}
