<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixAdministrationTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:fix-tables {--force : Force the creation even if tables exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix missing administration tables (URGENT for production)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚨 CORRECTION URGENTE - TABLES ADMINISTRATION MANQUANTES');
        $this->info('=====================================================');

        $force = $this->option('force');

        // Vérifier et créer administration_functions
        if (!$this->tableExists('administration_functions') || $force) {
            $this->warn('Table administration_functions manquante, création en cours...');
            $this->createAdministrationFunctionsTable();
        } else {
            $this->info('✅ Table administration_functions existe déjà');
        }

        // Vérifier et créer administration_function_types
        if (!$this->tableExists('administration_function_types') || $force) {
            $this->warn('Table administration_function_types manquante, création en cours...');
            $this->createAdministrationFunctionTypesTable();
        } else {
            $this->info('✅ Table administration_function_types existe déjà');
        }

        // Insérer les données de base
        $this->info('📝 Insertion des données de base...');
        $this->insertDefaultData();

        // Vérification finale
        $this->info('🔍 Vérification finale...');
        $this->verifyTables();

        $this->info('');
        $this->info('🎉 CORRECTION TERMINÉE!');
        $this->info('=====================');
        $this->info('');
        $this->info('📋 VÉRIFICATIONS À EFFECTUER:');
        $this->info('1. Accédez à /administration');
        $this->info('2. Vérifiez que la page se charge sans erreur');
        $this->info('3. Testez la création d\'une fonction d\'administration');
    }

    private function tableExists($tableName)
    {
        try {
            return Schema::hasTable($tableName);
        } catch (\Exception $e) {
            return false;
        }
    }

    private function createAdministrationFunctionsTable()
    {
        try {
            Schema::create('administration_functions', function ($table) {
                $table->id();
                $table->unsignedBigInteger('member_id');
                $table->string('function_name');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('church_id')->nullable();
                $table->timestamps();

                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            });
            $this->info('✅ Table administration_functions créée avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la création: ' . $e->getMessage());
        }
    }

    private function createAdministrationFunctionTypesTable()
    {
        try {
            Schema::create('administration_function_types', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->unsignedBigInteger('church_id')->nullable();
                $table->timestamps();

                $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            });
            $this->info('✅ Table administration_function_types créée avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la création: ' . $e->getMessage());
        }
    }

    private function insertDefaultData()
    {
        try {
            $types = [
                ['name' => 'Pasteur Principal', 'slug' => 'pasteur-principal', 'description' => 'Responsable principal de l\'église'],
                ['name' => 'Pasteur Associé', 'slug' => 'pasteur-associe', 'description' => 'Pasteur associé'],
                ['name' => 'Diacre', 'slug' => 'diacre', 'description' => 'Responsable du service diaconal'],
                ['name' => 'Secrétaire', 'slug' => 'secretaire', 'description' => 'Responsable de l\'administration'],
                ['name' => 'Trésorier', 'slug' => 'tresorier', 'description' => 'Responsable des finances'],
                ['name' => 'Responsable Jeunesse', 'slug' => 'responsable-jeunesse', 'description' => 'Responsable du ministère jeunesse'],
                ['name' => 'Responsable Musique', 'slug' => 'responsable-musique', 'description' => 'Responsable du ministère musical'],
                ['name' => 'Responsable Enfants', 'slug' => 'responsable-enfants', 'description' => 'Responsable du ministère enfants']
            ];

            foreach ($types as $type) {
                DB::table('administration_function_types')->updateOrInsert(
                    ['slug' => $type['slug']],
                    $type
                );
            }

            $this->info('✅ Types de fonctions insérés avec succès');
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'insertion: ' . $e->getMessage());
        }
    }

    private function verifyTables()
    {
        try {
            $count = DB::table('administration_functions')->count();
            $this->info("✅ Nombre d'enregistrements dans administration_functions: $count");
            
            $typesCount = DB::table('administration_function_types')->count();
            $this->info("✅ Nombre de types de fonctions: $typesCount");
        } catch (\Exception $e) {
            $this->error('❌ Erreur de vérification: ' . $e->getMessage());
        }
    }
}
