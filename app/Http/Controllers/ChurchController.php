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
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ], [
            'name.required' => 'Le nom de l\'église est requis.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Générer le slug automatiquement
        $slug = \Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Church::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Créer l'église
        $church = Church::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'logo' => null, // Pas de logo
            'is_active' => true, // Toujours actif par défaut
        ]);

        // Associer l'utilisateur actuel à cette église
        $user = Auth::user();
        $user->churches()->attach($church->id, [
            'is_primary' => false, // Pas forcément l'église principale
            'is_active' => true,
        ]);

        // Définir cette église comme courante
        $user->setCurrentChurch($church->id);

        return redirect('/')->with('success', 'Église créée avec succès ! Vous pouvez maintenant basculer vers cette église.');
    }

    /**
     * Display the specified church
     */
    public function show(Church $church)
    {
        // Vérifier que l'utilisateur appartient à cette église
        if (get_current_church_id() !== $church->id) {
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
        if (get_current_church_id() !== $church->id || !Auth::user()->is_church_admin) {
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
        if (get_current_church_id() !== $church->id || !Auth::user()->is_church_admin) {
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

        return redirect()->route('user-management.index')
            ->with('success', 'Informations de l\'église mises à jour avec succès.');
    }

    /**
     * Remove the specified church
     */
    public function destroy(Church $church)
    {
        // Seul l'admin de l'église peut supprimer
        if (get_current_church_id() !== $church->id || !Auth::user()->is_church_admin) {
            abort(403, 'Accès non autorisé.');
        }

        $church->delete();

        Auth::logout();

        return redirect()->route('login')
            ->with('success', 'Église supprimée avec succès.');
    }
}