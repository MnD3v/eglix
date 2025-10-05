<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChurchSwitchController extends Controller
{
    /**
     * Switch to a different church
     */
    public function switch(Request $request)
    {
        $request->validate([
            'church_id' => 'required|integer|exists:churches,id'
        ]);

        $user = Auth::user();
        $churchId = $request->church_id;

        // Vérifier que l'utilisateur a accès à cette église
        if (!$user->hasAccessToChurch($churchId)) {
            return redirect()->back()->withErrors(['error' => 'Vous n\'avez pas accès à cette église.']);
        }

        // Changer l'église active
        if ($user->setCurrentChurch($churchId)) {
            return redirect()->back()->with('success', 'Église changée avec succès.');
        }

        return redirect()->back()->withErrors(['error' => 'Erreur lors du changement d\'église.']);
    }

    /**
     * Get the current church info for AJAX requests
     */
    public function getCurrentChurch()
    {
        $user = Auth::user();
        $currentChurch = $user->getCurrentChurch();
        
        if (!$currentChurch) {
            return response()->json(['error' => 'Aucune église active'], 404);
        }

        return response()->json([
            'id' => $currentChurch->id,
            'name' => $currentChurch->name,
            'slug' => $currentChurch->slug
        ]);
    }

    /**
     * Get all accessible churches for the user
     */
    public function getAccessibleChurches()
    {
        $user = Auth::user();
        $churches = $user->activeChurches()->get();
        
        return response()->json([
            'churches' => $churches->map(function ($church) {
                return [
                    'id' => $church->id,
                    'name' => $church->name,
                    'slug' => $church->slug,
                    'is_primary' => $church->pivot->is_primary
                ];
            })
        ]);
    }
}