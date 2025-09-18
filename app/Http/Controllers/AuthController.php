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
            return redirect()->intended('/');
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
            $adminRole = \App\Models\Role::create([
                'church_id' => $church->id,
                'name' => 'Administrateur',
                'slug' => '', // Laisser le système générer un slug unique
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
            $user = User::create([
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
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}