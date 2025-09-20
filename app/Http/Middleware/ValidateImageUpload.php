<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateImageUpload
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
        // Vérifier si des fichiers images sont présents
        if ($request->hasFile('images') || $request->hasFile('profile_photo')) {
            $files = $request->hasFile('images') ? $request->file('images') : [$request->file('profile_photo')];
            
            foreach ($files as $index => $file) {
                if ($file && $file->isValid()) {
                    // Vérifier le type MIME
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!in_array($file->getMimeType(), $allowedMimes)) {
                        Log::warning('Invalid image MIME type', [
                            'file' => $file->getClientOriginalName(),
                            'mime_type' => $file->getMimeType(),
                            'allowed_types' => $allowedMimes
                        ]);
                        
                        return back()->withErrors([
                            'upload' => "Le fichier '{$file->getClientOriginalName()}' n'est pas une image valide. Types acceptés: JPEG, PNG, GIF, WebP."
                        ])->withInput();
                    }
                    
                    // Vérifier la taille (10MB max)
                    $maxSize = 10 * 1024 * 1024; // 10MB
                    if ($file->getSize() > $maxSize) {
                        Log::warning('Image file too large', [
                            'file' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'max_size' => $maxSize
                        ]);
                        
                        return back()->withErrors([
                            'upload' => "Le fichier '{$file->getClientOriginalName()}' est trop volumineux. Taille maximale: 10MB."
                        ])->withInput();
                    }
                    
                    // Vérifier l'extension
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (!in_array($extension, $allowedExtensions)) {
                        Log::warning('Invalid image extension', [
                            'file' => $file->getClientOriginalName(),
                            'extension' => $extension,
                            'allowed_extensions' => $allowedExtensions
                        ]);
                        
                        return back()->withErrors([
                            'upload' => "L'extension du fichier '{$file->getClientOriginalName()}' n'est pas supportée. Extensions acceptées: " . implode(', ', $allowedExtensions)
                        ])->withInput();
                    }
                } elseif ($file && !$file->isValid()) {
                    Log::error('Invalid uploaded file', [
                        'file' => $file->getClientOriginalName(),
                        'error' => $file->getErrorMessage(),
                        'error_code' => $file->getError()
                    ]);
                    
                    return back()->withErrors([
                        'upload' => "Erreur lors de l'upload du fichier '{$file->getClientOriginalName()}': " . $file->getErrorMessage()
                    ])->withInput();
                }
            }
        }
        
        return $next($request);
    }
}
