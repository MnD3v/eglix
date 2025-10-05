<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class TestSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:session {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test session functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Testing session for: {$email}");

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        // Test password
        if (!password_verify($password, $user->password)) {
            $this->error("Password is incorrect");
            return 1;
        }

        $this->info("✅ Password is correct");

        // Test session operations
        $this->info("Testing session operations...");

        // Set session value
        Session::put('test_key', 'test_value');
        $this->info("Session test_key set to: " . Session::get('test_key'));

        // Set church in session
        $user->setCurrentChurch($user->churches()->first()->id);
        $this->info("Church set in session: " . session('current_church_id'));

        // Test getCurrentChurch
        $currentChurch = $user->getCurrentChurch();
        if ($currentChurch) {
            $this->info("✅ getCurrentChurch works: {$currentChurch->name}");
        } else {
            $this->error("❌ getCurrentChurch failed");
        }

        return 0;
    }
}
