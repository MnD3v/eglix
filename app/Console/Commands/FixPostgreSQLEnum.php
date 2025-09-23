<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixPostgreSQLEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:postgresql-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les problèmes ENUM pour PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🐘 Correction des problèmes ENUM pour PostgreSQL...');
        
        try {
            $driver = DB::connection()->getDriverName();
            $this->info("📊 Type de base de données: $driver");
            
            if ($driver !== 'pgsql') {
                $this->info('ℹ️ Pas PostgreSQL, aucune correction nécessaire');
                return 0;
            }
            
            // Vérifier si la colonne subscription_status existe avec ENUM
            $this->fixSubscriptionStatusColumn();
            
            $this->info('✅ Correction PostgreSQL terminée !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction PostgreSQL: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Corriger la colonne subscription_status
     */
    private function fixSubscriptionStatusColumn()
    {
        $this->info('🔧 Correction de la colonne subscription_status...');
        
        try {
            // Vérifier si la colonne existe
            if (Schema::hasColumn('churches', 'subscription_status')) {
                $this->info('✅ Colonne subscription_status existe');
                
                // Vérifier le type de la colonne
                $columnInfo = DB::select("
                    SELECT data_type, column_default 
                    FROM information_schema.columns 
                    WHERE table_name = 'churches' AND column_name = 'subscription_status'
                ");
                
                if (!empty($columnInfo)) {
                    $dataType = $columnInfo[0]->data_type;
                    $this->info("📋 Type actuel: $dataType");
                    
                    if ($dataType === 'character varying') {
                        $this->info('✅ Colonne déjà en VARCHAR, vérification de la contrainte...');
                        
                        // Vérifier si la contrainte CHECK existe
                        $constraintExists = DB::select("
                            SELECT constraint_name 
                            FROM information_schema.check_constraints 
                            WHERE constraint_name = 'churches_subscription_status_check'
                        ");
                        
                        if (empty($constraintExists)) {
                            $this->info('🔧 Ajout de la contrainte CHECK...');
                            DB::statement("ALTER TABLE churches ADD CONSTRAINT churches_subscription_status_check CHECK (subscription_status IN ('active', 'expired', 'suspended'))");
                            $this->info('✅ Contrainte CHECK ajoutée');
                        } else {
                            $this->info('✅ Contrainte CHECK existe déjà');
                        }
                    } else {
                        $this->info('⚠️ Type de colonne non standard, aucune action nécessaire');
                    }
                }
            } else {
                $this->info('ℹ️ Colonne subscription_status n\'existe pas encore');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la correction de subscription_status: ' . $e->getMessage());
        }
    }
}
