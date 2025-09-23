<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DebugRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Déboguer les routes et afficher des informations détaillées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🐛 Débogage des routes...');
        
        try {
            // 1. Vérifier les routes
            $this->debugRoutes();
            
            // 2. Vérifier la configuration
            $this->debugConfiguration();
            
            // 3. Vérifier les fichiers
            $this->debugFiles();
            
            // 4. Tester une route spécifique
            $this->testSpecificRoute();
            
            $this->info('✅ Débogage terminé !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du débogage: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Déboguer les routes
     */
    private function debugRoutes()
    {
        $this->info('🛣️ Débogage des routes...');
        
        try {
            $routes = Route::getRoutes();
            $this->info("📊 Nombre total de routes: " . count($routes));
            
            // Afficher les premières routes
            $this->info('📋 Premières routes:');
            $count = 0;
            foreach ($routes as $route) {
                if ($count >= 10) break;
                $this->info("   - " . $route->methods()[0] . " " . $route->uri() . " -> " . $route->getName());
                $count++;
            }
            
            // Vérifier les routes essentielles
            $essentialRoutes = [
                'login' => '/login',
                'register' => '/register',
                'logout' => '/logout',
                'home' => '/'
            ];
            
            $this->info('🔍 Vérification des routes essentielles:');
            foreach ($essentialRoutes as $name => $path) {
                if (Route::has($name)) {
                    $route = Route::getRoutes()->getByName($name);
                    $this->info("✅ Route '$name' ($path) existe");
                    $this->info("   - Méthodes: " . implode(', ', $route->methods()));
                    $this->info("   - Action: " . $route->getActionName());
                } else {
                    $this->error("❌ Route '$name' ($path) manquante");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du débogage des routes: ' . $e->getMessage());
        }
    }
    
    /**
     * Déboguer la configuration
     */
    private function debugConfiguration()
    {
        $this->info('⚙️ Débogage de la configuration...');
        
        try {
            $this->info("📋 Configuration de l'application:");
            $this->info("   - Environnement: " . config('app.env'));
            $this->info("   - Debug: " . (config('app.debug') ? 'Activé' : 'Désactivé'));
            $this->info("   - URL: " . config('app.url'));
            $this->info("   - Timezone: " . config('app.timezone'));
            $this->info("   - Locale: " . config('app.locale'));
            
            // Vérifier les variables d'environnement
            $this->info('🔍 Variables d\'environnement:');
            $envVars = [
                'APP_NAME',
                'APP_ENV',
                'APP_KEY',
                'APP_DEBUG',
                'APP_URL',
                'DB_CONNECTION',
                'DB_HOST',
                'DB_DATABASE',
                'PORT'
            ];
            
            foreach ($envVars as $var) {
                $value = env($var);
                if ($value) {
                    $displayValue = strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value;
                    $this->info("   - $var: $displayValue");
                } else {
                    $this->warn("   - $var: non définie");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du débogage de la configuration: ' . $e->getMessage());
        }
    }
    
    /**
     * Déboguer les fichiers
     */
    private function debugFiles()
    {
        $this->info('📁 Débogage des fichiers...');
        
        try {
            $files = [
                'public/index.php',
                'public/.htaccess',
                'public/server.php',
                'bootstrap/app.php',
                'routes/web.php',
                'start.sh'
            ];
            
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $size = filesize($file);
                    $this->info("✅ Fichier $file existe ($size bytes)");
                } else {
                    $this->error("❌ Fichier $file manquant");
                }
            }
            
            // Vérifier les permissions
            $this->info('🔐 Permissions:');
            $directories = [
                'storage',
                'bootstrap/cache',
                'public/storage'
            ];
            
            foreach ($directories as $dir) {
                if (is_dir($dir)) {
                    $perms = substr(sprintf('%o', fileperms($dir)), -4);
                    $writable = is_writable($dir) ? 'Oui' : 'Non';
                    $this->info("   - $dir: $perms (écriture: $writable)");
                } else {
                    $this->error("   - $dir: n'existe pas");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du débogage des fichiers: ' . $e->getMessage());
        }
    }
    
    /**
     * Tester une route spécifique
     */
    private function testSpecificRoute()
    {
        $this->info('🧪 Test d\'une route spécifique...');
        
        try {
            // Tester la route de connexion
            $loginRoute = Route::getRoutes()->getByName('login');
            if ($loginRoute) {
                $this->info('✅ Route de connexion trouvée');
                $this->info("   - Méthodes: " . implode(', ', $loginRoute->methods()));
                $this->info("   - URI: " . $loginRoute->uri());
                $this->info("   - Action: " . $loginRoute->getActionName());
            } else {
                $this->error('❌ Route de connexion non trouvée');
            }
            
            // Tester la route racine
            $homeRoute = Route::getRoutes()->getByName('home');
            if ($homeRoute) {
                $this->info('✅ Route racine trouvée');
                $this->info("   - Méthodes: " . implode(', ', $homeRoute->methods()));
                $this->info("   - URI: " . $homeRoute->uri());
                $this->info("   - Action: " . $homeRoute->getActionName());
            } else {
                $this->error('❌ Route racine non trouvée');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test des routes: ' . $e->getMessage());
        }
    }
}
