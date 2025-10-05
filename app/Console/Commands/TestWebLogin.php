<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

class TestWebLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:web-login {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test web login process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $this->info("Testing web login for: {$email}");

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $this->info("User found: {$user->name}");
        $this->info("Churches associated: {$user->churches()->count()}");

        if ($user->churches()->count() === 0) {
            $this->error("User has no churches associated!");
            return 1;
        }

        // Test password
        if (!password_verify($password, $user->password)) {
            $this->error("Password is incorrect");
            return 1;
        }

        $this->info("✅ Password is correct");

        // Simulate web login process
        $this->info("Simulating web login...");

        // Create a mock request
        $request = new Request();
        $request->merge([
            'email' => $email,
            'password' => $password,
        ]);

        // Create AuthController instance
        $authController = new AuthController();

        try {
            // Test the login method
            $response = $authController->login($request);
            
            $this->info("Login method executed");
            
            // Check if user is authenticated
            if (Auth::check()) {
                $this->info("✅ User is authenticated");
                
                $currentUser = Auth::user();
                $currentChurch = $currentUser->getCurrentChurch();
                
                if ($currentChurch) {
                    $this->info("✅ Current church: {$currentChurch->name}");
                } else {
                    $this->warn("⚠️ No current church set");
                }
                
                $sessionChurchId = session('current_church_id');
                $this->info("Session current_church_id: " . ($sessionChurchId ?: 'null'));
                
                Auth::logout();
                $this->info("✅ Web login test PASSED");
                return 0;
            } else {
                $this->error("❌ User is not authenticated");
                return 1;
            }
        } catch (\Exception $e) {
            $this->error("❌ Error during login: " . $e->getMessage());
            return 1;
        }
    }
}
