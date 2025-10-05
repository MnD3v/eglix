<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::where('church_id', get_current_church_id())
            ->orderBy('name')
            ->paginate(12);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:150'],
            'description' => ['nullable','string'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'budget' => ['nullable','numeric','min:0'],
            'status' => ['required','in:planned,in_progress,completed,cancelled'],
        ]);
        // church_id will be set by BelongsToChurch trait if not provided
        $validated['church_id'] = get_current_church_id();
        $p = Project::create($validated);
        return redirect()->route('projects.index')->with('success','Projet créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:150'],
            'description' => ['nullable','string'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'budget' => ['nullable','numeric','min:0'],
            'status' => ['required','in:planned,in_progress,completed,cancelled'],
        ]);
        $project->update($validated);
        return redirect()->route('projects.index')->with('success','Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success','Projet supprimé.');
    }
}
