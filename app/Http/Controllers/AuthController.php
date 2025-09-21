<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Log pour diagnostiquer les problèmes de session
            \Log::info('Connexion réussie pour: ' . $request->email);
            \Log::info('Session ID après connexion: ' . $request->session()->getId());
            \Log::info('User ID connecté: ' . Auth::id());
            
            return redirect()->intended('/')->with('success', 'Connexion réussie !');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'])
            ->withInput($request->except('password'));
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        // FORCER L'AJOUT DES COLONNES USERS EN PREMIER
        $this->ensureUsersTableIsComplete();

        // FORCER L'EXÉCUTION DES MIGRATIONS SI NÉCESSAIRE
        $this->ensureMigrationsAreRun();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'church_name' => 'required|string|max:255',
            'church_description' => 'nullable|string',
        ], [
            'name.required' => 'Le nom est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'church_name.required' => 'Le nom de l\'église est requis.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Créer l'église
        try {
            $church = \App\Models\Church::create([
                'name' => $request->church_name,
                'description' => $request->church_description,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['church_name' => 'Erreur lors de la création de l\'église: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Créer le rôle admin pour cette église
        try {
            // Générer un slug vraiment unique pour ce rôle
            $timestamp = time();
            $random = substr(md5(uniqid(mt_rand(), true)), 0, 6);
            $slug = "administrateur-{$church->id}-{$timestamp}-{$random}";
            
            $adminRole = \App\Models\Role::create([
                'church_id' => $church->id,
                'name' => 'Administrateur',
                'slug' => $slug,
                'description' => 'Administrateur de l\'église avec tous les droits',
                'permissions' => array_keys(\App\Models\Role::getAvailablePermissions()),
            ]);
        } catch (\Exception $e) {
            $church->delete(); // Supprimer l'église créée en cas d'erreur
            return redirect()->back()
                ->withErrors(['church_name' => 'Erreur lors de la création du rôle administrateur: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Créer l'utilisateur admin
        try {
            $user = $this->createUserSafely([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'church_id' => $church->id,
                'role_id' => $adminRole->id,
                'is_church_admin' => true,
                'is_active' => true,
            ]);
        } catch (\Exception $e) {
            $church->delete(); // Supprimer l'église et le rôle en cas d'erreur
            $adminRole->delete();
            return redirect()->back()
                ->withErrors(['email' => 'Erreur lors de la création de l\'utilisateur administrateur: ' . $e->getMessage()])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        Auth::login($user);

        return redirect('/')->with('success', 'Compte et église créés avec succès ! Vous êtes maintenant administrateur de votre église.');
    }

    /**
     * S'assurer que la table users a toutes les colonnes nécessaires
     */
    private function ensureUsersTableIsComplete()
    {
        try {
            \Log::info('Vérification des colonnes de la table users...');

            // Forcer l'ajout de chaque colonne
            $this->addColumnIfNotExists('users', 'church_id', 'BIGINT');
            $this->addColumnIfNotExists('users', 'role_id', 'BIGINT');
            $this->addColumnIfNotExists('users', 'is_church_admin', 'BOOLEAN DEFAULT false');
            $this->addColumnIfNotExists('users', 'is_active', 'BOOLEAN DEFAULT true');

            \Log::info('Vérification des colonnes users terminée');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification des colonnes users: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter une colonne si elle n'existe pas
     */
    private function addColumnIfNotExists($table, $column, $type)
    {
        try {
            if (!$this->columnExists($table, $column)) {
                \DB::statement("ALTER TABLE {$table} ADD COLUMN {$column} {$type}");
                \Log::info("Colonne {$column} ajoutée à {$table}");
            } else {
                \Log::info("Colonne {$column} existe déjà dans {$table}");
            }
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'ajout de la colonne {$column}: " . $e->getMessage());
        }
    }

    /**
     * S'assurer que les migrations sont exécutées
     */
    private function ensureMigrationsAreRun()
    {
        try {
            // Vérifier si la table churches existe
            \DB::select('SELECT 1 FROM churches LIMIT 1');
        } catch (\Exception $e) {
            // La table n'existe pas, exécuter SEULEMENT les migrations en attente
            try {
                \Artisan::call('migrate', ['--force' => true, '--no-interaction' => true]);
                \Log::info('Migrations exécutées automatiquement lors de l\'inscription');
            } catch (\Exception $migrationError) {
                \Log::error('Erreur lors des migrations: ' . $migrationError->getMessage());
                // Si les migrations échouent, essayer une approche différente
                $this->createMissingTables();
            }
        }
    }

    /**
     * Créer seulement les tables manquantes
     */
    private function createMissingTables()
    {
        try {
            // Vérifier et créer la table churches si elle n'existe pas
            if (!$this->tableExists('churches')) {
                \DB::statement('
                    CREATE TABLE IF NOT EXISTS churches (
                        id BIGSERIAL PRIMARY KEY,
                        name VARCHAR(255) NOT NULL,
                        slug VARCHAR(255) UNIQUE NOT NULL,
                        description TEXT,
                        address VARCHAR(255),
                        phone VARCHAR(20),
                        email VARCHAR(255),
                        website VARCHAR(255),
                        logo VARCHAR(255),
                        settings JSONB,
                        is_active BOOLEAN DEFAULT true,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP
                    )
                ');
            }

            // Vérifier et créer la table roles si elle n'existe pas
            if (!$this->tableExists('roles')) {
                \DB::statement('
                    CREATE TABLE IF NOT EXISTS roles (
                        id BIGSERIAL PRIMARY KEY,
                        church_id BIGINT NOT NULL,
                        name VARCHAR(255) NOT NULL,
                        slug VARCHAR(255) NOT NULL,
                        description TEXT,
                        permissions JSONB,
                        is_active BOOLEAN DEFAULT true,
                        created_at TIMESTAMP,
                        updated_at TIMESTAMP,
                        FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE,
                        UNIQUE (church_id, slug)
                    )
                ');
            }

            // Ajouter les colonnes manquantes à la table users
            $this->addMissingUsersColumns();

            \Log::info('Tables critiques créées manuellement');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création manuelle des tables: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter les colonnes manquantes à la table users
     */
    private function addMissingUsersColumns()
    {
        try {
            // Vérifier et ajouter church_id
            if (!$this->columnExists('users', 'church_id')) {
                \DB::statement('ALTER TABLE users ADD COLUMN church_id BIGINT');
                \Log::info('Colonne church_id ajoutée à users');
            }

            // Vérifier et ajouter role_id
            if (!$this->columnExists('users', 'role_id')) {
                \DB::statement('ALTER TABLE users ADD COLUMN role_id BIGINT');
                \Log::info('Colonne role_id ajoutée à users');
            }

            // Vérifier et ajouter is_church_admin
            if (!$this->columnExists('users', 'is_church_admin')) {
                \DB::statement('ALTER TABLE users ADD COLUMN is_church_admin BOOLEAN DEFAULT false');
                \Log::info('Colonne is_church_admin ajoutée à users');
            }

            // Vérifier et ajouter is_active
            if (!$this->columnExists('users', 'is_active')) {
                \DB::statement('ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT true');
                \Log::info('Colonne is_active ajoutée à users');
            }

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'ajout des colonnes à users: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier si une colonne existe dans une table
     */
    private function columnExists($table, $column)
    {
        try {
            $result = \DB::select("
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = ? AND column_name = ?
            ", [$table, $column]);
            
            return count($result) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Vérifier si une table existe
     */
    private function tableExists($tableName)
    {
        try {
            \DB::select("SELECT 1 FROM {$tableName} LIMIT 1");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Créer un utilisateur de manière sécurisée
     */
    private function createUserSafely(array $data)
    {
        // Vérifier d'abord que toutes les colonnes existent
        $this->ensureUsersTableIsComplete();

        // Filtrer les données selon les colonnes existantes
        $filteredData = [];
        $requiredColumns = ['name', 'email', 'password'];
        $optionalColumns = ['church_id', 'role_id', 'is_church_admin', 'is_active'];

        // Toujours inclure les colonnes requises
        foreach ($requiredColumns as $column) {
            if (isset($data[$column])) {
                $filteredData[$column] = $data[$column];
            }
        }

        // N'inclure les colonnes optionnelles que si elles existent
        foreach ($optionalColumns as $column) {
            if (isset($data[$column]) && $this->columnExists('users', $column)) {
                $filteredData[$column] = $data[$column];
            }
        }

        // Créer l'utilisateur avec les données filtrées
        $user = User::create($filteredData);

        // Si certaines colonnes n'existaient pas, les ajouter maintenant
        if (isset($data['church_id']) && !isset($filteredData['church_id'])) {
            $this->addColumnIfNotExists('users', 'church_id', 'BIGINT');
            $user->update(['church_id' => $data['church_id']]);
        }

        if (isset($data['role_id']) && !isset($filteredData['role_id'])) {
            $this->addColumnIfNotExists('users', 'role_id', 'BIGINT');
            $user->update(['role_id' => $data['role_id']]);
        }

        if (isset($data['is_church_admin']) && !isset($filteredData['is_church_admin'])) {
            $this->addColumnIfNotExists('users', 'is_church_admin', 'BOOLEAN DEFAULT false');
            $user->update(['is_church_admin' => $data['is_church_admin']]);
        }

        if (isset($data['is_active']) && !isset($filteredData['is_active'])) {
            $this->addColumnIfNotExists('users', 'is_active', 'BOOLEAN DEFAULT true');
            $user->update(['is_active' => $data['is_active']]);
        }

        return $user;
    }
}