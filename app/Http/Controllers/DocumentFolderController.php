<?php

namespace App\Http\Controllers;

use App\Models\DocumentFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentFolderController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $folders = DocumentFolder::where('church_id', Auth::user()->church_id)
            ->ordered()
            ->withCount('documents')
            ->get();

        return view('documents.folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('documents.folders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $validated['church_id'] = Auth::user()->church_id;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DocumentFolder::create($validated);

        return redirect()->route('document-folders.index')->with('success', 'Dossier créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentFolder $documentFolder)
    {
        $this->authorize('view', $documentFolder);
        
        $documents = $documentFolder->documents()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('documents.folders.show', compact('documentFolder', 'documents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentFolder $documentFolder)
    {
        $this->authorize('update', $documentFolder);
        
        return view('documents.folders.edit', compact('documentFolder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentFolder $documentFolder)
    {
        $this->authorize('update', $documentFolder);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $documentFolder->update($validated);

        return redirect()->route('document-folders.index')->with('success', 'Dossier mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentFolder $documentFolder)
    {
        $this->authorize('delete', $documentFolder);

        // Vérifier s'il y a des documents dans le dossier
        if ($documentFolder->documents()->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'Impossible de supprimer un dossier contenant des documents.']);
        }

        $documentFolder->delete();

        return redirect()->route('document-folders.index')->with('success', 'Dossier supprimé avec succès.');
    }
}