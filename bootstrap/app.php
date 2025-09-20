<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware global pour toutes les requêtes
        $middleware->append(\App\Http\Middleware\SecureHeaders::class);
        
        // Middleware pour les routes web
        $middleware->web(append: [
            \App\Http\Middleware\EnsureMigrationsAreRun::class,
            \App\Http\Middleware\EnhancedCsrfProtection::class,
            \App\Http\Middleware\CheckChurchSubscription::class,
        ]);
        
        // Enregistrement du middleware de validation d'upload
        $middleware->alias([
            'validate.image.upload' => \App\Http\Middleware\ValidateImageUpload::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
