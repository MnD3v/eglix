<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnhancedCsrfProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier les requêtes POST, PUT, PATCH, DELETE
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            
            // Vérifier le token CSRF
            if (!$request->hasValidSignature() && !$this->hasValidCsrfToken($request)) {
                Log::warning('Tentative de soumission de formulaire sans token CSRF valide', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);
                
                return response()->json([
                    'error' => 'Token CSRF invalide ou manquant',
                    'message' => 'Veuillez recharger la page et réessayer.'
                ], 419);
            }
            
            // Vérifier l'origine de la requête
            $origin = $request->header('Origin');
            $referer = $request->header('Referer');
            $host = $request->getHost();
            
            if ($origin && !str_contains($origin, $host)) {
                Log::warning('Tentative de soumission de formulaire depuis une origine non autorisée', [
                    'ip' => $request->ip(),
                    'origin' => $origin,
                    'host' => $host,
                    'url' => $request->fullUrl(),
                ]);
                
                return response()->json([
                    'error' => 'Origine non autorisée',
                    'message' => 'Cette requête provient d\'une origine non autorisée.'
                ], 403);
            }
            
            // Vérifier le Referer pour les requêtes sensibles
            if ($referer && !str_contains($referer, $host)) {
                Log::warning('Tentative de soumission de formulaire avec un Referer suspect', [
                    'ip' => $request->ip(),
                    'referer' => $referer,
                    'host' => $host,
                    'url' => $request->fullUrl(),
                ]);
                
                return response()->json([
                    'error' => 'Referer suspect',
                    'message' => 'Cette requête provient d\'une page non autorisée.'
                ], 403);
            }
        }
        
        return $next($request);
    }
    
    /**
     * Vérifier si le token CSRF est valide
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function hasValidCsrfToken(Request $request): bool
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        
        if (!$token) {
            return false;
        }
        
        return hash_equals(session()->token(), $token);
    }
}
