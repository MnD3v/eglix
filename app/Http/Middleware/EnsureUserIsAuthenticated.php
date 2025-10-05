<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            Log::info('Utilisateur non authentifié, redirection vers login');
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifier si l'utilisateur a des églises associées
        $user = Auth::user();
        if ($user->activeChurches()->count() === 0) {
            Log::warning('Utilisateur sans églises associées: ' . $user->email);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte n\'est pas correctement configuré. Veuillez contacter l\'administrateur.');
        }

        // Définir l'église courante si aucune n'est définie
        // Mais seulement si ce n'est pas une requête AJAX de changement d'église
        if (!$user->getCurrentChurch() && !$request->routeIs('church.switch')) {
            $primaryChurch = $user->primaryChurch()->first();
            if ($primaryChurch) {
                $user->setCurrentChurch($primaryChurch->id);
                Log::info('Église principale définie pour ' . $user->email . ': ' . $primaryChurch->name);
            } else {
                // Prendre la première église active
                $firstChurch = $user->activeChurches()->first();
                if ($firstChurch) {
                    $user->setCurrentChurch($firstChurch->id);
                    Log::info('Première église définie pour ' . $user->email . ': ' . $firstChurch->name);
                }
            }
        }

        $currentChurch = $user->getCurrentChurch();
        Log::info('Utilisateur authentifié: ' . $user->email . ' (Église courante: ' . ($currentChurch ? $currentChurch->name : 'Aucune') . ')');
        
        return $next($request);
    }
}
