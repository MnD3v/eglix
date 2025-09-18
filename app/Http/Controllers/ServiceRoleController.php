<?php

namespace App\Http\Controllers;

use App\Models\ServiceRole;
use Illuminate\Http\Request;

class ServiceRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = ServiceRole::latest()->paginate(12);
        return view('service-roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service-roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        ServiceRole::create($validated);

        return redirect()->route('service-roles.index')->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRole $serviceRole)
    {
        return view('service-roles.show', compact('serviceRole'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceRole $serviceRole)
    {
        return view('service-roles.edit', compact('serviceRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceRole $serviceRole)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_active' => ['boolean'],
        ]);

        $serviceRole->update($validated);

        return redirect()->route('service-roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRole $serviceRole)
    {
        $serviceRole->update(['is_active' => false]);
        return redirect()->route('service-roles.index')->with('success', 'Rôle désactivé avec succès.');
    }

    /**
     * Restaurer les rôles par défaut
     */
    public function resetDefaults()
    {
        // Désactiver tous les rôles existants
        ServiceRole::query()->update(['is_active' => false]);

        // Créer les rôles par défaut
        $defaultRoles = [
            [
                'name' => 'MC (Maître de Cérémonie)',
                'description' => 'Dirige le culte et coordonne les différentes parties',
                'color' => '#FF2600',
                'is_active' => true,
            ],
            [
                'name' => 'Prédicateur',
                'description' => 'Prêche le message principal du culte',
                'color' => '#22C55E',
                'is_active' => true,
            ],
            [
                'name' => 'Lecteur',
                'description' => 'Lit les passages bibliques pendant le culte',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Prieur d\'ouverture',
                'description' => 'Dirige la prière d\'ouverture du culte',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Prieur de clôture',
                'description' => 'Dirige la prière de clôture du culte',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Chorale',
                'description' => 'Dirige les chants et la louange',
                'color' => '#EC4899',
                'is_active' => true,
            ],
            [
                'name' => 'Accompagnateur musical',
                'description' => 'Joue la musique d\'accompagnement',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Accueil',
                'description' => 'Accueille les fidèles à l\'entrée',
                'color' => '#6B7280',
                'is_active' => true,
            ],
            [
                'name' => 'Collecte',
                'description' => 'Collecte les offrandes pendant le culte',
                'color' => '#F97316',
                'is_active' => true,
            ],
            [
                'name' => 'Technique',
                'description' => 'Gère la sonorisation et l\'éclairage',
                'color' => '#6366F1',
                'is_active' => true,
            ],
        ];

        foreach ($defaultRoles as $role) {
            ServiceRole::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        return redirect()->route('service-roles.index')->with('success', 'Rôles par défaut restaurés avec succès.');
    }
}
