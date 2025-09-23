<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;
use PDOException;

class TestPostgreSQLConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:postgresql-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester la connexion PostgreSQL avec SSL pour Laravel Cloud';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Test de connexion PostgreSQL pour Laravel Cloud...');
        
        try {
            // 1. Test de connexion basique
            $this->testBasicConnection();
            
            // 2. Test de connexion avec SSL
            $this->testSSLConnection();
            
            // 3. Test de requête simple
            $this->testSimpleQuery();
            
            // 4. Test de création de table
            $this->testTableCreation();
            
            $this->info('✅ Tous les tests PostgreSQL réussis !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors du test PostgreSQL: ' . $e->getMessage());
            Log::error('❌ Erreur TestPostgreSQLConnection: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Test de connexion basique
     */
    private function testBasicConnection()
    {
        $this->info('🔧 Test de connexion basique...');
        
        try {
            $pdo = DB::connection()->getPdo();
            $this->info('✅ Connexion PostgreSQL réussie');
            
            // Vérifier la version PostgreSQL
            $version = $pdo->query('SELECT version()')->fetchColumn();
            $this->info("📋 Version PostgreSQL: " . substr($version, 0, 50) . "...");
            
        } catch (PDOException $e) {
            $this->error('❌ Erreur de connexion: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Test de connexion avec SSL
     */
    private function testSSLConnection()
    {
        $this->info('🔒 Test de connexion SSL...');
        
        try {
            $pdo = DB::connection()->getPdo();
            
            // Vérifier le statut SSL
            $sslStatus = $pdo->query('SELECT ssl_is_used()')->fetchColumn();
            $this->info("🔒 SSL utilisé: " . ($sslStatus ? 'Oui' : 'Non'));
            
            if (!$sslStatus) {
                $this->warn('⚠️ SSL non utilisé - vérifiez la configuration');
            }
            
            // Vérifier les paramètres SSL
            $sslVersion = $pdo->query('SELECT ssl_version()')->fetchColumn();
            $this->info("🔒 Version SSL: " . ($sslVersion ?: 'Non disponible'));
            
        } catch (PDOException $e) {
            $this->error('❌ Erreur SSL: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Test de requête simple
     */
    private function testSimpleQuery()
    {
        $this->info('📊 Test de requête simple...');
        
        try {
            // Test de requête simple
            $result = DB::select('SELECT 1 as test_value');
            $this->info('✅ Requête simple réussie: ' . $result[0]->test_value);
            
            // Test de requête avec paramètres
            $result = DB::select('SELECT ? as param_test', ['Laravel Cloud']);
            $this->info('✅ Requête avec paramètres réussie: ' . $result[0]->param_test);
            
        } catch (PDOException $e) {
            $this->error('❌ Erreur de requête: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Test de création de table
     */
    private function testTableCreation()
    {
        $this->info('🏗️ Test de création de table...');
        
        try {
            // Créer une table de test
            DB::statement('
                CREATE TABLE IF NOT EXISTS test_connection (
                    id SERIAL PRIMARY KEY,
                    test_data VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ');
            $this->info('✅ Table de test créée');
            
            // Insérer des données de test
            DB::table('test_connection')->insert([
                'test_data' => 'Test Laravel Cloud Connection'
            ]);
            $this->info('✅ Données de test insérées');
            
            // Vérifier les données
            $count = DB::table('test_connection')->count();
            $this->info("📊 Nombre d'enregistrements: $count");
            
            // Nettoyer la table de test
            DB::statement('DROP TABLE IF EXISTS test_connection');
            $this->info('✅ Table de test nettoyée');
            
        } catch (PDOException $e) {
            $this->error('❌ Erreur de création de table: ' . $e->getMessage());
            throw $e;
        }
    }
}
