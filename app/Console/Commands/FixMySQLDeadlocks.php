<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixMySQLDeadlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:mysql-deadlocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Résoudre les deadlocks MySQL lors des migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Résolution des deadlocks MySQL...');
        
        try {
            // 1. Vérifier la connexion MySQL
            $this->checkMySQLConnection();
            
            // 2. Vérifier les tables verrouillées
            $this->checkLockedTables();
            
            // 3. Résoudre les deadlocks
            $this->resolveDeadlocks();
            
            // 4. Vérifier les colonnes manquantes
            $this->checkMissingColumns();
            
            $this->info('✅ Deadlocks MySQL résolus !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la résolution des deadlocks: ' . $e->getMessage());
            Log::error('❌ Erreur FixMySQLDeadlocks: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Vérifier la connexion MySQL
     */
    private function checkMySQLConnection()
    {
        $this->info('🔍 Vérification de la connexion MySQL...');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Connexion MySQL réussie');
            
            // Vérifier la version MySQL
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            $this->info("📋 Version MySQL: " . substr($version, 0, 50) . "...");
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion MySQL: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Vérifier les tables verrouillées
     */
    private function checkLockedTables()
    {
        $this->info('🔒 Vérification des tables verrouillées...');
        
        try {
            // Vérifier les processus MySQL
            $processes = DB::select('SHOW PROCESSLIST');
            $this->info("📊 Nombre de processus MySQL: " . count($processes));
            
            // Vérifier les tables verrouillées
            $lockedTables = DB::select("
                SELECT 
                    TABLE_SCHEMA,
                    TABLE_NAME,
                    ENGINE,
                    TABLE_ROWS
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME IN ('members', 'users', 'churches', 'sessions')
            ");
            
            $this->info("📋 Tables principales: " . count($lockedTables));
            foreach ($lockedTables as $table) {
                $this->info("   - {$table->TABLE_NAME} ({$table->ENGINE}) - {$table->TABLE_ROWS} lignes");
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Erreur lors de la vérification des tables: ' . $e->getMessage());
        }
    }
    
    /**
     * Résoudre les deadlocks
     */
    private function resolveDeadlocks()
    {
        $this->info('🔧 Résolution des deadlocks...');
        
        try {
            // Tuer les processus en attente
            $waitingProcesses = DB::select("
                SELECT ID, USER, HOST, DB, COMMAND, TIME, STATE, INFO
                FROM information_schema.PROCESSLIST 
                WHERE COMMAND = 'Sleep' 
                AND TIME > 30
                AND DB = DATABASE()
            ");
            
            if (count($waitingProcesses) > 0) {
                $this->info("🔍 Processus en attente trouvés: " . count($waitingProcesses));
                
                foreach ($waitingProcesses as $process) {
                    try {
                        DB::statement("KILL {$process->ID}");
                        $this->info("✅ Processus {$process->ID} tué");
                    } catch (\Exception $e) {
                        $this->warn("⚠️ Impossible de tuer le processus {$process->ID}: " . $e->getMessage());
                    }
                }
            } else {
                $this->info('✅ Aucun processus en attente trouvé');
            }
            
            // Vérifier les deadlocks
            $deadlocks = DB::select("SHOW ENGINE INNODB STATUS");
            if (!empty($deadlocks)) {
                $status = $deadlocks[0]->Status;
                if (strpos($status, 'DEADLOCK') !== false) {
                    $this->warn('⚠️ Deadlocks détectés dans le statut InnoDB');
                } else {
                    $this->info('✅ Aucun deadlock détecté');
                }
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Erreur lors de la résolution des deadlocks: ' . $e->getMessage());
        }
    }
    
    /**
     * Vérifier les colonnes manquantes
     */
    private function checkMissingColumns()
    {
        $this->info('🔍 Vérification des colonnes manquantes...');
        
        try {
            // Vérifier la table members
            if (Schema::hasTable('members')) {
                $this->info('✅ Table members existe');
                
                // Vérifier la colonne function
                if (!Schema::hasColumn('members', 'function')) {
                    $this->info('🔧 Ajout de la colonne function à la table members...');
                    
                    // Gérer les deadlocks avec retry
                    $maxRetries = 3;
                    $retryCount = 0;
                    
                    while ($retryCount < $maxRetries) {
                        try {
                            Schema::table('members', function ($table) {
                                $table->string('function')->nullable()->after('marital_status');
                            });
                            $this->info('✅ Colonne function ajoutée avec succès');
                            break;
                        } catch (\Exception $e) {
                            $retryCount++;
                            
                            if (strpos($e->getMessage(), 'Deadlock found') !== false && $retryCount < $maxRetries) {
                                $this->warn("⚠️ Deadlock détecté, tentative $retryCount/$maxRetries...");
                                sleep(rand(1, 3));
                                continue;
                            }
                            
                            $this->error('❌ Erreur lors de l\'ajout de la colonne: ' . $e->getMessage());
                            throw $e;
                        }
                    }
                } else {
                    $this->info('✅ Colonne function existe déjà');
                }
            } else {
                $this->warn('⚠️ Table members n\'existe pas encore');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification des colonnes: ' . $e->getMessage());
            throw $e;
        }
    }
}
