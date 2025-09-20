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
