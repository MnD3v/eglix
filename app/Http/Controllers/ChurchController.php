<?php

namespace App\Http\Controllers;

use App\Models\Church;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChurchController extends Controller
{
    /**
     * Display a listing of churches (for super admin only)
     */
    public function index()
    {
        // Seul un super admin peut voir toutes les églises
        // Pour l'instant, on redirige vers la création d'église
        return redirect()->route('churches.create');
    }

    /**
     * Show the form for creating a new church
     */
    public function create()
    {
        return view('churches.create');
    }

    /**
     * Store a newly created church
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Le nom de l\'église est requis.',
            'admin_name.required' => 'Le nom de l\'administrateur est requis.',
            'admin_email.required' => 'L\'email de l\'administrateur est requis.',
            'admin_email.unique' => 'Cette adresse email est déjà utilisée.',
            'admin_password.required' => 'Le mot de passe est requis.',
            'admin_password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'admin_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('admin_password', 'admin_password_confirmation'));
        }

        // Créer l'église
        $church = Church::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
        ]);

        // Créer le rôle admin pour cette église
        $adminRole = Role::create([
            'church_id' => $church->id,
            'name' => 'Administrateur',
            'slug' => 'admin',
            'description' => 'Administrateur de l\'église avec tous les droits',
            'permissions' => array_keys(Role::getAvailablePermissions()),
        ]);

        // Créer l'utilisateur admin
        $adminUser = \App\Models\User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'church_id' => $church->id,
            'role_id' => $adminRole->id,
            'is_church_admin' => true,
        ]);

        // Connecter l'utilisateur admin
        Auth::login($adminUser);

        return redirect('/')->with('success', 'Église créée avec succès ! Vous êtes maintenant connecté en tant qu\'administrateur.');
    }

    /**
     * Display the specified church
     */
    public function show(Church $church)
    {
        // Vérifier que l'utilisateur appartient à cette église
        if (Auth::user()->church_id !== $church->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('churches.show', compact('church'));
    }

    /**
     * Show the form for editing the specified church
     */
    public function edit(Church $church)
    {
        // Seul l'admin de l'église peut modifier
        if (Auth::user()->church_id !== $church->id || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé.');
        }

        return view('churches.edit', compact('church'));
    }

    /**
     * Update the specified church
     */
    public function update(Request $request, Church $church)
    {
        // Seul l'admin de l'église peut modifier
        if (Auth::user()->church_id !== $church->id || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $church->update($request->all());

        return redirect()->route('churches.show', $church)
            ->with('success', 'Informations de l\'église mises à jour avec succès.');
    }

    /**
     * Remove the specified church
     */
    public function destroy(Church $church)
    {
        // Seul l'admin de l'église peut supprimer
        if (Auth::user()->church_id !== $church->id || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé.');
        }

        $church->delete();

        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Église supprimée avec succès.');
    }
}