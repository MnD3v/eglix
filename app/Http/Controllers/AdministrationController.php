<?php

namespace App\Http\Controllers;

use App\Models\AdministrationFunction;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdministrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q'));
        $functionFilter = $request->get('function');
        $statusFilter = $request->get('status');

        $query = AdministrationFunction::with('member');

        // Recherche
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('function_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par fonction
        if ($functionFilter) {
            $query->where('function_name', $functionFilter);
        }

        // Filtre par statut
        if ($statusFilter) {
            if ($statusFilter === 'active') {
                $query->active()->current();
            } elseif ($statusFilter === 'inactive') {
                $query->where('is_active', false);
            } elseif ($statusFilter === 'ended') {
                $query->where('end_date', '<', now());
            }
        }

        $functions = $query->latest('start_date')->paginate(12)->withQueryString();

        // Statistiques
        $stats = [
            'total' => AdministrationFunction::count(),
            'active' => AdministrationFunction::active()->current()->count(),
            'inactive' => AdministrationFunction::where('is_active', false)->count(),
            'ended' => AdministrationFunction::where('end_date', '<', now())->count(),
        ];

        // Liste des fonctions disponibles
        $availableFunctions = AdministrationFunction::distinct()->pluck('function_name')->sort()->values();

        return view('administration.index', compact('functions', 'search', 'functionFilter', 'statusFilter', 'stats', 'availableFunctions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::where('church_id', get_current_church_id())
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        $functions = $this->getAvailableFunctions();
        
        return view('administration.create', compact('members', 'functions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'function_name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean']
        ]);

        // Vérifier qu'il n'y a pas de conflit de fonction active
        $existingActive = AdministrationFunction::where('member_id', $validated['member_id'])
            ->where('function_name', $validated['function_name'])
            ->active()
            ->current()
            ->exists();

        if ($existingActive) {
            return back()->withErrors(['function_name' => 'Ce membre occupe déjà cette fonction de manière active.']);
        }

        AdministrationFunction::create($validated);

        return redirect()->route('administration.index')->with('success', 'Fonction administrative ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrationFunction $administration)
    {
        $administration->load('member');
        return view('administration.show', compact('administration'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdministrationFunction $administration)
    {
        $members = Member::where('church_id', get_current_church_id())
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        $functions = $this->getAvailableFunctions();
        
        return view('administration.edit', compact('administration', 'members', 'functions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdministrationFunction $administration)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'function_name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean']
        ]);

        // Vérifier qu'il n'y a pas de conflit de fonction active (sauf pour l'enregistrement actuel)
        $existingActive = AdministrationFunction::where('member_id', $validated['member_id'])
            ->where('function_name', $validated['function_name'])
            ->where('id', '!=', $administration->id)
            ->active()
            ->current()
            ->exists();

        if ($existingActive) {
            return back()->withErrors(['function_name' => 'Ce membre occupe déjà cette fonction de manière active.']);
        }

        $administration->update($validated);

        return redirect()->route('administration.index')->with('success', 'Fonction administrative mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdministrationFunction $administration)
    {
        $administration->delete();
        return redirect()->route('administration.index')->with('success', 'Fonction administrative supprimée avec succès.');
    }

    /**
     * Get available functions list from database
     */
    private function getAvailableFunctions()
    {
        return \App\Models\AdministrationFunctionType::active()->ordered()->pluck('name', 'name')->toArray();
    }
}