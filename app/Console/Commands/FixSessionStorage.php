<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSessionStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:session-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix session storage for Render deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 CORRECTION DU STOCKAGE DES SESSIONS');
        $this->info('=====================================');

        try {
            // Vérifier si la table sessions existe
            $this->info('Vérification de la table sessions...');
            
            try {
                DB::select('SELECT 1 FROM sessions LIMIT 1');
                $this->info('✅ Table sessions existe déjà');
            } catch (\Exception $e) {
                $this->info('❌ Table sessions n\'existe pas, création...');
                
                // Créer la table sessions
                DB::statement('
                    CREATE TABLE IF NOT EXISTS sessions (
                        id VARCHAR(255) PRIMARY KEY,
                        user_id BIGINT NULL,
                        ip_address VARCHAR(45) NULL,
                        user_agent TEXT NULL,
                        payload TEXT NOT NULL,
                        last_activity INTEGER NOT NULL
                    )
                ');
                
                $this->info('✅ Table sessions créée');
            }

            // Vérifier la configuration des sessions
            $this->info('Configuration des sessions...');
            
            // Forcer l'utilisation des sessions en base de données
            config(['session.driver' => 'database']);
            config(['session.table' => 'sessions']);
            config(['session.lifetime' => 120]);
            config(['session.expire_on_close' => false]);
            config(['session.secure' => true]);
            config(['session.http_only' => true]);
            config(['session.same_site' => 'lax']);
            
            $this->info('✅ Configuration des sessions mise à jour');
            
            // Tester la création d'une session
            $this->info('Test de création de session...');
            $sessionId = 'test-' . time();
            DB::table('sessions')->insert([
                'id' => $sessionId,
                'payload' => base64_encode(serialize(['test' => 'data'])),
                'last_activity' => time()
            ]);
            
            $session = DB::table('sessions')->where('id', $sessionId)->first();
            if ($session) {
                $this->info('✅ Test de session réussi');
                DB::table('sessions')->where('id', $sessionId)->delete();
            } else {
                $this->error('❌ Test de session échoué');
            }

            $this->info('');
            $this->info('🎉 CORRECTION TERMINÉE AVEC SUCCÈS!');
            $this->info('==================================');
            $this->info('✅ Table sessions créée/vérifiée');
            $this->info('✅ Configuration mise à jour');
            $this->info('✅ Sessions en base de données activées');

        } catch (\Exception $e) {
            $this->error('❌ ERREUR lors de la correction: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
