<?php

namespace App\Http\Controllers;

use App\Models\AdministrationFunctionType;
use Illuminate\Http\Request;

class AdministrationFunctionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $functionTypes = AdministrationFunctionType::ordered()->paginate(12);
        
        return view('administration.function-types.index', compact('functionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administration.function-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:administration_function_types,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0']
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        AdministrationFunctionType::create($validated);

        return redirect()->route('administration-function-types.index')->with('success', 'Type de fonction créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrationFunctionType $administrationFunctionType)
    {
        $administrationFunctionType->load('functions.member');
        
        return view('administration.function-types.show', compact('administrationFunctionType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdministrationFunctionType $administrationFunctionType)
    {
        return view('administration.function-types.edit', compact('administrationFunctionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdministrationFunctionType $administrationFunctionType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:administration_function_types,name,' . $administrationFunctionType->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0']
        ]);

        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $administrationFunctionType->update($validated);

        return redirect()->route('administration-function-types.index')->with('success', 'Type de fonction mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdministrationFunctionType $administrationFunctionType)
    {
        // Vérifier s'il y a des fonctions associées
        if ($administrationFunctionType->functions()->count() > 0) {
            return back()->withErrors(['error' => 'Impossible de supprimer ce type de fonction car il est utilisé par des membres.']);
        }

        $administrationFunctionType->delete();

        return redirect()->route('administration-function-types.index')->with('success', 'Type de fonction supprimé avec succès.');
    }

    /**
     * Toggle active status
     */
    public function toggle(AdministrationFunctionType $administrationFunctionType)
    {
        $administrationFunctionType->update(['is_active' => !$administrationFunctionType->is_active]);

        $status = $administrationFunctionType->is_active ? 'activé' : 'désactivé';
        
        return back()->with('success', "Type de fonction {$status} avec succès.");
    }
}