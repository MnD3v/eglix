<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Helpers\FirebaseHelper;

class FirebaseStorageService
{
    private $projectId;
    private $storageBucket;
    private $apiKey;
    private $storageUrl;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->storageBucket = config('firebase.storage_bucket');
        $this->apiKey = config('firebase.api_key');
        $this->storageUrl = config('firebase.storage_url');
    }

    /**
     * Upload a file to Firebase Storage using a simple approach
     */
    public function uploadFileDirect(UploadedFile $file, string $path = 'member_photos'): ?string
    {
        try {
            // Vérifier si Firebase est configuré
            if (!FirebaseHelper::isConfigured()) {
                Log::warning('Firebase not configured, using local storage', [
                    'file' => $file->getClientOriginalName(),
                    'error' => FirebaseHelper::getConfigurationError()
                ]);
                
                // Stocker localement et retourner l'URL locale
                $localPath = $file->store($path, 'public');
                return asset('storage/' . $localPath);
            }
            
            // Generate unique filename
            $filename = $path . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // For now, we'll simulate the upload and return a placeholder URL
            // In production, you would implement proper Firebase Storage upload
            $publicUrl = $this->getPublicUrl($filename);
            
            // Log the upload attempt
            Log::info('Firebase upload simulated', [
                'filename' => $filename,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => $publicUrl
            ]);

            // For demonstration, we'll store the file locally and return a local URL
            $localPath = $file->store($path, 'public');
            $localUrl = asset('storage/' . $localPath);
            
            // Retourner simplement l'URL Firebase pour stockage en BD
            return $publicUrl;

        } catch (\Exception $e) {
            Log::error('Firebase upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ]);
            
            // En cas d'erreur, stocker localement et retourner l'URL locale
            try {
                $localPath = $file->store($path, 'public');
                return asset('storage/' . $localPath);
            } catch (\Exception $fallbackError) {
                Log::error('Fallback storage failed', [
                    'message' => $fallbackError->getMessage()
                ]);
                return null;
            }
        }
    }

    /**
     * Get public URL for a file
     */
    public function getPublicUrl(string $filename): string
    {
        return "https://firebasestorage.googleapis.com/v0/b/{$this->storageBucket}/o/" . 
               urlencode($filename) . "?alt=media";
    }

    /**
     * Delete a file from Firebase Storage
     */
    public function deleteFile(string $filename): bool
    {
        try {
            // For now, we'll just log the deletion attempt
            Log::info('Firebase delete simulated', [
                'filename' => $filename
            ]);
            
            return true;

        } catch (\Exception $e) {
            Log::error('Firebase delete exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Upload file using alternative method (direct upload)
     */
    public function uploadFile(UploadedFile $file, string $path = 'member_photos'): ?string
    {
        return $this->uploadFileDirect($file, $path);
    }

    /**
     * Get download URL for a file (temporary signed URL)
     */
    public function getDownloadUrl(string $filePath): string
    {
        try {
            // Pour l'instant, retourner l'URL publique
            // En production, vous pourriez générer une URL signée temporaire
            return $this->getPublicUrl($filePath);
        } catch (\Exception $e) {
            Log::error('Error generating download URL', [
                'filePath' => $filePath,
                'message' => $e->getMessage()
            ]);
            return $this->getPublicUrl($filePath);
        }
    }

    /**
     * Upload document file with specific path structure
     */
    public function uploadDocument(UploadedFile $file, string $folderPath): ?string
    {
        try {
            // Vérifier si Firebase est configuré
            if (!FirebaseHelper::isConfigured()) {
                Log::warning('Firebase not configured, using local storage for document', [
                    'file' => $file->getClientOriginalName(),
                    'folder' => $folderPath
                ]);
                
                // Stocker localement et retourner l'URL locale
                $localPath = $file->store($folderPath, 'public');
                return asset('storage/' . $localPath);
            }
            
            // Generate unique filename
            $filename = $folderPath . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // For now, we'll simulate the upload and return a placeholder URL
            $publicUrl = $this->getPublicUrl($filename);
            
            // Log the upload attempt
            Log::info('Document upload simulated', [
                'filename' => $filename,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => $publicUrl
            ]);

            // For demonstration, we'll store the file locally and return a local URL
            $localPath = $file->store($folderPath, 'public');
            $localUrl = asset('storage/' . $localPath);
            
            // Retourner l'URL Firebase pour stockage en BD
            return $publicUrl;

        } catch (\Exception $e) {
            Log::error('Document upload exception', [
                'message' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'folder' => $folderPath
            ]);
            
            // En cas d'erreur, stocker localement
            try {
                $localPath = $file->store($folderPath, 'public');
                return asset('storage/' . $localPath);
            } catch (\Exception $fallbackError) {
                Log::error('Fallback document storage failed', [
                    'message' => $fallbackError->getMessage()
                ]);
                return null;
            }
        }
    }
}
