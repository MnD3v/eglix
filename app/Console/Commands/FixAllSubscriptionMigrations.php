<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class FixAllSubscriptionMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:all-subscription-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger toutes les migrations d\'abonnement avec vérifications d\'existence';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction de toutes les migrations d\'abonnement...');
        
        try {
            // 1. Vérifier la connexion
            $this->checkConnection();
            
            // 2. Analyser les tables
            $this->analyzeTables();
            
            // 3. Corriger les migrations
            $this->fixMigrations();
            
            // 4. Vérifier l'état final
            $this->verifyFinalState();
            
            $this->info('✅ Toutes les migrations d\'abonnement corrigées !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction des migrations: ' . $e->getMessage());
            Log::error('❌ Erreur FixAllSubscriptionMigrations: ' . $e->getMessage());
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
        
        $tables = ['churches', 'subscriptions'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table $table existe");
                
                // Vérifier les colonnes d'abonnement pour churches
                if ($table === 'churches') {
                    $subscriptionColumns = [
                        'subscription_start_date',
                        'subscription_end_date',
                        'subscription_status',
                        'subscription_amount',
                        'subscription_currency',
                        'subscription_plan',
                        'subscription_notes',
                        'payment_reference',
                        'payment_date'
                    ];
                    
                    foreach ($subscriptionColumns as $column) {
                        if (Schema::hasColumn($table, $column)) {
                            $this->info("   - Colonne $column existe");
                        } else {
                            $this->warn("   - Colonne $column manquante");
                        }
                    }
                }
                
                // Vérifier les colonnes d'audit pour subscriptions
                if ($table === 'subscriptions') {
                    $auditColumns = ['created_by', 'updated_by'];
                    foreach ($auditColumns as $column) {
                        if (Schema::hasColumn($table, $column)) {
                            $this->info("   - Colonne $column existe");
                        } else {
                            $this->warn("   - Colonne $column manquante");
                        }
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
        
        // 1. Corriger la table churches
        $this->fixChurchesTable();
        
        // 2. Corriger la table subscriptions
        $this->fixSubscriptionsTable();
    }
    
    /**
     * Corriger la table churches
     */
    private function fixChurchesTable()
    {
        $this->info('🔍 Traitement de la table churches...');
        
        if (!Schema::hasTable('churches')) {
            $this->warn("⚠️ Table churches n'existe pas, ignorée");
            return;
        }
        
        // Définir les colonnes d'abonnement
        $subscriptionColumns = [
            'subscription_start_date' => 'DATE NULL',
            'subscription_end_date' => 'DATE NULL',
            'subscription_status' => "ENUM('active', 'expired', 'suspended') DEFAULT 'active'",
            'subscription_amount' => 'DECIMAL(10,2) NULL',
            'subscription_currency' => "VARCHAR(3) DEFAULT 'XOF'",
            'subscription_plan' => "VARCHAR(50) DEFAULT 'basic'",
            'subscription_notes' => 'TEXT NULL',
            'payment_reference' => 'VARCHAR(255) NULL',
            'payment_date' => 'DATE NULL'
        ];
        
        foreach ($subscriptionColumns as $column => $definition) {
            if (!Schema::hasColumn('churches', $column)) {
                $this->info("   + Ajout de la colonne $column...");
                
                // Gérer les deadlocks avec retry
                $maxRetries = 3;
                $retryCount = 0;
                
                while ($retryCount < $maxRetries) {
                    try {
                        DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
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
    
    /**
     * Corriger la table subscriptions
     */
    private function fixSubscriptionsTable()
    {
        $this->info('🔍 Traitement de la table subscriptions...');
        
        if (!Schema::hasTable('subscriptions')) {
            $this->info("   + Création de la table subscriptions...");
            
            try {
                Schema::create('subscriptions', function ($table) {
                    $table->id();
                    $table->foreignId('church_id')->constrained()->onDelete('cascade');
                    
                    // Informations sur l'abonnement
                    $table->string('plan_name')->default('basic');
                    $table->decimal('amount', 10, 2);
                    $table->string('currency', 3)->default('XOF');
                    $table->integer('max_members')->default(100);
                    $table->boolean('has_advanced_reports')->default(false);
                    $table->boolean('has_api_access')->default(false);
                    
                    // Dates importantes
                    $table->date('start_date');
                    $table->date('end_date');
                    $table->date('payment_date')->nullable();
                    
                    // Statut et gestion
                    $table->enum('is_active', ['active', 'expired', 'suspended'])->default('active');
                    $table->enum('payment_status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
                    $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'check'])->default('cash');
                    
                    // Informations supplémentaires
                    $table->text('notes')->nullable();
                    $table->string('receipt_number')->nullable();
                    $table->string('payment_reference')->nullable();
                    
                    // Audit
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                    
                    $table->timestamps();
                    
                    // Index pour optimiser les requêtes
                    $table->index(['church_id', 'is_active']);
                    $table->index(['end_date', 'is_active']);
                    $table->index(['payment_status', 'payment_date']);
                });
                
                $this->info("   ✅ Table subscriptions créée avec succès");
            } catch (\Exception $e) {
                $this->error("   ❌ Erreur lors de la création de la table subscriptions: " . $e->getMessage());
            }
        } else {
            $this->info("   ℹ️ Table subscriptions existe déjà");
            
            // Vérifier les colonnes d'audit
            $auditColumns = ['created_by', 'updated_by'];
            foreach ($auditColumns as $column) {
                if (!Schema::hasColumn('subscriptions', $column)) {
                    $this->info("   + Ajout de la colonne $column...");
                    
                    try {
                        DB::statement("ALTER TABLE subscriptions ADD COLUMN $column BIGINT UNSIGNED NULL");
                        $this->info("   ✅ Colonne $column ajoutée avec succès");
                    } catch (\Exception $e) {
                        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                            $this->info("   ℹ️ Colonne $column existe déjà, ignorée");
                        } else {
                            $this->error("   ❌ Erreur lors de l'ajout de $column: " . $e->getMessage());
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
        
        $tables = ['churches', 'subscriptions'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("📋 Table $table:");
                
                if ($table === 'churches') {
                    $subscriptionColumns = [
                        'subscription_start_date',
                        'subscription_end_date',
                        'subscription_status',
                        'subscription_amount',
                        'subscription_currency',
                        'subscription_plan',
                        'subscription_notes',
                        'payment_reference',
                        'payment_date'
                    ];
                    
                    foreach ($subscriptionColumns as $column) {
                        if (Schema::hasColumn($table, $column)) {
                            $this->info("   ✅ $column");
                        } else {
                            $this->error("   ❌ $column manquante");
                        }
                    }
                }
                
                if ($table === 'subscriptions') {
                    $auditColumns = ['created_by', 'updated_by'];
                    foreach ($auditColumns as $column) {
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
}
