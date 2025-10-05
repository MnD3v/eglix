<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Church;

class MigrateUsersToMultiChurch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:migrate-to-multi-church';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing users to the multi-church system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of users to multi-church system...');

        // Vérifier si la table user_churches existe
        if (!DB::getSchemaBuilder()->hasTable('user_churches')) {
            $this->error('Table user_churches does not exist. Please run migrations first.');
            return 1;
        }

        // Vérifier si la colonne church_id existe encore
        if (DB::getSchemaBuilder()->hasColumn('users', 'church_id')) {
            $this->info('Migrating users with church_id...');
            
            $users = User::whereNotNull('church_id')->get();
            $migrated = 0;
            
            foreach ($users as $user) {
                // Vérifier si l'association existe déjà
                $exists = DB::table('user_churches')
                    ->where('user_id', $user->id)
                    ->where('church_id', $user->church_id)
                    ->exists();
                
                if (!$exists) {
                    DB::table('user_churches')->insert([
                        'user_id' => $user->id,
                        'church_id' => $user->church_id,
                        'is_primary' => true,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $migrated++;
                }
            }
            
            $this->info("Migrated {$migrated} user-church associations.");
        } else {
            $this->info('Column church_id already removed. Migration already completed.');
        }

        // Afficher les statistiques
        $totalUsers = User::count();
        $usersWithChurches = DB::table('user_churches')->distinct('user_id')->count();
        $totalAssociations = DB::table('user_churches')->count();

        $this->info("\nMigration completed!");
        $this->info("Total users: {$totalUsers}");
        $this->info("Users with church access: {$usersWithChurches}");
        $this->info("Total user-church associations: {$totalAssociations}");

        return 0;
    }
}
