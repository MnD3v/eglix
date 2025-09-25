<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Church;
use App\Services\ChurchIdEncryptionService;

class GenerateSecureRegistrationLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:secure-links {church_id? : ID de l\'église spécifique}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère des liens d\'inscription sécurisés pour les églises';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $churchId = $this->argument('church_id');
        
        if ($churchId) {
            $church = Church::find($churchId);
            if (!$church) {
                $this->error("Église avec l'ID {$churchId} non trouvée.");
                return;
            }
            
            $this->generateLinkForChurch($church);
        } else {
            $churches = Church::where('is_active', true)->get();
            
            if ($churches->isEmpty()) {
                $this->info('Aucune église active trouvée.');
                return;
            }
            
            $this->info("Génération des liens sécurisés pour {$churches->count()} église(s) :");
            $this->newLine();
            
            foreach ($churches as $church) {
                $this->generateLinkForChurch($church);
            }
        }
    }
    
    private function generateLinkForChurch(Church $church)
    {
        $secureLink = ChurchIdEncryptionService::generateRegistrationLink($church->id);
        
        $this->line("🏛️  <fg=blue>{$church->name}</fg=blue>");
        $this->line("   ID: {$church->id}");
        $this->line("   Slug: {$church->slug}");
        $this->line("   Lien sécurisé: <fg=green>{$secureLink}</fg=green>");
        $this->newLine();
    }
}
