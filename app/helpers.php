<?php

if (!function_exists('get_image_url')) {
    /**
     * Get the correct image URL, handling both Firebase URLs and local storage paths
     *
     * @param string|null $firebaseUrl
     * @param string|null $localPath
     * @return string|null
     */
    function get_image_url(?string $firebaseUrl = null, ?string $localPath = null): ?string
    {
        // Si on a une URL Firebase, l'utiliser directement
        if ($firebaseUrl && str_starts_with($firebaseUrl, 'http')) {
            return $firebaseUrl;
        }
        
        // Sinon, utiliser le chemin local avec asset()
        if ($localPath) {
            return asset('storage/' . $localPath);
        }
        
        return null;
    }
}

if (!function_exists('get_current_church_id')) {
    /**
     * Get the current church ID for the authenticated user
     *
     * @return int|null
     */
    function get_current_church_id(): ?int
    {
        if (!auth()->check()) {
            return null;
        }
        
        $user = auth()->user();
        $currentChurch = $user->getCurrentChurch();
        
        return $currentChurch ? $currentChurch->id : null;
    }
}

if (!function_exists('get_current_church')) {
    /**
     * Get the current church object for the authenticated user
     *
     * @return \App\Models\Church|null
     */
    function get_current_church()
    {
        if (!auth()->check()) {
            return null;
        }
        
        return auth()->user()->getCurrentChurch();
    }
}