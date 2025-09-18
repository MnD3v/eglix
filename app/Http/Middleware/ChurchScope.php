<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ChurchScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user || !$user->church_id) {
            return redirect()->route('login')->with('error', 'Vous devez être associé à une église pour accéder à cette page.');
        }

        // Ajouter l'ID de l'église à la requête pour faciliter l'accès dans les contrôleurs
        $request->merge(['church_id' => $user->church_id]);
        
        return $next($request);
    }
}