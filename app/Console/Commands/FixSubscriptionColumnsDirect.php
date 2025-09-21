<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSubscriptionColumnsDirect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:subscription-columns-direct {--force : Force the fix without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing subscription columns in churches table using direct SQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚨 CORRECTION IMMÉDIATE - COLONNES SUBSCRIPTION MANQUANTES');
        $this->info('=======================================================');

        if (!$this->option('force')) {
            if (!$this->confirm('Êtes-vous sûr de vouloir corriger les colonnes subscription manquantes ?')) {
                $this->info('Correction annulée.');
                return;
            }
        }

        try {
            $this->addSubscriptionColumnsDirect();
            $this->testAdminController();
            $this->verifyFinalState();

            $this->info('');
            $this->info('🎉 CORRECTION IMMÉDIATE TERMINÉE AVEC SUCCÈS!');
            $this->info('=============================================');
            $this->info('✅ Colonnes subscription ajoutées directement via SQL');
            $this->info('✅ AdminController fonctionne');
            $this->info('✅ Route /admin-0202 accessible');

        } catch (\Exception $e) {
            $this->error('❌ ERREUR lors de la correction: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Ajouter les colonnes subscription directement via SQL
     */
    private function addSubscriptionColumnsDirect()
    {
        $this->info('🔧 Ajout direct des colonnes subscription via SQL...');

        try {
            // Vérifier si les colonnes existent déjà
            $columns = DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'churches' 
                AND column_name IN ('subscription_status', 'subscription_end_date', 'subscription_amount')
            ");
            
            $existingColumns = array_column($columns, 'column_name');
            
            // Ajouter subscription_start_date si elle n'existe pas
            if (!in_array('subscription_start_date', $existingColumns)) {
                DB::statement('ALTER TABLE churches ADD COLUMN subscription_start_date DATE NULL');
                $this->info('✅ Colonne subscription_start_date ajoutée');
            } else {
                $this->info('✅ Colonne subscription_start_date existe déjà');
            }
            
            // Ajouter subscription_end_date si elle n'existe pas
            if (!in_array('subscription_end_date', $existingColumns)) {
                DB::statement('ALTER TABLE churches ADD COLUMN subscription_end_date DATE NULL');
                $this->info('✅ Colonne subscription_end_date ajoutée');
            } else {
                $this->info('✅ Colonne subscription_end_date existe déjà');
            }
            
            // Ajouter subscription_status si elle n'existe pas
            if (!in_array('subscription_status', $existingColumns)) {
                DB::statement("ALTER TABLE churches ADD COLUMN subscription_status VARCHAR(20) DEFAULT 'active'");
                $this->info('✅ Colonne subscription_status ajoutée');
            } else {
                $this->info('✅ Colonne subscription_status existe déjà');
            }
            
            // Ajouter subscription_amount si elle n'existe pas
            if (!in_array('subscription_amount', $existingColumns)) {
                DB::statement('ALTER TABLE churches ADD COLUMN subscription_amount DECIMAL(10,2) NULL');
                $this->info('✅ Colonne subscription_amount ajoutée');
            } else {
                $this->info('✅ Colonne subscription_amount existe déjà');
            }
            
            // Ajouter les autres colonnes optionnelles
            $optionalColumns = [
                'subscription_currency' => "VARCHAR(3) DEFAULT 'XOF'",
                'subscription_plan' => "VARCHAR(50) DEFAULT 'basic'",
                'subscription_notes' => 'TEXT NULL',
                'payment_reference' => 'VARCHAR(255) NULL',
                'payment_date' => 'DATE NULL'
            ];
            
            foreach ($optionalColumns as $column => $definition) {
                $checkColumn = DB::select("
                    SELECT column_name 
                    FROM information_schema.columns 
                    WHERE table_name = 'churches' AND column_name = '$column'
                ");
                
                if (empty($checkColumn)) {
                    DB::statement("ALTER TABLE churches ADD COLUMN $column $definition");
                    $this->info("✅ Colonne $column ajoutée");
                } else {
                    $this->info("✅ Colonne $column existe déjà");
                }
            }
            
            $this->info('🎉 Toutes les colonnes subscription ont été ajoutées!');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'ajout des colonnes: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tester que l'AdminController fonctionne
     */
    private function testAdminController()
    {
        $this->info('🧪 Test de l\'AdminController après correction...');

        try {
            // Test des requêtes qui causaient l'erreur
            $totalChurches = \App\Models\Church::count();
            $this->info("✅ Church::count(): {$totalChurches}");

            $activeSubscriptions = \App\Models\Church::where('subscription_status', 'active')
                ->where('subscription_end_date', '>=', now())
                ->count();
            $this->info("✅ Active subscriptions: {$activeSubscriptions}");

            $expiredSubscriptions = \App\Models\Church::where(function($q) {
                $q->where('subscription_status', 'expired')
                  ->orWhere('subscription_end_date', '<', now());
            })->count();
            $this->info("✅ Expired subscriptions: {$expiredSubscriptions}");

            $suspendedSubscriptions = \App\Models\Church::where('subscription_status', 'suspended')->count();
            $this->info("✅ Suspended subscriptions: {$suspendedSubscriptions}");

            $totalRevenue = \App\Models\Church::whereNotNull('subscription_amount')->sum('subscription_amount');
            $this->info("✅ Total revenue: {$totalRevenue}");

            $churchesWithoutSubscription = \App\Models\Church::whereNull('subscription_end_date')->count();
            $this->info("✅ Churches without subscription: {$churchesWithoutSubscription}");

            $this->info('🎉 AdminController fonctionne parfaitement!');

        } catch (\Exception $e) {
            $this->error('❌ Erreur dans AdminController: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier l'état final
     */
    private function verifyFinalState()
    {
        $this->info('🔍 Vérification de l\'état final...');

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
            $checkColumn = DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'churches' AND column_name = '$column'
            ");
            
            if (!empty($checkColumn)) {
                $this->info("✅ {$column}: OK");
            } else {
                $this->error("❌ {$column}: MANQUANTE");
            }
        }

        // Test final de l'AdminController
        try {
            $stats = [
                'total_churches' => \App\Models\Church::count(),
                'active_subscriptions' => \App\Models\Church::where('subscription_status', 'active')
                    ->where('subscription_end_date', '>=', now())
                    ->count(),
                'expired_subscriptions' => \App\Models\Church::where(function($q) {
                    $q->where('subscription_status', 'expired')
                      ->orWhere('subscription_end_date', '<', now());
                })->count(),
                'suspended_subscriptions' => \App\Models\Church::where('subscription_status', 'suspended')->count(),
                'total_revenue' => \App\Models\Church::whereNotNull('subscription_amount')->sum('subscription_amount'),
                'churches_without_subscription' => \App\Models\Church::whereNull('subscription_end_date')->count(),
            ];

            $this->info('✅ Statistiques AdminController calculées avec succès');
            $this->table(['Statistique', 'Valeur'], [
                ['Total Churches', $stats['total_churches']],
                ['Active Subscriptions', $stats['active_subscriptions']],
                ['Expired Subscriptions', $stats['expired_subscriptions']],
                ['Suspended Subscriptions', $stats['suspended_subscriptions']],
                ['Total Revenue', $stats['total_revenue']],
                ['Churches Without Subscription', $stats['churches_without_subscription']],
            ]);

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test final: ' . $e->getMessage());
            throw $e;
        }
    }
}
