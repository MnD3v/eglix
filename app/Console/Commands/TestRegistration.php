<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Church;
use App\Models\Role;

class TestRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:registration {name} {email} {password} {church_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test registration process';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $churchName = $this->argument('church_name');

        $this->info("Testing registration for: {$name} ({$email})");
        $this->info("Church: {$churchName}");

        try {
            // Créer l'église
            $church = Church::create([
                'name' => $churchName,
                'description' => 'Église de test',
            ]);
            $this->info("✅ Église créée: {$church->name}");

            // Créer le rôle admin
            $timestamp = time();
            $random = substr(md5(uniqid(mt_rand(), true)), 0, 6);
            $slug = "administrateur-{$church->id}-{$timestamp}-{$random}";
            
            $adminRole = Role::create([
                'church_id' => $church->id,
                'name' => 'Administrateur',
                'slug' => $slug,
                'description' => 'Administrateur de l\'église avec tous les droits',
                'permissions' => array_keys(Role::getAvailablePermissions()),
            ]);
            $this->info("✅ Rôle admin créé");

            // Créer l'utilisateur
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_church_admin' => true,
                'is_active' => true,
            ]);
            $this->info("✅ Utilisateur créé: {$user->name}");

            // Associer l'utilisateur à l'église
            $user->churches()->attach($church->id, [
                'is_primary' => true,
                'is_active' => true,
            ]);
            $this->info("✅ Utilisateur associé à l'église");

            // Définir l'église courante
            $user->setCurrentChurch($church->id);
            $this->info("✅ Église courante définie");

            // Vérifier la connexion
            $credentials = ['email' => $email, 'password' => $password];
            if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
                $this->info("✅ Test de connexion réussi");
                \Illuminate\Support\Facades\Auth::logout();
            } else {
                $this->error("❌ Test de connexion échoué");
            }

            $this->info("=== Test d'inscription RÉUSSI ===");
            $this->info("Vous pouvez maintenant vous connecter avec:");
            $this->info("Email: {$email}");
            $this->info("Mot de passe: {$password}");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du test d'inscription: " . $e->getMessage());
            return 1;
        }
    }
}
