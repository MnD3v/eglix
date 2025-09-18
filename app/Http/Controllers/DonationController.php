<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::where('church_id', Auth::user()->church_id)
            ->with(['member','project'])->latest('received_at')->paginate(12);
        return view('donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::where('church_id', Auth::user()->church_id)
            ->orderBy('last_name')->orderBy('first_name')->get();
        $projects = Project::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();
        return view('donations.create', compact('members','projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['nullable','exists:members,id'],
            'project_id' => ['nullable','exists:projects,id'],
            'received_at' => ['required','date'],
            'donation_type' => ['required','in:money,physical'],
            'amount' => ['required_if:donation_type,money','nullable','numeric','min:0'],
            'physical_item' => ['required_if:donation_type,physical','nullable','string','max:150'],
            'physical_description' => ['nullable','string'],
            'donor_name' => ['nullable','string','max:150'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        // Ensure DB NOT NULL for amount: set to 0 when physical donation
        if (($validated['donation_type'] ?? 'money') === 'physical') {
            $validated['amount'] = 0;
        }
        
        // Ajouter l'ID de l'église et l'auteur
        $validated['church_id'] = Auth::user()->church_id;
        $validated['created_by'] = Auth::id();
        
        $d = Donation::create($validated);
        return redirect()->route('donations.show', $d)->with('success','Don enregistré.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        // Vérifier que le don appartient à l'église de l'utilisateur
        if ($donation->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $donation->load(['member','project']);
        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        // Vérifier que le don appartient à l'église de l'utilisateur
        if ($donation->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $members = Member::where('church_id', Auth::user()->church_id)
            ->orderBy('last_name')->orderBy('first_name')->get();
        $projects = Project::where('church_id', Auth::user()->church_id)
            ->orderBy('name')->get();
        return view('donations.edit', compact('donation','members','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        // Vérifier que le don appartient à l'église de l'utilisateur
        if ($donation->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'member_id' => ['nullable','exists:members,id'],
            'project_id' => ['nullable','exists:projects,id'],
            'received_at' => ['required','date'],
            'donation_type' => ['required','in:money,physical'],
            'amount' => ['required_if:donation_type,money','nullable','numeric','min:0'],
            'physical_item' => ['required_if:donation_type,physical','nullable','string','max:150'],
            'physical_description' => ['nullable','string'],
            'donor_name' => ['nullable','string','max:150'],
            'payment_method' => ['nullable','string','max:50'],
            'reference' => ['nullable','string','max:100'],
            'notes' => ['nullable','string'],
        ]);
        if (($validated['donation_type'] ?? $donation->donation_type) === 'physical') {
            $validated['amount'] = 0;
        }
        $validated['updated_by'] = Auth::id();
        $donation->update($validated);
        return redirect()->route('donations.show', $donation)->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        // Vérifier que le don appartient à l'église de l'utilisateur
        if ($donation->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $donation->delete();
        return redirect()->route('donations.index')->with('success','Don supprimé.');
    }
}
