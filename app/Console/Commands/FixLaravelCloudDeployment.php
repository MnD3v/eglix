<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FixLaravelCloudDeployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-cloud:fix-deployment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les problèmes de déploiement sur Laravel Cloud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Correction du déploiement Laravel Cloud...');
        
        try {
            // 1. Vérifier et corriger la table sessions
            $this->fixSessionsTable();
            
            // 2. Vérifier et corriger les colonnes subscription
            $this->fixSubscriptionColumns();
            
            // 3. Vérifier et corriger les colonnes manquantes
            $this->fixMissingColumns();
            
            $this->info('✅ Correction du déploiement Laravel Cloud terminée !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction: ' . $e->getMessage());
            Log::error('❌ Erreur FixLaravelCloudDeployment: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Corriger la table sessions
     */
    private function fixSessionsTable()
    {
        $this->info('🔧 Vérification de la table sessions...');
        
        try {
            // Vérifier si la table sessions existe
            if (Schema::hasTable('sessions')) {
                $this->info('✅ Table sessions existe déjà');
                
                // Vérifier si la table sessions a des enregistrements
                $count = DB::table('sessions')->count();
                $this->info("📊 Table sessions contient $count enregistrements");
                
                // Si la table existe mais est vide, c'est normal
                if ($count === 0) {
                    $this->info('ℹ️ Table sessions vide - normal pour un nouveau déploiement');
                }
            } else {
                $this->info('❌ Table sessions n\'existe pas - sera créée par les migrations');
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Erreur lors de la vérification de la table sessions: ' . $e->getMessage());
        }
    }
    
    /**
     * Corriger les colonnes subscription
     */
    private function fixSubscriptionColumns()
    {
        $this->info('🔧 Vérification des colonnes subscription...');
        
        try {
            // Vérifier si la table churches existe
            if (!Schema::hasTable('churches')) {
                $this->info('⚠️ Table churches n\'existe pas encore - sera créée par les migrations');
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
            $this->info('📋 Colonnes subscription existantes: ' . implode(', ', $existingColumns));
            
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
                        $this->info("✅ Colonne $column ajoutée");
                    } catch (\Exception $e) {
                        $this->warn("⚠️ Erreur lors de l'ajout de $column: " . $e->getMessage());
                    }
                } else {
                    $this->info("✅ Colonne $column existe déjà");
                }
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Erreur lors de la vérification des colonnes subscription: ' . $e->getMessage());
        }
    }
    
    /**
     * Corriger les colonnes manquantes
     */
    private function fixMissingColumns()
    {
        $this->info('🔧 Vérification des colonnes manquantes...');
        
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
                    $this->info("⚠️ Table $table n'existe pas encore - sera créée par les migrations");
                    continue;
                }
                
                $this->info("🔍 Vérification de la table $table...");
                
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
                            $this->info("✅ Colonne $column ajoutée à la table $table");
                        } catch (\Exception $e) {
                            $this->warn("⚠️ Erreur lors de l'ajout de $column à $table: " . $e->getMessage());
                        }
                    } else {
                        $this->info("✅ Colonne $column existe déjà dans $table");
                    }
                }
                
            } catch (\Exception $e) {
                $this->warn("⚠️ Erreur lors de la vérification de $table: " . $e->getMessage());
            }
        }
    }
}
