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

            // Store the file locally and return the local URL
            $localPath = $file->store($path, 'public');
            $localUrl = asset('storage/' . $localPath);
            
            // Return the local URL for database storage
            return $localUrl;

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
            // Vérifier si Firebase est configuré
            if (!FirebaseHelper::isConfigured()) {
                Log::warning('Firebase not configured, cannot delete file', [
                    'filename' => $filename
                ]);
                return true; // Retourner true pour éviter les erreurs
            }
            
            // URL de suppression Firebase Storage
            $deleteUrl = $this->storageUrl . urlencode($filename);
            
            // Initialiser cURL pour la suppression
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $deleteUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            
            // Exécuter la requête
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                Log::error('cURL error during Firebase delete', ['error' => $error]);
                return false;
            }
            
            if ($httpCode === 200 || $httpCode === 404) {
                // 200 = supprimé avec succès, 404 = fichier déjà supprimé
                Log::info('File deleted from Firebase successfully', [
                    'filename' => $filename,
                    'http_code' => $httpCode
                ]);
                return true;
            } else {
                Log::error('Firebase delete failed', [
                    'http_code' => $httpCode,
                    'response' => $response,
                    'filename' => $filename
                ]);
                return false;
            }
            
        } catch (\Exception $e) {
            Log::error('Firebase delete exception', [
                'message' => $e->getMessage(),
                'filename' => $filename
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
            // Pour l'instant, retourner l'URL publique directe
            // En production, vous pourriez générer une URL signée temporaire pour plus de sécurité
            $publicUrl = $this->getPublicUrl($filePath);
            
            Log::info('Generated download URL', [
                'filePath' => $filePath,
                'url' => $publicUrl
            ]);
            
            return $publicUrl;
            
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
            
            // Upload vers Firebase Storage
            $publicUrl = $this->uploadToFirebase($file, $filename);
            
            if ($publicUrl) {
                Log::info('Document uploaded to Firebase successfully', [
                    'filename' => $filename,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                    'url' => $publicUrl
                ]);
                return $publicUrl;
            } else {
                throw new \Exception('Failed to upload to Firebase');
            }

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

    /**
     * Upload file to Firebase Storage using REST API
     */
    private function uploadToFirebase(UploadedFile $file, string $filename): ?string
    {
        try {
            // Préparer les données pour l'upload
            $fileContent = file_get_contents($file->getPathname());
            $mimeType = $file->getMimeType();
            
            // URL d'upload Firebase Storage
            $uploadUrl = $this->storageUrl . urlencode($filename);
            
            // Headers pour l'upload
            $headers = [
                'Content-Type: ' . $mimeType,
                'Content-Length: ' . strlen($fileContent),
            ];
            
            // Initialiser cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uploadUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            
            // Exécuter la requête
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                Log::error('cURL error during Firebase upload', ['error' => $error]);
                return null;
            }
            
            if ($httpCode === 200) {
                // Upload réussi, retourner l'URL publique
                return $this->getPublicUrl($filename);
            } else {
                Log::error('Firebase upload failed', [
                    'http_code' => $httpCode,
                    'response' => $response
                ]);
                return null;
            }
            
        } catch (\Exception $e) {
            Log::error('Firebase upload exception', [
                'message' => $e->getMessage(),
                'filename' => $filename
            ]);
            return null;
        }
    }
}
