<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixAllPhotoMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:all-photo-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger toutes les migrations de photos avec vérifications d\'existence';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction de toutes les migrations de photos...');
        
        try {
            // 1. Vérifier la connexion
            $this->checkConnection();
            
            // 2. Analyser les tables
            $this->analyzeTables();
            
            // 3. Corriger les migrations
            $this->fixMigrations();
            
            // 4. Vérifier l'état final
            $this->verifyFinalState();
            
            $this->info('✅ Toutes les migrations de photos corrigées !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction des migrations: ' . $e->getMessage());
            Log::error('❌ Erreur FixAllPhotoMigrations: ' . $e->getMessage());
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
        
        $tables = ['members'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table $table existe");
                
                // Vérifier les colonnes de photos
                $photoColumns = [
                    'marital_status',
                    'profile_photo',
                    'photo_url',
                    'photo'
                ];
                
                foreach ($photoColumns as $column) {
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
        
        // 1. Corriger la table members
        $this->fixMembersTable();
    }
    
    /**
     * Corriger la table members
     */
    private function fixMembersTable()
    {
        $this->info('🔍 Traitement de la table members...');
        
        if (!Schema::hasTable('members')) {
            $this->warn("⚠️ Table members n'existe pas, ignorée");
            return;
        }
        
        // Définir les colonnes de photos
        $photoColumns = [
            'marital_status' => 'VARCHAR(255) NULL',
            'profile_photo' => 'VARCHAR(255) NULL',
            'photo_url' => 'VARCHAR(255) NULL'
        ];
        
        foreach ($photoColumns as $column => $definition) {
            if (!Schema::hasColumn('members', $column)) {
                $this->info("   + Ajout de la colonne $column...");
                
                // Gérer les deadlocks avec retry
                $maxRetries = 3;
                $retryCount = 0;
                
                while ($retryCount < $maxRetries) {
                    try {
                        // Déterminer la position de la colonne
                        $afterColumn = $this->determineColumnPosition($column);
                        
                        DB::statement("ALTER TABLE members ADD COLUMN $column $definition AFTER $afterColumn");
                        $this->info("   ✅ Colonne $column ajoutée avec succès après $afterColumn");
                        break;
                    } catch (\Exception $e) {
                        $retryCount++;
                        
                        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                            $this->info("   ℹ️ Colonne $column existe déjà, ignorée");
                            break;
                        }
                        
                        if (strpos($e->getMessage(), 'Unknown column') !== false) {
                            $this->warn("   ⚠️ Colonne de référence inconnue, tentative avec position par défaut...");
                            try {
                                DB::statement("ALTER TABLE members ADD COLUMN $column $definition");
                                $this->info("   ✅ Colonne $column ajoutée avec succès (position par défaut)");
                                break;
                            } catch (\Exception $e2) {
                                $this->error("   ❌ Erreur lors de l'ajout de $column: " . $e2->getMessage());
                                break;
                            }
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
    
    /**
     * Déterminer la position de la colonne
     */
    private function determineColumnPosition($column)
    {
        // Définir les positions par défaut
        $positions = [
            'marital_status' => 'gender',
            'profile_photo' => 'marital_status',
            'photo_url' => 'updated_at'
        ];
        
        $afterColumn = $positions[$column] ?? 'updated_at';
        
        // Vérifier si la colonne de référence existe
        if (!Schema::hasColumn('members', $afterColumn)) {
            // Essayer des alternatives
            $alternatives = [
                'marital_status' => ['gender', 'function', 'updated_at'],
                'profile_photo' => ['marital_status', 'function', 'updated_at'],
                'photo_url' => ['profile_photo', 'marital_status', 'function', 'updated_at']
            ];
            
            if (isset($alternatives[$column])) {
                foreach ($alternatives[$column] as $alt) {
                    if (Schema::hasColumn('members', $alt)) {
                        $afterColumn = $alt;
                        break;
                    }
                }
            }
        }
        
        return $afterColumn;
    }
    
    /**
     * Vérifier l'état final
     */
    private function verifyFinalState()
    {
        $this->info('🔍 Vérification de l\'état final...');
        
        $tables = ['members'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("📋 Table $table:");
                
                $photoColumns = [
                    'marital_status',
                    'profile_photo',
                    'photo_url'
                ];
                
                foreach ($photoColumns as $column) {
                    if (Schema::hasColumn($table, $column)) {
                        $this->info("   ✅ $column");
                    } else {
                        $this->error("   ❌ $column manquante");
                    }
                }
            }
        }
    }
}
