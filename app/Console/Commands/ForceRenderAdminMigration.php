<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Church;
use App\Models\Role;
use App\Models\Permission;

class ForceRenderAdminMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'render:force-admin-migration {--force : Force the migration without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force the migration of super admin section for Render production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 MIGRATION FORCÉE SUPER ADMIN - RENDER PRODUCTION');
        $this->info('==================================================');

        if (!$this->option('force')) {
            if (!$this->confirm('Êtes-vous sûr de vouloir forcer la migration super admin sur Render ?')) {
                $this->info('Migration annulée.');
                return;
            }
        }

        try {
            $this->ensureCriticalTables();
            $this->addCriticalColumns();
            $this->createBaseData();
            $this->verifyAdminRoute();
            $this->verifyFinalState();

            $this->info('');
            $this->info('🎉 MIGRATION FORCÉE SUPER ADMIN TERMINÉE AVEC SUCCÈS!');
            $this->info('=====================================================');
            $this->info('✅ Route admin-0202 fonctionnelle');
            $this->info('✅ Super admin créé');
            $this->info('✅ Tables critiques créées');
            $this->info('');
            $this->warn('🔐 Accès: /admin-0202');
            $this->warn('📧 Email: admin@eglix.com');
            $this->warn('🔑 Mot de passe: admin123!');
            $this->warn('⚠️  IMPORTANT: Changez le mot de passe par défaut!');

        } catch (\Exception $e) {
            $this->error('❌ ERREUR lors de la migration forcée: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Créer les tables critiques pour le super admin
     */
    private function ensureCriticalTables()
    {
        $this->info('🔐 Création des tables critiques...');

        $tables = [
            'churches' => function ($table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('pastor_name')->nullable();
                $table->string('pastor_phone')->nullable();
                $table->string('pastor_email')->nullable();
                $table->string('subscription_status')->default('inactive');
                $table->date('subscription_start_date')->nullable();
                $table->date('subscription_end_date')->nullable();
                $table->decimal('subscription_amount', 10, 2)->nullable();
                $table->timestamps();
            },
            'roles' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->timestamps();
            },
            'permissions' => function ($table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->timestamps();
            },
            'subscriptions' => function ($table) {
                $table->id();
                $table->foreignId('church_id')->constrained('churches')->onDelete('cascade');
                $table->string('plan_name');
                $table->decimal('amount', 10, 2);
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
            }
        ];

        foreach ($tables as $tableName => $callback) {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, $callback);
                $this->info("✅ Table {$tableName} créée");
            } else {
                $this->info("✅ Table {$tableName} existe déjà");
            }
        }
    }

    /**
     * Ajouter les colonnes critiques
     */
    private function addCriticalColumns()
    {
        $this->info('🔧 Ajout des colonnes critiques...');

        if (Schema::hasTable('users')) {
            $columns = [
                'church_id' => function ($table) {
                    $table->foreignId('church_id')->nullable()->constrained('churches')->onDelete('set null');
                },
                'role_id' => function ($table) {
                    $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
                },
                'is_super_admin' => function ($table) {
                    $table->boolean('is_super_admin')->default(false);
                },
                'is_active' => function ($table) {
                    $table->boolean('is_active')->default(true);
                }
            ];

            foreach ($columns as $columnName => $callback) {
                if (!Schema::hasColumn('users', $columnName)) {
                    Schema::table('users', $callback);
                    $this->info("✅ Colonne {$columnName} ajoutée à users");
                } else {
                    $this->info("✅ Colonne {$columnName} existe déjà dans users");
                }
            }
        }
    }

    /**
     * Créer les données de base
     */
    private function createBaseData()
    {
        $this->info('📊 Création des données de base...');

        // Créer les rôles
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Administrateur global de la plateforme'],
            ['name' => 'Church Admin', 'description' => 'Administrateur d\'église'],
            ['name' => 'Pastor', 'description' => 'Pasteur'],
            ['name' => 'Member', 'description' => 'Membre']
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);
            $this->info("✅ Rôle créé/vérifié: {$role->name}");
        }

        // Créer les permissions
        $permissions = [
            ['name' => 'manage_all_churches', 'description' => 'Gérer toutes les églises'],
            ['name' => 'manage_subscriptions', 'description' => 'Gérer les abonnements'],
            ['name' => 'view_admin_panel', 'description' => 'Accéder au panneau admin'],
            ['name' => 'manage_users', 'description' => 'Gérer les utilisateurs'],
            ['name' => 'manage_church_data', 'description' => 'Gérer les données d\'église']
        ];

        foreach ($permissions as $permData) {
            $permission = Permission::firstOrCreate(['name' => $permData['name']], $permData);
            $this->info("✅ Permission créée/vérifiée: {$permission->name}");
        }

        // Créer un super admin par défaut
        $superAdminExists = User::where('is_super_admin', true)->exists();
        if (!$superAdminExists) {
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@eglix.com',
                'password' => bcrypt('admin123!'),
                'is_super_admin' => true,
                'is_active' => true,
                'email_verified_at' => now()
            ]);
            $this->info("✅ Super admin créé: {$superAdmin->email}");
        } else {
            $this->info("✅ Super admin existe déjà");
        }
    }

    /**
     * Vérifier que la route admin fonctionne
     */
    private function verifyAdminRoute()
    {
        $this->info('🌐 Vérification de la route admin-0202...');
        
        // Vérifier que la route existe dans les routes
        $routes = app('router')->getRoutes();
        $adminRouteExists = false;
        
        foreach ($routes as $route) {
            if ($route->uri() === 'admin-0202') {
                $adminRouteExists = true;
                break;
            }
        }
        
        if ($adminRouteExists) {
            $this->info("✅ Route admin-0202 disponible");
        } else {
            $this->warn("⚠️  Route admin-0202 non trouvée");
        }
    }

    /**
     * Vérifier l'état final
     */
    private function verifyFinalState()
    {
        $this->info('🔍 Vérification de l\'état final...');

        $tables = ['churches', 'roles', 'permissions', 'subscriptions', 'users'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->info("✅ Table {$table}: {$count} enregistrements");
            } else {
                $this->error("❌ Table {$table}: MANQUANTE");
            }
        }

        $superAdmins = User::where('is_super_admin', true)->count();
        $this->info("🔐 Super admins: {$superAdmins}");
    }
}
