<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixAllAuditMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:all-audit-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger toutes les migrations d\'audit avec vérifications d\'existence';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction de toutes les migrations d\'audit...');
        
        try {
            // 1. Vérifier la connexion
            $this->checkConnection();
            
            // 2. Analyser les tables
            $this->analyzeTables();
            
            // 3. Corriger les migrations
            $this->fixMigrations();
            
            // 4. Vérifier l'état final
            $this->verifyFinalState();
            
            $this->info('✅ Toutes les migrations d\'audit corrigées !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction des migrations: ' . $e->getMessage());
            Log::error('❌ Erreur FixAllAuditMigrations: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Vérifier la connexion
     */
    private function checkConnection()
    {
        $this->info('🔍 Vérification de la connexion...');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Connexion réussie');
            
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            $this->info("📋 Version: " . substr($version, 0, 50) . "...");
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Analyser les tables
     */
    private function analyzeTables()
    {
        $this->info('📊 Analyse des tables...');
        
        $tables = ['members', 'expenses', 'donations', 'offerings', 'tithes', 'projects'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table $table existe");
                
                // Vérifier les colonnes d'audit
                $auditColumns = ['created_by', 'updated_by'];
                foreach ($auditColumns as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $this->info("   - Colonne $column existe");
                    } else {
                        $this->warn("   - Colonne $column manquante");
                    }
                }
            } else {
                $this->warn("⚠️ Table $table n'existe pas");
            }
        }
    }
    
    /**
     * Corriger les migrations
     */
    private function fixMigrations()
    {
        $this->info('🔧 Correction des migrations...');
        
        // Définir les tables et leurs colonnes d'audit
        $auditTables = [
            'members' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL',
                'function' => 'VARCHAR(255) NULL'
            ],
            'expenses' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL'
            ],
            'donations' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL'
            ],
            'offerings' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL'
            ],
            'tithes' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL'
            ],
            'projects' => [
                'created_by' => 'BIGINT UNSIGNED NULL',
                'updated_by' => 'BIGINT UNSIGNED NULL'
            ]
        ];
        
        foreach ($auditTables as $table => $columns) {
            if (!Schema::hasTable($table)) {
                $this->warn("⚠️ Table $table n'existe pas, ignorée");
                continue;
            }
            
            $this->info("🔍 Traitement de la table $table...");
            
            foreach ($columns as $column => $definition) {
                if (!Schema::hasColumn($table, $column)) {
                    $this->info("   + Ajout de la colonne $column...");
                    
                    // Gérer les deadlocks avec retry
                    $maxRetries = 3;
                    $retryCount = 0;
                    
                    while ($retryCount < $maxRetries) {
                        try {
                            DB::statement("ALTER TABLE $table ADD COLUMN $column $definition");
                            $this->info("   ✅ Colonne $column ajoutée avec succès");
                            break;
                        } catch (\Exception $e) {
                            $retryCount++;
                            
                            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                                $this->info("   ℹ️ Colonne $column existe déjà, ignorée");
                                break;
                            }
                            
                            if (strpos($e->getMessage(), 'Deadlock found') !== false && $retryCount < $maxRetries) {
                                $this->warn("   ⚠️ Deadlock détecté, tentative $retryCount/$maxRetries...");
                                sleep(rand(1, 3));
                                continue;
                            }
                            
                            $this->error("   ❌ Erreur lors de l'ajout de $column: " . $e->getMessage());
                            break;
                        }
                    }
                } else {
                    $this->info("   ℹ️ Colonne $column existe déjà");
                }
            }
        }
    }
    
    /**
     * Vérifier l'état final
     */
    private function verifyFinalState()
    {
        $this->info('🔍 Vérification de l\'état final...');
        
        $tables = ['members', 'expenses', 'donations', 'offerings', 'tithes', 'projects'];
        $auditColumns = ['created_by', 'updated_by'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("📋 Table $table:");
                
                foreach ($auditColumns as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $this->info("   ✅ $column");
                    } else {
                        $this->error("   ❌ $column manquante");
                    }
                }
                
                // Vérifier la colonne function pour members
                if ($table === 'members') {
                    if (Schema::hasColumn($table, 'function')) {
                        $this->info("   ✅ function");
                    } else {
                        $this->error("   ❌ function manquante");
                    }
                }
            }
        }
    }
}
