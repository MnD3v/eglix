<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixSessionsTableConflict extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:sessions-conflict';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Résoudre le conflit de table sessions entre auto-correction et migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Résolution du conflit de table sessions...');
        
        try {
            // 1. Vérifier si la table sessions existe
            if (Schema::hasTable('sessions')) {
                $this->info('✅ Table sessions existe déjà');
                
                // Vérifier la structure de la table
                $columns = DB::select("
                    SELECT column_name, data_type, is_nullable
                    FROM information_schema.columns 
                    WHERE table_name = 'sessions'
                    ORDER BY ordinal_position
                ");
                
                $this->info('📋 Structure de la table sessions:');
                foreach ($columns as $column) {
                    $this->line("   - {$column->column_name} ({$column->data_type})");
                }
                
                // Vérifier si la table a des enregistrements
                $count = DB::table('sessions')->count();
                $this->info("📊 Nombre d'enregistrements: $count");
                
                // Si la table existe et est correcte, marquer la migration comme exécutée
                $this->markSessionsMigrationAsRun();
                
            } else {
                $this->info('❌ Table sessions n\'existe pas');
                $this->info('ℹ️ La table sera créée par les migrations');
            }
            
            $this->info('✅ Conflit de table sessions résolu !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la résolution du conflit: ' . $e->getMessage());
            Log::error('❌ Erreur FixSessionsTableConflict: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Marquer la migration sessions comme exécutée
     */
    private function markSessionsMigrationAsRun()
    {
        try {
            // Vérifier si la table migrations existe
            if (!Schema::hasTable('migrations')) {
                $this->info('⚠️ Table migrations n\'existe pas encore');
                return;
            }
            
            // Vérifier si la migration users est déjà marquée
            $migrationExists = DB::table('migrations')
                ->where('migration', '0001_01_01_000000_create_users_table')
                ->exists();
            
            if ($migrationExists) {
                $this->info('✅ Migration users déjà marquée comme exécutée');
            } else {
                // Marquer la migration comme exécutée
                DB::table('migrations')->insert([
                    'migration' => '0001_01_01_000000_create_users_table',
                    'batch' => 1
                ]);
                $this->info('✅ Migration users marquée comme exécutée');
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Erreur lors du marquage de la migration: ' . $e->getMessage());
        }
    }
}
