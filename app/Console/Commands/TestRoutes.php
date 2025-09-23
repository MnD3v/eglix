<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester les routes et afficher les informations de débogage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test des routes...');
        
        try {
            // 1. Vérifier les routes
            $this->testRoutes();
            
            // 2. Vérifier la base de données
            $this->testDatabase();
            
            // 3. Vérifier la configuration
            $this->testConfiguration();
            
            // 4. Afficher les informations de débogage
            $this->showDebugInfo();
            
            $this->info('✅ Tests terminés !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors des tests: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Tester les routes
     */
    private function testRoutes()
    {
        $this->info('🛣️ Test des routes...');
        
        try {
            $routes = Route::getRoutes();
            $this->info("✅ " . count($routes) . " routes enregistrées");
            
            // Tester les routes essentielles
            $essentialRoutes = [
                'login' => '/login',
                'register' => '/register',
                'logout' => '/logout',
                'home' => '/'
            ];
            
            foreach ($essentialRoutes as $name => $path) {
                if (Route::has($name)) {
                    $this->info("✅ Route '$name' ($path) existe");
                } else {
                    $this->error("❌ Route '$name' ($path) manquante");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test des routes: ' . $e->getMessage());
        }
    }
    
    /**
     * Tester la base de données
     */
    private function testDatabase()
    {
        $this->info('🗄️ Test de la base de données...');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Connexion à la base de données réussie');
            
            // Tester une requête simple
            $result = DB::select('SELECT 1 as test_value');
            $this->info('✅ Requête de test réussie: ' . $result[0]->test_value);
            
            // Vérifier les tables essentielles
            $essentialTables = ['users', 'churches', 'members'];
            foreach ($essentialTables as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->info("✅ Table $table existe ($count enregistrements)");
                } else {
                    $this->error("❌ Table $table manquante");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test de la base de données: ' . $e->getMessage());
        }
    }
    
    /**
     * Tester la configuration
     */
    private function testConfiguration()
    {
        $this->info('⚙️ Test de la configuration...');
        
        try {
            $this->info("📋 Configuration de l'application:");
            $this->info("   - Environnement: " . config('app.env'));
            $this->info("   - Debug: " . (config('app.debug') ? 'Activé' : 'Désactivé'));
            $this->info("   - URL: " . config('app.url'));
            $this->info("   - Timezone: " . config('app.timezone'));
            
            // Vérifier les variables d'environnement
            $essentialEnvVars = [
                'APP_NAME',
                'APP_ENV',
                'APP_KEY',
                'APP_DEBUG',
                'APP_URL',
                'DB_CONNECTION',
                'DB_HOST',
                'DB_DATABASE'
            ];
            
            foreach ($essentialEnvVars as $var) {
                $value = env($var);
                if ($value) {
                    $this->info("✅ $var: " . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value));
                } else {
                    $this->error("❌ $var: non définie");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test de la configuration: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher les informations de débogage
     */
    private function showDebugInfo()
    {
        $this->info('🔍 Informations de débogage...');
        
        try {
            $this->info("📋 Informations système:");
            $this->info("   - PHP Version: " . PHP_VERSION);
            $this->info("   - Laravel Version: " . app()->version());
            $this->info("   - Serveur: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Inconnu'));
            $this->info("   - Port: " . ($_SERVER['SERVER_PORT'] ?? 'Inconnu'));
            $this->info("   - Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Inconnu'));
            $this->info("   - Script Name: " . ($_SERVER['SCRIPT_NAME'] ?? 'Inconnu'));
            $this->info("   - Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'Inconnu'));
            
            // Vérifier les fichiers essentiels
            $essentialFiles = [
                'public/index.php',
                'public/.htaccess',
                'public/server.php',
                'bootstrap/app.php',
                'routes/web.php'
            ];
            
            foreach ($essentialFiles as $file) {
                if (file_exists($file)) {
                    $this->info("✅ Fichier $file existe");
                } else {
                    $this->error("❌ Fichier $file manquant");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'affichage des informations de débogage: ' . $e->getMessage());
        }
    }
}
