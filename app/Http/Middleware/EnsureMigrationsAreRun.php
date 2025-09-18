<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnsureMigrationsAreRun
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier uniquement sur les routes critiques
        if ($this->shouldCheckMigrations($request)) {
            $this->ensureMigrationsAreRun();
        }

        return $next($request);
    }

    /**
     * Déterminer si on doit vérifier les migrations
     */
    private function shouldCheckMigrations(Request $request): bool
    {
        $criticalRoutes = [
            'register',
            'login',
            'churches.store',
            'churches.create'
        ];

        return $request->routeIs($criticalRoutes);
    }

    /**
     * S'assurer que les migrations sont exécutées
     */
    private function ensureMigrationsAreRun(): void
    {
        try {
            // Vérifier si la table churches existe
            DB::select('SELECT 1 FROM churches LIMIT 1');
        } catch (\Exception $e) {
            Log::warning('Table churches manquante, exécution des migrations...');
            
            try {
                // Exécuter les migrations
                Artisan::call('migrate', [
                    '--force' => true, 
                    '--no-interaction' => true
                ]);
                
                Log::info('Migrations exécutées avec succès');
            } catch (\Exception $migrationError) {
                Log::error('Erreur lors de l\'exécution des migrations: ' . $migrationError->getMessage());
            }
        }
    }
}
