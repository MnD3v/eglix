<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixPostgresqlMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:postgresql-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix PostgreSQL migration issue by removing church_id column safely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction de la migration PostgreSQL...');
        
        try {
            // Vérifier si la colonne church_id existe
            if (Schema::hasColumn('users', 'church_id')) {
                $this->info('✅ Colonne church_id trouvée dans la table users');
                
                // Supprimer la contrainte de clé étrangère si elle existe
                try {
                    DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_church_id_foreign');
                    $this->info('✅ Contrainte users_church_id_foreign supprimée (si elle existait)');
                } catch (\Exception $e) {
                    $this->warn('⚠️  Erreur lors de la suppression de la contrainte: ' . $e->getMessage());
                }
                
                // Supprimer la colonne church_id
                Schema::table('users', function ($table) {
                    $table->dropColumn('church_id');
                });
                
                $this->info('✅ Colonne church_id supprimée avec succès');
            } else {
                $this->info('ℹ️  Colonne church_id n\'existe pas, rien à faire');
            }
            
            // Vérifier que la table user_churches existe
            if (Schema::hasTable('user_churches')) {
                $this->info('✅ Table user_churches existe');
                
                // Compter les associations
                $count = DB::table('user_churches')->count();
                $this->info("📊 Nombre d'associations utilisateur-église: {$count}");
            } else {
                $this->error('❌ Table user_churches n\'existe pas !');
                return 1;
            }
            
            $this->info('🎉 Correction terminée avec succès !');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction: ' . $e->getMessage());
            return 1;
        }
    }
}
