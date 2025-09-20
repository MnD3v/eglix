<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class CheckSubscriptionAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Si l'utilisateur n'a pas d'église, laisser passer (gestion des erreurs)
        if (!$user->church_id) {
            return $next($request);
        }

        // Vérifier si l'église a un abonnement valide
        $hasAccess = Subscription::checkChurchAccess($user->church_id);

        if (!$hasAccess) {
            // Rediriger vers la page de paiement/expiration
            return redirect()->route('subscription.expired');
        }

        return $next($request);
    }
}
