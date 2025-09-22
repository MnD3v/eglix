<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Services\FirebaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;
    
    protected $firebaseService;

    public function __construct(FirebaseStorageService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Document::where('church_id', Auth::user()->church_id)
            ->with('folder');

        // Filtrage par dossier
        if ($request->filled('folder_id')) {
            $query->where('folder_id', $request->folder_id);
        }

        // Filtrage par type
        if ($request->filled('type')) {
            if ($request->type === 'images') {
                $query->images();
            } elseif ($request->type === 'pdfs') {
                $query->pdfs();
            }
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        $folders = DocumentFolder::where('church_id', Auth::user()->church_id)
            ->active()
            ->ordered()
            ->get();

        return view('documents.index', compact('documents', 'folders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $folders = DocumentFolder::where('church_id', Auth::user()->church_id)
            ->active()
            ->ordered()
            ->get();

        $selectedFolder = $request->get('folder_id');

        return view('documents.create', compact('folders', 'selectedFolder'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'required|exists:document_folders,id',
            'file' => 'required|file|mimes:jpeg,png,gif,webp,pdf|max:10240', // 10MB max
            'is_public' => 'boolean'
        ]);

        $file = $request->file('file');
        $folder = DocumentFolder::findOrFail($validated['folder_id']);

        // Vérifier que le dossier appartient à l'église de l'utilisateur
        if ($folder->church_id !== Auth::user()->church_id) {
            return redirect()->back()->withErrors(['error' => 'Dossier non autorisé.']);
        }

        try {
            // Générer un nom de fichier unique
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $folderPath = 'documents/' . Auth::user()->church_id . '/' . $folder->slug;
            $filePath = $folderPath . '/' . $fileName;

            // Upload vers Firebase Storage
            $fileUrl = $this->firebaseService->uploadDocument($file, $folderPath);

            // Déterminer le type de fichier
            $fileType = $this->getFileType($file->getMimeType());

            // Créer l'enregistrement en base
            Document::create([
                'name' => $validated['name'],
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_url' => $fileUrl,
                'file_type' => $fileType,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $validated['description'],
                'folder_id' => $validated['folder_id'],
                'is_public' => $validated['is_public'] ?? false,
                'church_id' => Auth::user()->church_id
            ]);

            return redirect()->route('documents.index')->with('success', 'Document uploadé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'upload: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);
        
        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $this->authorize('update', $document);
        
        $folders = DocumentFolder::where('church_id', Auth::user()->church_id)
            ->active()
            ->ordered()
            ->get();

        return view('documents.edit', compact('document', 'folders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'folder_id' => 'required|exists:document_folders,id',
            'is_public' => 'boolean'
        ]);

        // Vérifier que le nouveau dossier appartient à l'église
        $folder = DocumentFolder::findOrFail($validated['folder_id']);
        if ($folder->church_id !== Auth::user()->church_id) {
            return redirect()->back()->withErrors(['error' => 'Dossier non autorisé.']);
        }

        $document->update($validated);

        return redirect()->route('documents.index')->with('success', 'Document mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        try {
            // Supprimer le fichier de Firebase Storage
            $this->firebaseService->deleteFile($document->file_path);
            
            // Supprimer l'enregistrement de la base
            $document->delete();

            return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la suppression: ' . $e->getMessage()]);
        }
    }

    /**
     * Download the document file
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document);

        try {
            // Générer une URL de téléchargement temporaire depuis Firebase
            $downloadUrl = $this->firebaseService->getDownloadUrl($document->file_path);
            
            return redirect($downloadUrl);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erreur lors du téléchargement: ' . $e->getMessage()]);
        }
    }

    /**
     * Déterminer le type de fichier basé sur le MIME type
     */
    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } else {
            return 'other';
        }
    }
}