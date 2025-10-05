<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Church;
use App\Models\User;

class UserChurchesController extends Controller
{
    /**
     * Afficher la page de gestion des églises de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        $userChurches = $user->churches()->withPivot(['is_primary', 'is_active', 'created_at'])->get();
        
        // Récupérer les églises disponibles (celles auxquelles l'utilisateur n'a pas encore accès)
        $userChurchIds = $userChurches->pluck('id')->toArray();
        $availableChurches = Church::whereNotIn('id', $userChurchIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('user-churches', compact('userChurches', 'availableChurches'));
    }

    /**
     * Ajouter une église à l'utilisateur
     */
    public function addChurch(Request $request)
    {
        $request->validate([
            'church_id' => 'required|integer|exists:churches,id',
            'is_primary' => 'boolean'
        ]);

        $user = Auth::user();
        $churchId = $request->church_id;
        $isPrimary = $request->boolean('is_primary');

        // Vérifier que l'utilisateur n'a pas déjà accès à cette église
        if ($user->hasAccessToChurch($churchId)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà accès à cette église.'
            ]);
        }

        // Si c'est défini comme principale, retirer le statut principal des autres
        if ($isPrimary) {
            $user->churches()->updateExistingPivot(
                $user->churches()->pluck('id')->toArray(),
                ['is_primary' => false]
            );
        }

        // Ajouter l'église
        $user->churches()->attach($churchId, [
            'is_primary' => $isPrimary,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Église ajoutée avec succès.'
        ]);
    }

    /**
     * Définir une église comme principale
     */
    public function setPrimary(Request $request)
    {
        $request->validate([
            'church_id' => 'required|integer|exists:churches,id'
        ]);

        $user = Auth::user();
        $churchId = $request->church_id;

        // Vérifier que l'utilisateur a accès à cette église
        if (!$user->hasAccessToChurch($churchId)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à cette église.'
            ]);
        }

        // Retirer le statut principal de toutes les autres églises
        $user->churches()->updateExistingPivot(
            $user->churches()->pluck('id')->toArray(),
            ['is_primary' => false]
        );

        // Définir cette église comme principale
        $user->churches()->updateExistingPivot($churchId, [
            'is_primary' => true,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Église principale mise à jour avec succès.'
        ]);
    }

    /**
     * Retirer l'accès à une église
     */
    public function removeChurch(Request $request)
    {
        $request->validate([
            'church_id' => 'required|integer|exists:churches,id'
        ]);

        $user = Auth::user();
        $churchId = $request->church_id;

        // Vérifier que l'utilisateur a accès à cette église
        if (!$user->hasAccessToChurch($churchId)) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à cette église.'
            ]);
        }

        // Vérifier que ce n'est pas la seule église de l'utilisateur
        if ($user->churches()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas retirer votre dernière église.'
            ]);
        }

        // Retirer l'accès
        $user->churches()->detach($churchId);

        // Si c'était l'église principale, définir une autre comme principale
        $remainingChurches = $user->churches()->get();
        if ($remainingChurches->count() > 0) {
            $user->churches()->updateExistingPivot($remainingChurches->first()->id, [
                'is_primary' => true,
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Accès à l\'église retiré avec succès.'
        ]);
    }

    /**
     * Obtenir les églises de l'utilisateur pour AJAX
     */
    public function getUserChurches()
    {
        $user = Auth::user();
        $churches = $user->activeChurches()->get();
        
        return response()->json([
            'churches' => $churches->map(function ($church) {
                return [
                    'id' => $church->id,
                    'name' => $church->name,
                    'slug' => $church->slug,
                    'is_primary' => $church->pivot->is_primary,
                    'is_current' => $church->id == get_current_church_id()
                ];
            })
        ]);
    }
}
