<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

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
        try {
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
                \Log::info('Erreurs de validation de connexion: ' . json_encode($validator->errors()->all()));
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
            }

            $credentials = $request->only('email', 'password');
            $remember = $request->has('remember');

            \Log::info('Tentative de connexion pour: ' . $request->email);

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                
                // Log pour diagnostiquer les problèmes de session
                \Log::info('Connexion réussie pour: ' . $request->email);
                \Log::info('Session ID après connexion: ' . $request->session()->getId());
                \Log::info('User ID connecté: ' . Auth::id());
                
                // Vérifier si l'utilisateur a des églises associées
                $user = Auth::user();
                if ($user->activeChurches()->count() === 0) {
                    \Log::warning('Utilisateur sans églises associées: ' . $user->email);
                    Auth::logout();
                    return redirect()->back()
                        ->withErrors(['email' => 'Votre compte n\'est pas correctement configuré. Veuillez contacter l\'administrateur.'])
                        ->withInput($request->except('password'));
                }

                // Définir l'église courante si aucune n'est définie
                if (!$user->getCurrentChurch()) {
                    $primaryChurch = $user->primaryChurch()->first();
                    if ($primaryChurch) {
                        $user->setCurrentChurch($primaryChurch->id);
                        \Log::info('Église principale définie pour ' . $user->email . ': ' . $primaryChurch->name);
                    } else {
                        // Prendre la première église active
                        $firstChurch = $user->activeChurches()->first();
                        if ($firstChurch) {
                            $user->setCurrentChurch($firstChurch->id);
                            \Log::info('Première église définie pour ' . $user->email . ': ' . $firstChurch->name);
                        }
                    }
                } else {
                    \Log::info('Église courante déjà définie pour ' . $user->email . ': ' . $user->getCurrentChurch()->name);
                }
                
                // Vérifier que l'église courante est bien définie dans la session
                $currentChurchId = session('current_church_id');
                \Log::info('Session current_church_id après connexion: ' . ($currentChurchId ?: 'null'));
                
                return redirect()->intended('/')->with('success', 'Connexion réussie !');
            }

            \Log::warning('Échec de connexion pour: ' . $request->email);
            return redirect()->back()
                ->withErrors(['email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'])
                ->withInput($request->except('password'));

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la connexion: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['email' => 'Une erreur est survenue lors de la connexion. Veuillez réessayer.'])
                ->withInput($request->except('password'));
        }
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
                'is_church_admin' => true,
                'is_active' => true,
            ]);

            // Ajouter l'utilisateur à l'église avec le nouveau système multi-églises
            $user->churches()->attach($church->id, [
                'is_primary' => true,
                'is_active' => true,
            ]);

            // Définir l'église courante dans la session
            $user->setCurrentChurch($church->id);

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

            // Forcer l'ajout de chaque colonne (sans church_id qui n'existe plus)
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
        $optionalColumns = ['role_id', 'is_church_admin', 'is_active']; // Supprimé church_id

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

    /**
     * Afficher le formulaire de demande de récupération de mot de passe
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de récupération de mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.exists' => 'Cette adresse email n\'existe pas dans notre système.'
        ]);

        // Récupérer l'utilisateur
        $user = User::where('email', $request->email)->first();
        
        // Générer le token de réinitialisation
        $token = Password::getRepository()->create($user);
        
        // Construire l'URL de réinitialisation
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
        
        // Envoyer l'email personnalisé
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $user));
            
            return back()->with(['status' => 'Nous avons envoyé un lien de réinitialisation à votre adresse email.']);
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email réinitialisation: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Une erreur est survenue lors de l\'envoi de l\'email. Veuillez réessayer.']);
        }
    }

    /**
     * Afficher le formulaire de réinitialisation de mot de passe
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $status = \Illuminate\Support\Facades\Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}