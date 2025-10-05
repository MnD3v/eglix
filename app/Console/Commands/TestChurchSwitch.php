<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ChurchSwitchController;

class TestChurchSwitch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:church-switch {email} {church_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test church switching functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $churchId = (int) $this->argument('church_id');

        $this->info("Testing church switch for: {$email} to church ID: {$churchId}");

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $this->info("User found: {$user->name}");

        // Check current church
        $currentChurch = $user->getCurrentChurch();
        if ($currentChurch) {
            $this->info("Current church: {$currentChurch->name} (ID: {$currentChurch->id})");
        } else {
            $this->warn("No current church set");
        }

        // Check if user has access to the target church
        if (!$user->hasAccessToChurch($churchId)) {
            $this->error("User does not have access to church ID: {$churchId}");
            return 1;
        }

        $this->info("✅ User has access to church ID: {$churchId}");

        // Test the switch method
        $this->info("Testing church switch...");

        // Create a mock request
        $request = new Request();
        $request->merge(['church_id' => $churchId]);

        // Authenticate the user
        Auth::login($user);

        try {
            // Create ChurchSwitchController instance
            $controller = new ChurchSwitchController();
            
            // Test the switch method
            $response = $controller->switch($request);
            
            $this->info("Switch method executed");
            
            // Check if church was switched
            $newCurrentChurch = $user->getCurrentChurch();
            if ($newCurrentChurch && $newCurrentChurch->id == $churchId) {
                $this->info("✅ Church switch successful: {$newCurrentChurch->name}");
                $this->info("✅ Church switch test PASSED");
                return 0;
            } else {
                $this->error("❌ Church switch failed");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error during church switch: " . $e->getMessage());
            return 1;
        } finally {
            Auth::logout();
        }
    }
}
