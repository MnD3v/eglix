<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Church;
use App\Services\ChurchIdEncryptionService;

class GenerateRegistrationLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'churches:generate-registration-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate secure registration links for all active churches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Génération des Liens d\'Inscription Sécurisés ===');
        $this->line('');

        $churches = Church::where('is_active', true)->orderBy('name')->get();

        if ($churches->isEmpty()) {
            $this->warn('Aucune église active trouvée.');
            return 0;
        }

        $this->info('Églises actives trouvées : ' . $churches->count());
        $this->line('');

        $headers = ['ID', 'Nom de l\'Église', 'Lien d\'Inscription Sécurisé'];
        $rows = [];

        foreach ($churches as $church) {
            $secureLink = ChurchIdEncryptionService::generateRegistrationLink($church->id);
            
            $rows[] = [
                $church->id,
                $church->name,
                $secureLink
            ];
        }

        $this->table($headers, $rows);

        $this->line('');
        $this->info('=== Instructions d\'Utilisation ===');
        $this->line('1. Copiez le lien d\'inscription de l\'église souhaitée');
        $this->line('2. Partagez ce lien avec les membres de cette église');
        $this->line('3. Les membres pourront s\'inscrire directement via ce lien');
        $this->line('');
        $this->warn('⚠️  Ces liens sont sécurisés et chiffrés. Ne les modifiez pas manuellement.');

        return 0;
    }
}
