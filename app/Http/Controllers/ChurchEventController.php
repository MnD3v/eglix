<?php

namespace App\Http\Controllers;

use App\Models\ChurchEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChurchEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = ChurchEvent::where('church_id', Auth::user()->church_id)
            ->orderBy('date','desc')
            ->paginate(12);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'date' => ['required','date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'type' => ['nullable','string','max:100'],
            'location' => ['nullable','string','max:150'],
            'description' => ['nullable','string'],
            'images' => ['nullable','array'],
            'images.*' => ['nullable','url','max:2048'],
        ]);
        $validated['images'] = array_values(array_filter($validated['images'] ?? [], fn($u) => !empty($u)));
        $validated['church_id'] = Auth::user()->church_id;
        $e = ChurchEvent::create($validated);
        return redirect()->route('events.index')->with('success','Événement créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChurchEvent $churchEvent)
    {
        // Vérifier que l'événement appartient à l'église de l'utilisateur
        if ($churchEvent->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé à cet événement.');
        }
        
        return view('events.show', ['event' => $churchEvent]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChurchEvent $churchEvent)
    {
        // Vérifier que l'événement appartient à l'église de l'utilisateur
        if ($churchEvent->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé à cet événement.');
        }
        return view('events.edit', ['event' => $churchEvent]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChurchEvent $churchEvent)
    {
        // Vérifier que l'événement appartient à l'église de l'utilisateur
        if ($churchEvent->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé à cet événement.');
        }
        
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'date' => ['required','date'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'type' => ['nullable','string','max:100'],
            'location' => ['nullable','string','max:150'],
            'description' => ['nullable','string'],
            'images' => ['nullable','array'],
            'images.*' => ['nullable','url','max:2048'],
        ]);
        $validated['images'] = array_values(array_filter($validated['images'] ?? [], fn($u) => !empty($u)));
        $churchEvent->update($validated);
        return redirect()->route('events.index')->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchEvent $churchEvent)
    {
        // Vérifier que l'événement appartient à l'église de l'utilisateur
        if ($churchEvent->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé à cet événement.');
        }
        
        $churchEvent->delete();
        return redirect()->route('events.index')->with('success','Événement supprimé.');
    }
}
