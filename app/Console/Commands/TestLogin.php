<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Testing login for: {$email}");

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $this->info("User found: {$user->name}");

        // Check churches
        $churchesCount = $user->churches()->count();
        $this->info("Churches associated: {$churchesCount}");

        if ($churchesCount === 0) {
            $this->error("User has no churches associated!");
            return 1;
        }

        // Check current church
        $currentChurch = $user->getCurrentChurch();
        if (!$currentChurch) {
            $this->warn("No current church set, setting primary church...");
            $primaryChurch = $user->primaryChurch()->first();
            if ($primaryChurch) {
                $user->setCurrentChurch($primaryChurch->id);
                $this->info("Current church set to: {$primaryChurch->name}");
            } else {
                $firstChurch = $user->activeChurches()->first();
                if ($firstChurch) {
                    $user->setCurrentChurch($firstChurch->id);
                    $this->info("Current church set to: {$firstChurch->name}");
                }
            }
        } else {
            $this->info("Current church: {$currentChurch->name}");
        }

        // Test password
        if (password_verify($password, $user->password)) {
            $this->info("✅ Password is correct");
            
            // Test Auth::attempt
            $credentials = ['email' => $email, 'password' => $password];
            if (Auth::attempt($credentials)) {
                $this->info("✅ Auth::attempt successful");
                $this->info("✅ Login test PASSED");
                Auth::logout();
                return 0;
            } else {
                $this->error("❌ Auth::attempt failed");
                return 1;
            }
        } else {
            $this->error("❌ Password is incorrect");
            return 1;
        }
    }
}
