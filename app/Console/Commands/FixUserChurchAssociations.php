<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Church;

class FixUserChurchAssociations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-church-associations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix user-church associations for users without churches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Correction des Associations Utilisateur-Église ===');
        $this->line('');

        // Trouver les utilisateurs sans église
        $usersWithoutChurches = User::whereDoesntHave('churches')->get();
        
        if ($usersWithoutChurches->isEmpty()) {
            $this->info('✅ Tous les utilisateurs ont déjà des églises associées.');
            return 0;
        }

        $this->info('Utilisateurs sans église trouvés : ' . $usersWithoutChurches->count());
        $this->line('');

        // Trouver les églises récentes (créées dans les dernières 24h)
        $recentChurches = Church::where('created_at', '>=', now()->subDay())->get();
        
        if ($recentChurches->isEmpty()) {
            $this->warn('Aucune église récente trouvée. Utilisation de toutes les églises disponibles.');
            $recentChurches = Church::all();
        }

        $this->info('Églises disponibles : ' . $recentChurches->count());
        $this->line('');

        $fixed = 0;
        foreach ($usersWithoutChurches as $user) {
            // Essayer de trouver une église correspondant au nom de l'utilisateur
            $matchingChurch = null;
            
            if ($user->church_name) {
                $matchingChurch = Church::where('name', 'LIKE', '%' . $user->church_name . '%')->first();
            }
            
            // Si pas de correspondance, prendre la première église récente
            if (!$matchingChurch) {
                $matchingChurch = $recentChurches->first();
            }
            
            if ($matchingChurch) {
                // Associer l'utilisateur à l'église
                $user->churches()->attach($matchingChurch->id, [
                    'is_primary' => true,
                    'is_active' => true,
                ]);
                
                // Définir l'église courante
                $user->setCurrentChurch($matchingChurch->id);
                
                $this->info("✅ {$user->name} ({$user->email}) associé à {$matchingChurch->name}");
                $fixed++;
            } else {
                $this->error("❌ Impossible d'associer {$user->name} ({$user->email}) - aucune église disponible");
            }
        }

        $this->line('');
        $this->info("=== Résumé ===");
        $this->info("Utilisateurs corrigés : {$fixed}");
        $this->info("Utilisateurs restants sans église : " . ($usersWithoutChurches->count() - $fixed));

        return 0;
    }
}
