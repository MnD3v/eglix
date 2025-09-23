<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table churches existe
        if (Schema::hasTable('churches')) {
            // Déterminer le type de base de données
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            // Définir les colonnes d'abonnement avec leurs définitions selon le SGBD
            if ($driver === 'pgsql') {
                // PostgreSQL - utiliser VARCHAR avec CHECK constraint
                $subscriptionColumns = [
                    'subscription_start_date' => 'DATE NULL',
                    'subscription_end_date' => 'DATE NULL',
                    'subscription_status' => "VARCHAR(20) DEFAULT 'active'",
                    'subscription_amount' => 'DECIMAL(10,2) NULL',
                    'subscription_currency' => "VARCHAR(3) DEFAULT 'XOF'",
                    'subscription_plan' => "VARCHAR(50) DEFAULT 'basic'",
                    'subscription_notes' => 'TEXT NULL',
                    'payment_reference' => 'VARCHAR(255) NULL',
                    'payment_date' => 'DATE NULL'
                ];
            } else {
                // MySQL - utiliser ENUM
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
            }
            
            // Ajouter chaque colonne seulement si elle n'existe pas
            foreach ($subscriptionColumns as $column => $definition) {
                if (!Schema::hasColumn('churches', $column)) {
                    try {
                        DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
                        
                        // Pour PostgreSQL, ajouter une contrainte CHECK pour subscription_status
                        if ($driver === 'pgsql' && $column === 'subscription_status') {
                            DB::statement("ALTER TABLE churches ADD CONSTRAINT churches_subscription_status_check CHECK (subscription_status IN ('active', 'expired', 'suspended'))");
                        }
                    } catch (\Exception $e) {
                        if (strpos($e->getMessage(), 'Duplicate column name') !== false || 
                            strpos($e->getMessage(), 'already exists') !== false) {
                            // Colonne existe déjà, ignorer
                            continue;
                        }
                        throw $e;
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('churches', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_start_date',
                'subscription_end_date', 
                'subscription_status',
                'subscription_amount',
                'subscription_currency',
                'subscription_plan',
                'subscription_notes',
                'payment_reference',
                'payment_date'
            ]);
        });
    }
};
