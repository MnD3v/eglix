<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\JournalImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JournalEntryController extends Controller
{
    private function categories(): array
    {
        return [
            'naissance' => 'Naissance',
            'deces' => 'Décès',
            'bapteme' => 'Baptême',
            'mariage' => 'Mariage',
            'accident' => 'Accident',
            'travaux' => "Travaux d'entretien",
            'construction' => 'Construction',
            'peinture' => 'Peinture',
            'autre' => 'Autre',
        ];
    }
    public function index(Request $request)
    {
        $query = JournalEntry::query();
        if ($request->filled('q')) {
            $q = trim((string) $request->get('q'));
            $query->where(function($w) use ($q){
                $w->where('title','like',"%$q%")
                  ->orWhere('category','like',"%$q%")
                  ->orWhere('description','like',"%$q%");
            });
        }
        if ($request->filled('from')) { $query->whereDate('occurred_at','>=',$request->get('from')); }
        if ($request->filled('to')) { $query->whereDate('occurred_at','<=',$request->get('to')); }
        $entries = $query->orderByDesc('occurred_at')->paginate(12)->appends($request->query());
        $categories = $this->categories();
        return view('journal.index', compact('entries','categories'));
    }

    public function create()
    {
        $categories = $this->categories();
        return view('journal.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Log::info('[Journal] store request received', [
            'has_files' => $request->hasFile('images'),
            'image_urls_count' => is_array($request->image_urls ?? null) ? count($request->image_urls) : 0,
            'keys' => array_keys($request->all()),
        ]);
        try {
            $validated = $request->validate([
                'title' => ['required','string','max:150'],
                'category' => ['required', \Illuminate\Validation\Rule::in(array_keys($this->categories()))],
                'occurred_at' => ['required','date'],
                'description' => ['nullable','string'],
            ]);
            $entry = JournalEntry::create($validated);

            // Accept direct URLs (from Firebase) via image_urls[]
            if (is_array($request->image_urls ?? null) && count($request->image_urls) > 0) {
                foreach ($request->image_urls as $url) {
                    if (!empty($url)) {
                        JournalImage::create([
                            'journal_entry_id' => $entry->id,
                            'path' => $url,
                        ]);
                    }
                }
                Log::info('[Journal] saved firebase image urls', ['count' => count($request->image_urls)]);
            } elseif ($request->hasFile('images')) {
                // Fallback: store locally so that DB is populated even if Firebase upload is blocked
                foreach ($request->file('images') as $file) {
                    if (!$file->isValid()) { continue; }
                    $path = $file->store('journal', 'public');
                    JournalImage::create([
                        'journal_entry_id' => $entry->id,
                        'path' => $path,
                    ]);
                }
                Log::info('[Journal] saved local images');
            } else {
                Log::warning('[Journal] no images provided');
            }
            return redirect()->route('journal.show', $entry)->with('success','Entrée ajoutée.');
        } catch (\Throwable $e) {
            Log::error('[Journal] store failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['journal' => 'Erreur: '.$e->getMessage()])->withInput();
        }
    }

    public function show(JournalEntry $journal)
    {
        $journal->load('images');
        return view('journal.show', ['entry' => $journal]);
    }

    public function edit(JournalEntry $journal)
    {
        $categories = $this->categories();
        return view('journal.edit', ['entry' => $journal, 'categories' => $categories]);
    }

    public function update(Request $request, JournalEntry $journal)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:150'],
            'category' => ['required', \Illuminate\Validation\Rule::in(array_keys($this->categories()))],
            'occurred_at' => ['required','date'],
            'description' => ['nullable','string'],
        ]);
        $journal->update($validated);

        if (is_array($request->image_urls) && count($request->image_urls) > 0) {
            foreach ($request->image_urls as $url) {
                if (!empty($url)) {
                    JournalImage::create([
                        'journal_entry_id' => $journal->id,
                        'path' => $url,
                    ]);
                }
            }
        } elseif ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file->isValid()) { continue; }
                $path = $file->store('journal', 'public');
                JournalImage::create([
                    'journal_entry_id' => $journal->id,
                    'path' => $path,
                ]);
            }
        }
        return redirect()->route('journal.show', $journal)->with('success','Entrée mise à jour.');
    }

    public function destroy(JournalEntry $journal)
    {
        foreach ($journal->images as $img) { $img->delete(); }
        $journal->delete();
        return redirect()->route('journal.index')->with('success','Entrée supprimée.');
    }
}


