<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function __construct()
    {
        // Vérifier l'authentification et les permissions dans chaque méthode
    }

    /**
     * Display church settings/information
     */
    public function index()
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé.');
        }

        $church = Auth::user()->getCurrentChurch();
        
        if (!$church) {
            abort(404, 'Église non trouvée.');
        }

        // Récupérer les utilisateurs de l'église via la relation pivot
        $users = $church->users()->with('role')->orderBy('name')->get();

        return view('user-management.index', compact('church', 'users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Vérifier que l'utilisateur est authentifié et est admin de l'église
        if (!Auth::check() || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les utilisateurs.');
        }

        return view('user-management.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est authentifié et est admin de l'église
        if (!Auth::check() || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent gérer les utilisateurs.');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string|in:members,tithes,offerings,donations,expenses,reports,services,journal,administration',
            'is_active' => 'nullable|in:on,1,true',
        ], [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role_name.required' => 'Le rôle est requis.',
            'role_name.max' => 'Le nom du rôle ne peut pas dépasser 255 caractères.',
            'permissions.array' => 'Les permissions doivent être un tableau.',
            'permissions.*.in' => 'Une ou plusieurs permissions ne sont pas valides.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Créer un nouveau rôle pour cet utilisateur
        $role = Role::create([
            'church_id' => get_current_church_id(),
            'name' => $request->role_name,
            'slug' => Str::slug($request->role_name),
            'description' => 'Rôle personnalisé pour ' . $request->name,
            'permissions' => $request->permissions ?? [],
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
            'is_church_admin' => false,
            'is_active' => $request->has('is_active') || $request->input('is_active') === 'on',
        ]);

        // Associer l'utilisateur à l'église via la table pivot
        $user->churches()->attach(get_current_church_id(), [
            'is_primary' => false,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Vérifier que l'utilisateur appartient à la même église
        if (!$user->hasAccessToChurch(get_current_church_id())) {
            abort(403, 'Accès non autorisé.');
        }

        return view('user-management.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Vérifier que l'utilisateur appartient à la même église
        if (!$user->hasAccessToChurch(get_current_church_id())) {
            abort(403, 'Accès non autorisé.');
        }

        // PERMISSIONS SIMPLIFIÉES : Autoriser l'accès à tous les utilisateurs authentifiés de la même église
        
        $roles = Role::where('church_id', get_current_church_id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user-management.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Vérifier que l'utilisateur appartient à la même église
        if (!$user->hasAccessToChurch(get_current_church_id())) {
            abort(403, 'Accès non autorisé.');
        }

        // PERMISSIONS SIMPLIFIÉES : Autoriser la modification à tous les utilisateurs authentifiés de la même église

        // VALIDATION SIMPLIFIÉE
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:members,tithes,offerings,donations,expenses,reports,services,journal,administration',
            'is_active' => 'nullable|boolean',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Le nom est requis.',
            'email.required' => 'L\'adresse email est requise.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'role_id.required' => 'Le rôle est requis.',
            'role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
            'permissions.array' => 'Les permissions doivent être un tableau.',
            'permissions.*.in' => 'Une ou plusieurs permissions ne sont pas valides.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // MISE À JOUR SIMPLIFIÉE - Autoriser toutes les modifications
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Ajouter le rôle si fourni, sinon conserver l'actuel
        if ($request->has('role_id') && $request->role_id) {
            $role = Role::find($request->role_id);
            if ($role && $role->church_id === get_current_church_id()) {
                $updateData['role_id'] = $request->role_id;
                
                // Mettre à jour les permissions du rôle si fournies
                if ($request->has('permissions')) {
                    $role->update([
                        'permissions' => $request->permissions ?? []
                    ]);
                }
            }
        }

        // Statut actif
        $updateData['is_active'] = $request->has('is_active');

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Vérifier que l'utilisateur appartient à la même église
        if (!$user->hasAccessToChurch(get_current_church_id())) {
            abort(403, 'Accès non autorisé.');
        }

        // PERMISSIONS SIMPLIFIÉES : Autoriser la suppression à tous (sauf admin principal)
        
        // Ne pas permettre de supprimer l'admin de l'église
        if ($user->is_church_admin) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer l\'administrateur de l\'église.');
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        // Vérifier que l'utilisateur appartient à la même église
        if (!$user->hasAccessToChurch(get_current_church_id())) {
            abort(403, 'Accès non autorisé.');
        }

        // PERMISSIONS SIMPLIFIÉES : Autoriser la réinitialisation à tous les utilisateurs de la même église

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()
            ->with('success', 'Mot de passe réinitialisé avec succès.');
    }
}