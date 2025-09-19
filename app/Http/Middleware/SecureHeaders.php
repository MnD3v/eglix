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
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), speaker=()',
        'Cross-Origin-Embedder-Policy' => 'require-corp',
        'Cross-Origin-Opener-Policy' => 'same-origin',
        'Cross-Origin-Resource-Policy' => 'same-origin',
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

        // En production, ajouter des en-têtes de sécurité renforcés
        if (config('app.env') === 'production') {
            // Content Security Policy renforcé
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://www.gstatic.com https://cdnjs.cloudflare.com; " .
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
                "font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
                "img-src 'self' data: https: blob:; " .
                "connect-src 'self' https: wss:; " .
                "form-action 'self'; " .
                "frame-src 'self'; " .
                "object-src 'none'; " .
                "base-uri 'self'; " .
                "upgrade-insecure-requests;"
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
