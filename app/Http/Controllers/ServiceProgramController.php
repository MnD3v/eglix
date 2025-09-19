<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRole;
use App\Models\ServiceAssignment;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProgramController extends Controller
{
    /**
     * Afficher la programmation d'un culte
     */
    public function show(Service $service)
    {
        $service->load(['assignments.member', 'assignments.serviceRole']);
        $roles = ServiceRole::where('is_active', true)->orderBy('name')->get();
        $members = Member::where('church_id', Auth::user()->church_id)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        return view('services.program', compact('service', 'roles', 'members'));
    }

    /**
     * Assigner un membre à un rôle pour un culte
     */
    public function assign(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_role_id' => ['required', 'exists:service_roles,id'],
            'member_id' => ['required', 'exists:members,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Vérifier qu'il n'y a pas déjà une affectation pour ce rôle et ce culte
        $existing = ServiceAssignment::where('service_id', $service->id)
            ->where('service_role_id', $validated['service_role_id'])
            ->where('member_id', $validated['member_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Ce membre est déjà assigné à ce rôle pour ce culte.');
        }

        $validated['service_id'] = $service->id;
        ServiceAssignment::create($validated);

        return back()->with('success', 'Membre assigné avec succès.');
    }

    /**
     * Retirer l'assignation d'un membre
     */
    public function unassign(ServiceAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Assignation supprimée avec succès.');
    }
}
