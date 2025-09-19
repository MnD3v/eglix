<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Les en-têtes de sécurité à ajouter à toutes les réponses.
     *
     * @var array
     */
    protected $headers = [
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection' => '1; mode=block',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        'Referrer-Policy' => 'no-referrer-when-downgrade',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), speaker=()',
        'Cross-Origin-Embedder-Policy' => 'unsafe-none',
        'Cross-Origin-Opener-Policy' => 'unsafe-none',
        'Cross-Origin-Resource-Policy' => 'cross-origin',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Ajouter les en-têtes de sécurité
        foreach ($this->headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        // En production, ajouter des en-têtes de sécurité assouplis
        if (config('app.env') === 'production') {
            // Content Security Policy assoupli pour permettre les liens externes
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self' *; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' *; " .
                "style-src 'self' 'unsafe-inline' *; " .
                "font-src 'self' *; " .
                "img-src 'self' data: *; " .
                "connect-src 'self' *; " .
                "form-action 'self' *; " .
                "frame-src 'self' *; " .
                "object-src 'none'; " .
                "base-uri 'self' *;"
            );

            // Headers supplémentaires pour la sécurité
            $response->headers->set('X-Download-Options', 'noopen');
            $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
            
            // Cache Control pour les pages sensibles
            if ($request->is('admin/*') || $request->is('members/*') || $request->is('tithes/*') || $request->is('donations/*')) {
                $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->headers->set('Pragma', 'no-cache');
                $response->headers->set('Expires', '0');
            }
        }

        return $response;
    }
}
