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

        // Vérifier si l'utilisateur a un church_id (nécessaire pour le dashboard)
        $user = Auth::user();
        if (!$user->church_id) {
            Log::warning('Utilisateur sans church_id: ' . $user->email);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Votre compte n\'est pas correctement configuré. Veuillez contacter l\'administrateur.');
        }

        Log::info('Utilisateur authentifié: ' . $user->email . ' (Church ID: ' . $user->church_id . ')');
        
        return $next($request);
    }
}
