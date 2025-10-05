<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckChurchSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return $next($request);
        }

        // Exclure certaines routes du contrôle d'abonnement
        $excludedRoutes = [
            'subscription.request',
            'subscription.request.send',
            'subscription.renewal',
            'subscription.process-renewal',
            'logout',
            'admin.index',
            'admin.*',
            'church.switch',  // Exclure le changement d'église
            'church.current',
            'church.accessible'
        ];

        foreach ($excludedRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur a une église courante
        $currentChurch = $user->getCurrentChurch();
        
        if (!$currentChurch) {
            return $next($request);
        }

        // Charger l'église avec les informations d'abonnement
        $church = $currentChurch;

        // Vérifier si l'église a un abonnement
        if (!$church->subscription_end_date) {
            // Pas d'abonnement - rediriger vers la page de demande d'abonnement
            return redirect()->route('subscription.request');
        }

        // Vérifier si l'abonnement est expiré
        if ($church->isSubscriptionExpired()) {
            // Rediriger vers la page de renouvellement
            return redirect()->route('subscription.renewal');
        }

        return $next($request);
    }
}
