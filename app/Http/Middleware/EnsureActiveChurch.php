<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveChurch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $currentChurch = $user->getCurrentChurch();

        if (!$currentChurch) {
            // Si l'utilisateur n'a pas d'église active, rediriger vers une page de sélection
            return redirect()->route('church.selection')->withErrors([
                'error' => 'Veuillez sélectionner une église pour continuer.'
            ]);
        }

        // Ajouter l'église courante à la requête pour faciliter l'accès
        $request->merge(['current_church' => $currentChurch]);
        $request->merge(['current_church_id' => $currentChurch->id]);

        return $next($request);
    }
}
