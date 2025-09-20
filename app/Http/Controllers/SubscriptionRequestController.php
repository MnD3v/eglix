<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SubscriptionRequestController extends Controller
{
    /**
     * Afficher la page de demande d'abonnement
     */
    public function index()
    {
        $user = Auth::user();
        $church = $user->church;
        
        if (!$church) {
            return redirect()->route('dashboard')->with('error', 'Aucune église associée à votre compte.');
        }

        return view('subscription.request', compact('church'));
    }

    /**
     * Envoyer une demande d'abonnement à l'administrateur
     */
    public function sendRequest(Request $request)
    {
        $user = Auth::user();
        $church = $user->church;
        
        if (!$church) {
            return redirect()->route('dashboard')->with('error', 'Aucune église associée à votre compte.');
        }

        $validated = $request->validate([
            'subscription_plan' => 'required|in:basic,premium,enterprise',
            'message' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:100',
        ]);

        // Déterminer le montant selon le plan
        $amounts = [
            'basic' => 50000,
            'premium' => 100000,
            'enterprise' => 200000
        ];

        $planNames = [
            'basic' => 'Basique',
            'premium' => 'Premium',
            'enterprise' => 'Entreprise'
        ];

        // Préparer les données de la demande
        $requestData = [
            'church_name' => $church->name,
            'church_address' => $church->address,
            'church_phone' => $church->phone,
            'church_email' => $church->email,
            'plan_name' => $planNames[$validated['subscription_plan']],
            'plan_amount' => $amounts[$validated['subscription_plan']],
            'message' => $validated['message'],
            'contact_phone' => $validated['contact_phone'] ?: $church->phone,
            'contact_email' => $validated['contact_email'] ?: $church->email,
            'requested_by' => $user->name,
            'requested_at' => now(),
        ];

        // Ici, vous pourriez envoyer un email à l'administrateur
        // Mail::to('admin@example.com')->send(new SubscriptionRequestMail($requestData));

        // Pour l'instant, on stocke la demande dans la session
        session(['subscription_request' => $requestData]);

        return redirect()->route('subscription.request')->with('success', 
            'Votre demande d\'abonnement ' . $planNames[$validated['subscription_plan']] . 
            ' a été envoyée à l\'administrateur. Vous recevrez une réponse sous 24h.');
    }
}