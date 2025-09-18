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
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
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

        // En production, forcer HTTPS pour les cookies et les formulaires
        if (config('app.env') === 'production') {
            $response->headers->set('Content-Security-Policy', 
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://www.gstatic.com; " .
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; " .
                "font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; " .
                "img-src 'self' data: https:; " .
                "connect-src 'self' https:; " .
                "form-action 'self'; " .
                "frame-src 'self';"
            );
        }

        return $response;
    }
}
