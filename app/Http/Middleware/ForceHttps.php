<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
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
        // Forcer HTTPS en production
        if (config('app.env') === 'production' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        // Vérifier les en-têtes de proxy pour HTTPS
        if (config('app.env') === 'production') {
            $proto = $request->header('X-Forwarded-Proto');
            if ($proto && $proto !== 'https') {
                return redirect()->secure($request->getRequestUri(), 301);
            }
        }

        return $next($request);
    }
}
