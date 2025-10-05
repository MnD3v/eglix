<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeployMultiChurchSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:multi-church-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy multi-church system safely for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Déploiement du système multi-églises...');
        
        try {
            // 1. Vérifier l'état actuel
            $this->info('📋 Vérification de l\'état actuel...');
            
            $hasChurchIdColumn = Schema::hasColumn('users', 'church_id');
            $hasUserChurchesTable = Schema::hasTable('user_churches');
            
            $this->info("Colonne church_id existe: " . ($hasChurchIdColumn ? 'Oui' : 'Non'));
            $this->info("Table user_churches existe: " . ($hasUserChurchesTable ? 'Oui' : 'Non'));
            
            // 2. Si la colonne church_id existe mais pas la table user_churches
            if ($hasChurchIdColumn && !$hasUserChurchesTable) {
                $this->info('⚠️  Migration nécessaire...');
                
                // Créer la table user_churches
                $this->call('migrate', ['--path' => 'database/migrations/2025_10_04_220710_create_user_churches_table.php']);
                
                // Migrer les données
                $this->migrateUserChurchData();
                
                // Supprimer la colonne church_id
                $this->removeChurchIdColumn();
                
            } elseif (!$hasChurchIdColumn && $hasUserChurchesTable) {
                $this->info('✅ Système multi-églises déjà déployé');
                
            } else {
                $this->info('🔄 Exécution des migrations...');
                $this->call('migrate', ['--force' => true]);
            }
            
            // 3. Vérifier les associations
            $this->info('👥 Vérification des associations...');
            $this->call('fix:user-church-associations');
            
            // 4. Tester le système
            $this->info('🧪 Test du système...');
            $this->call('test-multi-church-system');
            
            // 5. Nettoyer le cache
            $this->info('🧹 Nettoyage du cache...');
            $this->call('config:clear');
            $this->call('cache:clear');
            $this->call('route:clear');
            $this->call('view:clear');
            
            $this->info('🎉 Déploiement terminé avec succès !');
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du déploiement: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function migrateUserChurchData()
    {
        $this->info('📦 Migration des données utilisateur-église...');
        
        $users = DB::table('users')->whereNotNull('church_id')->get();
        
        foreach ($users as $user) {
            DB::table('user_churches')->insert([
                'user_id' => $user->id,
                'church_id' => $user->church_id,
                'is_primary' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->info("✅ {$users->count()} associations migrées");
    }
    
    private function removeChurchIdColumn()
    {
        $this->info('🗑️  Suppression de la colonne church_id...');
        
        if (Schema::hasColumn('users', 'church_id')) {
            // Supprimer la contrainte de clé étrangère si elle existe
            try {
                DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_church_id_foreign');
                $this->info('✅ Contrainte supprimée (si elle existait)');
            } catch (\Exception $e) {
                $this->warn('⚠️  Erreur lors de la suppression de la contrainte: ' . $e->getMessage());
            }
            
            // Supprimer la colonne
            Schema::table('users', function ($table) {
                $table->dropColumn('church_id');
            });
            
            $this->info('✅ Colonne church_id supprimée');
        }
    }
}
