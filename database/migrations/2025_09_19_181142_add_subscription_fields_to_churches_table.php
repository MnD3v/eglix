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
            // Définir les colonnes d'abonnement avec leurs définitions
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
            
            // Ajouter chaque colonne seulement si elle n'existe pas
            foreach ($subscriptionColumns as $column => $definition) {
                if (!Schema::hasColumn('churches', $column)) {
                    try {
                        DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
                    } catch (\Exception $e) {
                        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
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
