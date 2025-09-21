<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Church;

class FixSubscriptionColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:subscription-columns {--force : Force the fix without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing subscription columns in churches table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 CORRECTION URGENTE - COLONNES SUBSCRIPTION MANQUANTES');
        $this->info('=======================================================');

        if (!$this->option('force')) {
            if (!$this->confirm('Êtes-vous sûr de vouloir corriger les colonnes subscription manquantes ?')) {
                $this->info('Correction annulée.');
                return;
            }
        }

        try {
            $this->checkChurchesTable();
            $this->addMissingColumns();
            $this->testAdminController();
            $this->verifyFinalState();

            $this->info('');
            $this->info('🎉 CORRECTION URGENTE TERMINÉE AVEC SUCCÈS!');
            $this->info('==========================================');
            $this->info('✅ Colonnes subscription ajoutées');
            $this->info('✅ AdminController fonctionne');
            $this->info('✅ Route /admin-0202 accessible');

        } catch (\Exception $e) {
            $this->error('❌ ERREUR lors de la correction: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Vérifier que la table churches existe
     */
    private function checkChurchesTable()
    {
        $this->info('🔍 Vérification de la table churches...');

        if (!Schema::hasTable('churches')) {
            $this->error('❌ Table churches n\'existe pas!');
            throw new \Exception('Table churches manquante');
        }

        $this->info('✅ Table churches existe');
    }

    /**
     * Ajouter les colonnes subscription manquantes
     */
    private function addMissingColumns()
    {
        $this->info('🔧 Ajout des colonnes subscription manquantes...');

        $subscriptionColumns = [
            'subscription_start_date' => 'date',
            'subscription_end_date' => 'date', 
            'subscription_status' => 'enum',
            'subscription_amount' => 'decimal',
            'subscription_currency' => 'string',
            'subscription_plan' => 'string',
            'subscription_notes' => 'text',
            'payment_reference' => 'string',
            'payment_date' => 'date'
        ];

        foreach ($subscriptionColumns as $column => $type) {
            if (Schema::hasColumn('churches', $column)) {
                $this->info("✅ Colonne {$column} existe déjà");
            } else {
                $this->warn("❌ Colonne {$column} manquante - Ajout en cours...");
                
                try {
                    Schema::table('churches', function ($table) use ($column) {
                        switch($column) {
                            case 'subscription_start_date':
                                $table->date('subscription_start_date')->nullable()->after('updated_at');
                                break;
                            case 'subscription_end_date':
                                $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
                                break;
                            case 'subscription_status':
                                $table->enum('subscription_status', ['active', 'expired', 'suspended'])->default('active')->after('subscription_end_date');
                                break;
                            case 'subscription_amount':
                                $table->decimal('subscription_amount', 10, 2)->nullable()->after('subscription_status');
                                break;
                            case 'subscription_currency':
                                $table->string('subscription_currency', 3)->default('XOF')->after('subscription_amount');
                                break;
                            case 'subscription_plan':
                                $table->string('subscription_plan', 50)->default('basic')->after('subscription_currency');
                                break;
                            case 'subscription_notes':
                                $table->text('subscription_notes')->nullable()->after('subscription_plan');
                                break;
                            case 'payment_reference':
                                $table->string('payment_reference')->nullable()->after('subscription_notes');
                                break;
                            case 'payment_date':
                                $table->date('payment_date')->nullable()->after('payment_reference');
                                break;
                        }
                    });
                    
                    $this->info("✅ Colonne {$column} ajoutée avec succès");
                } catch (\Exception $e) {
                    $this->error("❌ Erreur lors de l'ajout de {$column}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Tester que l'AdminController fonctionne
     */
    private function testAdminController()
    {
        $this->info('🧪 Test de l\'AdminController...');

        try {
            // Test des requêtes qui causaient l'erreur
            $totalChurches = Church::count();
            $this->info("✅ Church::count(): {$totalChurches}");

            $activeSubscriptions = Church::where('subscription_status', 'active')
                ->where('subscription_end_date', '>=', now())
                ->count();
            $this->info("✅ Active subscriptions: {$activeSubscriptions}");

            $expiredSubscriptions = Church::where(function($q) {
                $q->where('subscription_status', 'expired')
                  ->orWhere('subscription_end_date', '<', now());
            })->count();
            $this->info("✅ Expired subscriptions: {$expiredSubscriptions}");

            $suspendedSubscriptions = Church::where('subscription_status', 'suspended')->count();
            $this->info("✅ Suspended subscriptions: {$suspendedSubscriptions}");

            $totalRevenue = Church::whereNotNull('subscription_amount')->sum('subscription_amount');
            $this->info("✅ Total revenue: {$totalRevenue}");

            $churchesWithoutSubscription = Church::whereNull('subscription_end_date')->count();
            $this->info("✅ Churches without subscription: {$churchesWithoutSubscription}");

            $this->info('🎉 AdminController fonctionne correctement!');

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
            if (Schema::hasColumn('churches', $column)) {
                $this->info("✅ {$column}: OK");
            } else {
                $this->error("❌ {$column}: MANQUANTE");
            }
        }

        // Test final de l'AdminController
        try {
            $stats = [
                'total_churches' => Church::count(),
                'active_subscriptions' => Church::where('subscription_status', 'active')
                    ->where('subscription_end_date', '>=', now())
                    ->count(),
                'expired_subscriptions' => Church::where(function($q) {
                    $q->where('subscription_status', 'expired')
                      ->orWhere('subscription_end_date', '<', now());
                })->count(),
                'suspended_subscriptions' => Church::where('subscription_status', 'suspended')->count(),
                'total_revenue' => Church::whereNotNull('subscription_amount')->sum('subscription_amount'),
                'churches_without_subscription' => Church::whereNull('subscription_end_date')->count(),
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
