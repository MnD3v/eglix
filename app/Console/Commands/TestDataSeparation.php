<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tithe;
use App\Models\Member;

class TestDataSeparation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:data-separation {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test data separation between churches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Testing data separation for: {$email}");

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $this->info("User found: {$user->name}");
        $this->info("Churches associated: {$user->churches()->count()}");

        // Test each church
        foreach ($user->churches()->get() as $church) {
            $this->line("");
            $this->info("=== Testing Church: {$church->name} (ID: {$church->id}) ===");
            
            // Set current church
            $user->setCurrentChurch($church->id);
            
            // Test get_current_church_id function
            $currentChurchId = get_current_church_id();
            $this->info("get_current_church_id(): " . ($currentChurchId ?: 'null'));
            
            // Count members for this church
            $memberCount = Member::where('church_id', $church->id)->count();
            $this->info("Members in this church: {$memberCount}");
            
            // Count tithes for this church
            $titheCount = Tithe::where('church_id', $church->id)->count();
            $this->info("Tithes in this church: {$titheCount}");
            
            // Show some tithes
            if ($titheCount > 0) {
                $this->info("Sample tithes:");
                Tithe::where('church_id', $church->id)->take(3)->get()->each(function($tithe) {
                    $this->line("  - ID: {$tithe->id} - Amount: {$tithe->amount} - Date: {$tithe->paid_at}");
                });
            }
        }

        $this->line("");
        $this->info("=== Data Separation Test Complete ===");
        return 0;
    }
}
