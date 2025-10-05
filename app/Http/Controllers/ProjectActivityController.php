<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        // Vérifier que le projet appartient à l'église de l'utilisateur
        if ($project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        $activities = $project->activities()
            ->orderByActivityDate()
            ->paginate(15);

        return view('projects.activities.index', compact('project', 'activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        // Vérifier que le projet appartient à l'église de l'utilisateur
        if ($project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('projects.activities.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        // Vérifier que le projet appartient à l'église de l'utilisateur
        if ($project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount_spent' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'activity_date' => ['required', 'date'],
        ]);

        $validated['project_id'] = $project->id;

        ProjectActivity::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Activité ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, ProjectActivity $activity)
    {
        // Vérifier que l'activité appartient au projet et à l'église
        if ($activity->project_id !== $project->id || $project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('projects.activities.show', compact('project', 'activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, ProjectActivity $activity)
    {
        // Vérifier que l'activité appartient au projet et à l'église
        if ($activity->project_id !== $project->id || $project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('projects.activities.edit', compact('project', 'activity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, ProjectActivity $activity)
    {
        // Vérifier que l'activité appartient au projet et à l'église
        if ($activity->project_id !== $project->id || $project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'amount_spent' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'activity_date' => ['required', 'date'],
        ]);

        $activity->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Activité mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, ProjectActivity $activity)
    {
        // Vérifier que l'activité appartient au projet et à l'église
        if ($activity->project_id !== $project->id || $project->church_id !== get_current_church_id()) {
            abort(403, 'Accès non autorisé');
        }

        $activity->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Activité supprimée avec succès.');
    }
}
