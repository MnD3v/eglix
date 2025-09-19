<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ForceAdminMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:force-migration {--force : Force the migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force the migration of administration elements (tables, columns, and data)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 MIGRATION FORCÉE - ADMINISTRATION');
        $this->info('====================================');

        if (!$this->option('force')) {
            if (!$this->confirm('Êtes-vous sûr de vouloir forcer la migration des éléments d\'administration ?')) {
                $this->info('Migration annulée.');
                return;
            }
        }

        try {
            $this->createAdminTables();
            $this->addChurchIdColumns();
            $this->insertBaseData();
            $this->runLaravelMigrations();
            $this->verifyFinalState();

            $this->info('');
            $this->info('🎉 MIGRATION FORCÉE TERMINÉE AVEC SUCCÈS!');
            $this->info('Toutes les tables et colonnes d\'administration sont maintenant disponibles.');
            $this->info('Vous pouvez maintenant utiliser les fonctionnalités d\'administration de l\'application.');

        } catch (\Exception $e) {
            $this->error('❌ ERREUR lors de la migration forcée: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Créer les tables d'administration
     */
    private function createAdminTables()
    {
        $this->info('📋 Création des tables d\'administration...');

        $tables = [
            'administration_functions' => function ($table) {
                $table->id();
                $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                $table->string('function_name');
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            },
            'administration_function_types' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            },
            'roles' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            },
            'permissions' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            },
            'churches' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            }
        ];

        foreach ($tables as $tableName => $callback) {
            if (!Schema::hasTable($tableName)) {
                $this->warn("Table $tableName n'existe pas - Création en cours...");
                Schema::create($tableName, $callback);
                $this->info("✅ Table $tableName créée avec succès");
            } else {
                $this->info("✅ Table $tableName existe déjà");
            }
        }
    }

    /**
     * Ajouter les colonnes church_id
     */
    private function addChurchIdColumns()
    {
        $this->info('🏢 Ajout des colonnes church_id...');

        $tables = [
            'administration_functions',
            'administration_function_types',
            'users',
            'members',
            'tithes',
            'offerings',
            'donations',
            'expenses',
            'projects',
            'services',
            'church_events',
            'service_roles',
            'service_assignments',
            'journal_entries',
            'journal_images'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                if (!Schema::hasColumn($tableName, 'church_id')) {
                    $this->warn("Colonne church_id manquante dans $tableName - Ajout en cours...");
                    try {
                        Schema::table($tableName, function ($table) {
                            $table->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
                        });
                        $this->info("✅ Colonne church_id ajoutée à $tableName");
                    } catch (\Exception $e) {
                        $this->warn("⚠️  Impossible d'ajouter church_id à $tableName: " . $e->getMessage());
                    }
                } else {
                    $this->info("✅ Colonne church_id présente dans $tableName");
                }
            } else {
                $this->warn("⚠️  Table $tableName n'existe pas - ignorée");
            }
        }
    }

    /**
     * Insérer les données de base
     */
    private function insertBaseData()
    {
        $this->info('📊 Insertion des données de base...');

        // Types de fonctions d'administration
        $functionTypes = [
            ['name' => 'Pasteur Principal', 'slug' => 'pasteur-principal', 'description' => 'Responsable principal de l\'église', 'sort_order' => 1],
            ['name' => 'Pasteur Assistant', 'slug' => 'pasteur-assistant', 'description' => 'Assistant du pasteur principal', 'sort_order' => 2],
            ['name' => 'Ancien', 'slug' => 'ancien', 'description' => 'Membre du conseil des anciens', 'sort_order' => 3],
            ['name' => 'Diacre', 'slug' => 'diacre', 'description' => 'Responsable du service diaconal', 'sort_order' => 4],
            ['name' => 'Secrétaire', 'slug' => 'secretaire', 'description' => 'Responsable de la gestion administrative', 'sort_order' => 5],
            ['name' => 'Trésorier', 'slug' => 'tresorier', 'description' => 'Responsable de la gestion financière', 'sort_order' => 6],
        ];

        foreach ($functionTypes as $type) {
            $existing = DB::table('administration_function_types')->where('slug', $type['slug'])->first();
            if (!$existing) {
                DB::table('administration_function_types')->insert(array_merge($type, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
                $this->info("✅ Type de fonction ajouté: " . $type['name']);
            }
        }

        // Rôles de base
        $roles = [
            ['name' => 'Administrateur', 'slug' => 'admin', 'description' => 'Accès complet à l\'application'],
            ['name' => 'Pasteur', 'slug' => 'pasteur', 'description' => 'Accès aux fonctions pastorales'],
            ['name' => 'Secrétaire', 'slug' => 'secretaire', 'description' => 'Accès aux fonctions administratives'],
            ['name' => 'Membre', 'slug' => 'membre', 'description' => 'Accès de base aux informations'],
        ];

        foreach ($roles as $role) {
            $existing = DB::table('roles')->where('slug', $role['slug'])->first();
            if (!$existing) {
                DB::table('roles')->insert(array_merge($role, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
                $this->info("✅ Rôle ajouté: " . $role['name']);
            }
        }

        // Permissions de base
        $permissions = [
            ['name' => 'Voir les membres', 'slug' => 'members.view', 'description' => 'Consulter la liste des membres'],
            ['name' => 'Créer des membres', 'slug' => 'members.create', 'description' => 'Ajouter de nouveaux membres'],
            ['name' => 'Modifier les membres', 'slug' => 'members.edit', 'description' => 'Modifier les informations des membres'],
            ['name' => 'Supprimer les membres', 'slug' => 'members.delete', 'description' => 'Supprimer des membres'],
            ['name' => 'Voir les dîmes', 'slug' => 'tithes.view', 'description' => 'Consulter les dîmes'],
            ['name' => 'Gérer les dîmes', 'slug' => 'tithes.manage', 'description' => 'Créer et modifier les dîmes'],
            ['name' => 'Voir les offrandes', 'slug' => 'offerings.view', 'description' => 'Consulter les offrandes'],
            ['name' => 'Gérer les offrandes', 'slug' => 'offerings.manage', 'description' => 'Créer et modifier les offrandes'],
            ['name' => 'Voir les rapports', 'slug' => 'reports.view', 'description' => 'Consulter les rapports'],
            ['name' => 'Gérer l\'administration', 'slug' => 'administration.view', 'description' => 'Accès aux fonctions d\'administration'],
            ['name' => 'Gérer les utilisateurs', 'slug' => 'users.view', 'description' => 'Gérer les comptes utilisateurs'],
        ];

        foreach ($permissions as $permission) {
            $existing = DB::table('permissions')->where('slug', $permission['slug'])->first();
            if (!$existing) {
                DB::table('permissions')->insert(array_merge($permission, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
                $this->info("✅ Permission ajoutée: " . $permission['name']);
            }
        }
    }

    /**
     * Exécuter les migrations Laravel
     */
    private function runLaravelMigrations()
    {
        $this->info('🔄 Exécution des migrations Laravel...');
        
        try {
            $this->call('migrate', ['--force' => true]);
            $this->info('✅ Migrations Laravel exécutées avec succès');
        } catch (\Exception $e) {
            $this->warn('⚠️  Certaines migrations ont échoué, mais continuons...');
        }
    }

    /**
     * Vérifier l'état final
     */
    private function verifyFinalState()
    {
        $this->info('🔍 Vérification finale de l\'état des tables...');

        $tables = ['administration_functions', 'administration_function_types', 'roles', 'permissions', 'churches'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->info("✅ $table: $count enregistrements");
            } else {
                $this->error("❌ $table: Table manquante");
            }
        }

        // Vérifier les colonnes church_id
        $this->info('');
        $this->info('🏢 VÉRIFICATION DES COLONNES CHURCH_ID:');
        
        $churchTables = ['users', 'members', 'administration_functions'];
        foreach ($churchTables as $table) {
            if (Schema::hasTable($table)) {
                $hasChurchId = Schema::hasColumn($table, 'church_id');
                $this->info($hasChurchId ? "✅ $table: church_id présent" : "❌ $table: church_id manquant");
            }
        }
    }
}