<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Church;

class DebugChurchSwitch extends Command
{
    protected $signature = 'debug:church-switch {user_id} {church_id}';
    protected $description = 'Debug church switching for a specific user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $churchId = $this->argument('church_id');

        $user = User::find($userId);
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        $church = Church::find($churchId);
        if (!$church) {
            $this->error("Church with ID {$churchId} not found");
            return;
        }

        $this->info("=== DEBUG CHURCH SWITCH ===");
        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Target Church: {$church->name}");

        // Vérifier l'accès
        $hasAccess = $user->hasAccessToChurch($churchId);
        $this->info("Has Access: " . ($hasAccess ? 'YES' : 'NO'));

        if (!$hasAccess) {
            $this->error("User doesn't have access to this church!");
            return;
        }

        // Église courante avant
        $currentChurchBefore = $user->getCurrentChurch();
        $this->info("Current Church Before: " . ($currentChurchBefore ? $currentChurchBefore->name : 'None'));

        // Simuler le changement
        $this->info("\n--- Simulating Church Switch ---");
        $result = $user->setCurrentChurch($churchId);
        $this->info("Switch Result: " . ($result ? 'SUCCESS' : 'FAILED'));

        // Église courante après
        $currentChurchAfter = $user->getCurrentChurch();
        $this->info("Current Church After: " . ($currentChurchAfter ? $currentChurchAfter->name : 'None'));

        // Vérifier la session
        $sessionChurchId = session('current_church_id');
        $this->info("Session current_church_id: " . ($sessionChurchId ?? 'NULL'));

        // Vérifier les églises actives
        $activeChurches = $user->activeChurches()->get();
        $this->info("\nActive Churches:");
        foreach ($activeChurches as $church) {
            $isCurrent = $church->id == $sessionChurchId;
            $this->info("- {$church->name} (ID: {$church->id})" . ($isCurrent ? ' [CURRENT]' : ''));
        }

        // Vérifier l'église principale
        $primaryChurch = $user->primaryChurch()->first();
        $this->info("Primary Church: " . ($primaryChurch ? $primaryChurch->name : 'None'));

        $this->info("\n=== END DEBUG ===");
    }
}
