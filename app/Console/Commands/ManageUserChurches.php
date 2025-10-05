<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Church;

class ManageUserChurches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:manage-churches {action} {user_id} {church_id?} {--primary : Set as primary church}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage user church associations (add, remove, list, set-primary)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $userId = $this->argument('user_id');
        $churchId = $this->argument('church_id');
        $isPrimary = $this->option('primary');

        // Vérifier que l'utilisateur existe
        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return 1;
        }

        switch ($action) {
            case 'list':
                return $this->listUserChurches($user);
                
            case 'add':
                if (!$churchId) {
                    $this->error("Church ID is required for 'add' action.");
                    return 1;
                }
                return $this->addUserToChurch($user, $churchId, $isPrimary);
                
            case 'remove':
                if (!$churchId) {
                    $this->error("Church ID is required for 'remove' action.");
                    return 1;
                }
                return $this->removeUserFromChurch($user, $churchId);
                
            case 'set-primary':
                if (!$churchId) {
                    $this->error("Church ID is required for 'set-primary' action.");
                    return 1;
                }
                return $this->setPrimaryChurch($user, $churchId);
                
            default:
                $this->error("Invalid action. Available actions: list, add, remove, set-primary");
                return 1;
        }
    }

    /**
     * List user's churches
     */
    private function listUserChurches(User $user)
    {
        $this->info("Churches for user: {$user->name} (ID: {$user->id})");
        $this->line("");

        $churches = $user->churches()->withPivot(['is_primary', 'is_active'])->get();

        if ($churches->isEmpty()) {
            $this->warn("No churches assigned to this user.");
            return 0;
        }

        $headers = ['ID', 'Name', 'Primary', 'Active', 'Added Date'];
        $rows = [];

        foreach ($churches as $church) {
            $rows[] = [
                $church->id,
                $church->name,
                $church->pivot->is_primary ? 'Yes' : 'No',
                $church->pivot->is_active ? 'Yes' : 'No',
                $church->pivot->created_at->format('Y-m-d H:i:s')
            ];
        }

        $this->table($headers, $rows);
        return 0;
    }

    /**
     * Add user to church
     */
    private function addUserToChurch(User $user, int $churchId, bool $isPrimary)
    {
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

        // Si c'est défini comme principale, retirer le statut principal des autres
        if ($isPrimary) {
            $user->churches()->updateExistingPivot(
                $user->churches()->pluck('id')->toArray(),
                ['is_primary' => false]
            );
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

    /**
     * Remove user from church
     */
    private function removeUserFromChurch(User $user, int $churchId)
    {
        // Vérifier que l'église existe
        $church = Church::find($churchId);
        if (!$church) {
            $this->error("Church with ID {$churchId} not found.");
            return 1;
        }

        // Vérifier que l'utilisateur a accès à cette église
        if (!$user->hasAccessToChurch($churchId)) {
            $this->warn("User {$user->name} does not have access to church {$church->name}.");
            return 0;
        }

        // Vérifier que ce n'est pas la seule église de l'utilisateur
        if ($user->churches()->count() <= 1) {
            $this->error("Cannot remove the last church from user. User must have at least one church.");
            return 1;
        }

        // Retirer l'accès
        $user->churches()->detach($churchId);

        // Si c'était l'église principale, définir une autre comme principale
        $remainingChurches = $user->churches()->get();
        if ($remainingChurches->count() > 0) {
            $user->churches()->updateExistingPivot($remainingChurches->first()->id, [
                'is_primary' => true,
                'updated_at' => now(),
            ]);
            $this->info("Church {$remainingChurches->first()->name} has been set as the new primary church.");
        }

        $this->info("User {$user->name} has been removed from church {$church->name}.");
        return 0;
    }

    /**
     * Set primary church
     */
    private function setPrimaryChurch(User $user, int $churchId)
    {
        // Vérifier que l'église existe
        $church = Church::find($churchId);
        if (!$church) {
            $this->error("Church with ID {$churchId} not found.");
            return 1;
        }

        // Vérifier que l'utilisateur a accès à cette église
        if (!$user->hasAccessToChurch($churchId)) {
            $this->error("User {$user->name} does not have access to church {$church->name}.");
            return 1;
        }

        // Retirer le statut principal de toutes les autres églises
        $user->churches()->updateExistingPivot(
            $user->churches()->pluck('id')->toArray(),
            ['is_primary' => false]
        );

        // Définir cette église comme principale
        $user->churches()->updateExistingPivot($churchId, [
            'is_primary' => true,
            'updated_at' => now(),
        ]);

        $this->info("Church {$church->name} has been set as the primary church for user {$user->name}.");
        return 0;
    }
}
