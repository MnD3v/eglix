<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceAssignment;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with('assignments')->orderBy('date','desc')->paginate(12);
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'theme' => ['nullable','string','max:150'],
            'type' => ['nullable','string','max:100'],
            'preacher' => ['nullable','string','max:150'],
            'choir' => ['nullable','string','max:150'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'location' => ['nullable','string','max:150'],
            'notes' => ['nullable','string'],
            'assignments' => ['nullable','array'],
            'assignments.*.member_id' => ['nullable','exists:members,id'],
            'assignments.*.service_role_id' => ['required_with:assignments.*.member_id','exists:service_roles,id'],
            'assignments.*.notes' => ['nullable','string','max:500'],
        ]);

        // Créer le service
        $service = Service::create($validated);

        // Créer les assignations si elles existent
        if (isset($validated['assignments'])) {
            foreach ($validated['assignments'] as $assignment) {
                if (!empty($assignment['member_id'])) {
                    ServiceAssignment::create([
                        'service_id' => $service->id,
                        'service_role_id' => $assignment['service_role_id'],
                        'member_id' => $assignment['member_id'],
                        'notes' => $assignment['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('services.index')->with('success','Culte planifié avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['assignments.member', 'assignments.serviceRole']);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'date' => ['required','date'],
            'theme' => ['nullable','string','max:150'],
            'type' => ['nullable','string','max:100'],
            'preacher' => ['nullable','string','max:150'],
            'choir' => ['nullable','string','max:150'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'location' => ['nullable','string','max:150'],
            'notes' => ['nullable','string'],
        ]);
        $service->update($validated);
        return redirect()->route('services.index')->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success','Culte supprimé.');
    }
}
