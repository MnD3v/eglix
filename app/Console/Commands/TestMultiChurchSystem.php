<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Church;

class TestMultiChurchSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:multi-church';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the multi-church system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Test du Système Multi-Églises ===');
        $this->line('');

        try {
            // Test 1: Vérifier la table user_churches
            $this->info('1. Vérification de la table user_churches...');
            $exists = DB::getSchemaBuilder()->hasTable('user_churches');
            $this->line($exists ? '✅ Table user_churches existe' : '❌ Table user_churches n\'existe pas');

            // Test 2: Compter les associations
            $this->line('');
            $this->info('2. Vérification des associations...');
            $associationCount = DB::table('user_churches')->count();
            $this->line("✅ Nombre d'associations: {$associationCount}");

            // Test 3: Tester un utilisateur
            $this->line('');
            $this->info('3. Test d\'un utilisateur...');
            $user = User::first();
            if ($user) {
                $this->line("✅ Premier utilisateur: {$user->name} (ID: {$user->id})");
                
                $churches = $user->churches()->get();
                $this->line("✅ Églises de {$user->name}: " . $churches->count());
                
                $currentChurch = $user->getCurrentChurch();
                if ($currentChurch) {
                    $this->line("✅ Église courante: {$currentChurch->name}");
                } else {
                    $this->warn('⚠️  Aucune église courante définie');
                }
            }

            $this->line('');
            $this->info('=== Test terminé avec succès ===');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
