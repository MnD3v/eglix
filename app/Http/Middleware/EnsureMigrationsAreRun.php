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
            Log::warning('Table churches manquante, tentative de création...');
            
            // Essayer de créer seulement les tables manquantes sans migrations
            $this->createMissingTables();
        }
    }

    /**
     * Créer seulement les tables critiques manquantes
     */
    private function createMissingTables(): void
    {
        try {
            // Créer la table churches si elle n'existe pas
            if (!$this->tableExists('churches')) {
                DB::statement('
                    CREATE TABLE IF NOT EXISTS churches (
                        id BIGSERIAL PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        slug VARCHAR(255) UNIQUE NOT NULL,
                        description TEXT,
                        address VARCHAR(255),
                        phone VARCHAR(20),
                        email VARCHAR(255),
                        website VARCHAR(255),
                        logo VARCHAR(255),
                        settings JSONB,
                        is_active BOOLEAN DEFAULT true,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP
                    )
                ');
                Log::info('Table churches créée');
            }

            // Créer la table roles si elle n'existe pas
            if (!$this->tableExists('roles')) {
                DB::statement('
                    CREATE TABLE IF NOT EXISTS roles (
                        id BIGSERIAL PRIMARY KEY,
                        church_id BIGINT NOT NULL,
                        name VARCHAR(255) NOT NULL,
                        slug VARCHAR(255) NOT NULL,
                        description TEXT,
                        permissions JSONB,
                        is_active BOOLEAN DEFAULT true,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP,
                        FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE,
                        UNIQUE (church_id, slug)
                    )
                ');
                Log::info('Table roles créée');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création des tables: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si une table existe
     */
    private function tableExists(string $tableName): bool
    {
        try {
            DB::select("SELECT 1 FROM {$tableName} LIMIT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
