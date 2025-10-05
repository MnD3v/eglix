<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Church;

class AddUserToChurch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add-to-church {user_id} {church_id} {--primary : Set as primary church}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a user to a church';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $churchId = $this->argument('church_id');
        $isPrimary = $this->option('primary');

        // Vérifier que l'utilisateur existe
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        // Vérifier que l'église existe
        $church = Church::find($churchId);
        if (!$church) {
            $this->error("Church with ID {$churchId} not found.");
            return 1;
        }

        // Vérifier si l'association existe déjà
        if ($user->hasAccessToChurch($churchId)) {
            $this->warn("User {$user->name} already has access to church {$church->name}.");
            return 0;
        }

        // Ajouter l'association
        $user->churches()->attach($churchId, [
            'is_primary' => $isPrimary,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("User {$user->name} has been added to church {$church->name}.");
        
        if ($isPrimary) {
            $this->info("This church has been set as the primary church for this user.");
        }

        return 0;
    }
}
