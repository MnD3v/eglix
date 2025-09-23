<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DiagnoseRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostiquer les problèmes de routes et de configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnostic des routes et de la configuration...');
        
        try {
            // 1. Vérifier la connexion à la base de données
            $this->checkDatabaseConnection();
            
            // 2. Vérifier les tables essentielles
            $this->checkEssentialTables();
            
            // 3. Vérifier les routes
            $this->checkRoutes();
            
            // 4. Vérifier la configuration
            $this->checkConfiguration();
            
            // 5. Vérifier les fichiers essentiels
            $this->checkEssentialFiles();
            
            $this->info('✅ Diagnostic terminé !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du diagnostic: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Vérifier la connexion à la base de données
     */
    private function checkDatabaseConnection()
    {
        $this->info('🔍 Vérification de la connexion à la base de données...');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Connexion à la base de données réussie');
            
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            $this->info("📋 Version de la base de données: " . substr($version, 0, 50) . "...");
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion à la base de données: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Vérifier les tables essentielles
     */
    private function checkEssentialTables()
    {
        $this->info('📊 Vérification des tables essentielles...');
        
        $essentialTables = [
            'users',
            'churches',
            'members',
            'tithes',
            'offerings',
            'donations',
            'expenses',
            'projects',
            'journal_entries',
            'administration_functions',
            'administration_function_types',
            'offering_types',
            'subscriptions',
            'document_folders',
            'documents'
        ];
        
        foreach ($essentialTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->info("✅ Table $table existe ($count enregistrements)");
            } else {
                $this->error("❌ Table $table manquante");
            }
        }
    }
    
    /**
     * Vérifier les routes
     */
    private function checkRoutes()
    {
        $this->info('🛣️ Vérification des routes...');
        
        try {
            $routes = Route::getRoutes();
            $this->info("✅ " . count($routes) . " routes enregistrées");
            
            // Vérifier les routes essentielles
            $essentialRoutes = [
                'login',
                'register',
                'logout',
                'home'
            ];
            
            foreach ($essentialRoutes as $routeName) {
                if (Route::has($routeName)) {
                    $this->info("✅ Route '$routeName' existe");
                } else {
                    $this->error("❌ Route '$routeName' manquante");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification des routes: ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier la configuration
     */
    private function checkConfiguration()
    {
        $this->info('⚙️ Vérification de la configuration...');
        
        // Vérifier les variables d'environnement essentielles
        $essentialEnvVars = [
            'APP_NAME',
            'APP_ENV',
            'APP_KEY',
            'APP_DEBUG',
            'APP_URL',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
            'DB_USERNAME'
        ];
        
        foreach ($essentialEnvVars as $var) {
            $value = env($var);
            if ($value) {
                $this->info("✅ $var: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value));
            } else {
                $this->error("❌ $var: non définie");
            }
        }
        
        // Vérifier la configuration de l'application
        $this->info("📋 Configuration de l'application:");
        $this->info("   - Environnement: " . config('app.env'));
        $this->info("   - Debug: " . (config('app.debug') ? 'Activé' : 'Désactivé'));
        $this->info("   - URL: " . config('app.url'));
        $this->info("   - Timezone: " . config('app.timezone'));
    }
    
    /**
     * Vérifier les fichiers essentiels
     */
    private function checkEssentialFiles()
    {
        $this->info('📁 Vérification des fichiers essentiels...');
        
        $essentialFiles = [
            'public/index.php',
            'public/.htaccess',
            'public/server.php',
            'bootstrap/app.php',
            'config/app.php',
            'config/database.php',
            'routes/web.php'
        ];
        
        foreach ($essentialFiles as $file) {
            if (file_exists($file)) {
                $this->info("✅ Fichier $file existe");
            } else {
                $this->error("❌ Fichier $file manquant");
            }
        }
        
        // Vérifier les permissions
        $this->info('🔐 Vérification des permissions...');
        
        $directories = [
            'storage',
            'bootstrap/cache',
            'public/storage'
        ];
        
        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                if (is_writable($dir)) {
                    $this->info("✅ Répertoire $dir est accessible en écriture");
                } else {
                    $this->error("❌ Répertoire $dir n'est pas accessible en écriture");
                }
            } else {
                $this->error("❌ Répertoire $dir n'existe pas");
            }
        }
    }
}
