<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MemberRemarkController extends Controller
{
    /**
     * Ajouter une remarque à un membre
     */
    public function store(Request $request, Member $member): JsonResponse
    {
        $request->validate([
            'remark' => 'required|string|max:500',
        ]);

        $member->addRemark($request->remark);

        return response()->json([
            'success' => true,
            'message' => 'Remarque ajoutée avec succès',
            'remarks' => $member->getFormattedRemarks(),
        ]);
    }

    /**
     * Supprimer une remarque d'un membre
     */
    public function destroy(Member $member, int $index): JsonResponse
    {
        $member->removeRemark($index);

        return response()->json([
            'success' => true,
            'message' => 'Remarque supprimée avec succès',
            'remarks' => $member->getFormattedRemarks(),
        ]);
    }

    /**
     * Obtenir toutes les remarques d'un membre
     */
    public function index(Member $member): JsonResponse
    {
        return response()->json([
            'success' => true,
            'remarks' => $member->getFormattedRemarks(),
        ]);
    }
}