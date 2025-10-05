<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionRenewalController extends Controller
{
    /**
     * Afficher la page de renouvellement d'abonnement
     */
    public function renewal()
    {
        $user = Auth::user();
        $church = $user->getCurrentChurch();
        
        if (!$church) {
            return redirect()->route('church.selection')->with('error', 'Aucune église associée à votre compte.');
        }

        return view('subscription.renewal', compact('church'));
    }

    /**
     * Traiter le renouvellement d'abonnement
     */
    public function processRenewal(Request $request)
    {
        $user = Auth::user();
        $church = $user->getCurrentChurch();
        
        if (!$church) {
            return redirect()->route('church.selection')->with('error', 'Aucune église associée à votre compte.');
        }

        $validated = $request->validate([
            'subscription_plan' => 'required|in:basic,premium,enterprise',
            'subscription_amount' => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:100',
            'subscription_notes' => 'nullable|string',
        ]);

        // Calculer les dates d'abonnement
        $startDate = now();
        $endDate = $startDate->copy()->addYear();

        // Mettre à jour l'abonnement de l'église
        $church->update([
            'subscription_start_date' => $startDate,
            'subscription_end_date' => $endDate,
            'subscription_status' => 'active',
            'subscription_plan' => $validated['subscription_plan'],
            'subscription_amount' => $validated['subscription_amount'],
            'subscription_notes' => $validated['subscription_notes'],
            'payment_reference' => $validated['payment_reference'],
            'payment_date' => now(),
        ]);

        return redirect()->route('church.selection')->with('success', 'Abonnement renouvelé avec succès !');
    }
}
