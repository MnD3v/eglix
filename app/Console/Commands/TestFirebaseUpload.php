<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TestFirebaseUpload extends Command
{
    protected $signature = 'test:firebase-upload';
    protected $description = 'Test Firebase Storage upload functionality';

    public function handle()
    {
        $this->info('🧪 Testing Firebase Storage upload...');
        
        try {
            // Créer un fichier de test
            $testContent = 'Test content for Firebase upload - ' . now();
            $testPath = 'test-firebase-upload.txt';
            
            // Créer le fichier temporaire
            Storage::disk('local')->put($testPath, $testContent);
            $fullPath = Storage::disk('local')->path($testPath);
            
            // Créer un objet UploadedFile simulé
            $uploadedFile = new UploadedFile(
                $fullPath,
                'test-firebase-upload.txt',
                'text/plain',
                null,
                true
            );
            
            $this->info('📁 Created test file: ' . $testPath);
            
            // Tester l'upload
            $firebaseService = new FirebaseStorageService();
            $folderPath = 'test-uploads/' . now()->format('Y-m-d');
            
            $this->info('🚀 Uploading to Firebase...');
            $result = $firebaseService->uploadDocument($uploadedFile, $folderPath);
            
            if ($result) {
                $this->info('✅ Upload successful!');
                $this->info('📎 URL: ' . $result);
                
                // Tester la suppression
                $this->info('🗑️ Testing file deletion...');
                $filename = str_replace($firebaseService->getPublicUrl(''), '', $result);
                $filename = urldecode($filename);
                $filename = str_replace('?alt=media', '', $filename);
                
                $deleteResult = $firebaseService->deleteFile($filename);
                
                if ($deleteResult) {
                    $this->info('✅ File deleted successfully!');
                } else {
                    $this->warn('⚠️ File deletion failed');
                }
                
            } else {
                $this->error('❌ Upload failed!');
            }
            
            // Nettoyer le fichier local
            Storage::disk('local')->delete($testPath);
            $this->info('🧹 Cleaned up local test file');
            
        } catch (\Exception $e) {
            $this->error('❌ Test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
        
        $this->info('🏁 Test completed!');
    }
}