<?php

namespace App\Helpers;

class FirebaseHelper
{
    /**
     * Vérifier si Firebase est correctement configuré
     */
    public static function isConfigured(): bool
    {
        $apiKey = config('firebase.api_key');
        $projectId = config('firebase.project_id');
        $storageBucket = config('firebase.storage_bucket');
        
        return $apiKey && 
               $apiKey !== 'xxxx' && 
               $projectId && 
               $projectId !== 'xxxx' &&
               $storageBucket && 
               $storageBucket !== 'xxxx.appspot.com';
    }
    
    /**
     * Obtenir la configuration Firebase pour le JavaScript
     */
    public static function getJsConfig(): array
    {
        return [
            'apiKey' => config('firebase.api_key'),
            'authDomain' => config('firebase.auth_domain'),
            'projectId' => config('firebase.project_id'),
            'storageBucket' => config('firebase.storage_bucket'),
            'messagingSenderId' => config('firebase.messaging_sender_id'),
            'appId' => config('firebase.app_id'),
        ];
    }
    
    /**
     * Obtenir un message d'erreur de configuration Firebase
     */
    public static function getConfigurationError(): ?string
    {
        if (self::isConfigured()) {
            return null;
        }
        
        $missing = [];
        
        if (!config('firebase.api_key') || config('firebase.api_key') === 'xxxx') {
            $missing[] = 'API Key';
        }
        
        if (!config('firebase.project_id') || config('firebase.project_id') === 'xxxx') {
            $missing[] = 'Project ID';
        }
        
        if (!config('firebase.storage_bucket') || config('firebase.storage_bucket') === 'xxxx.appspot.com') {
            $missing[] = 'Storage Bucket';
        }
        
        return 'Configuration Firebase incomplète. Paramètres manquants: ' . implode(', ', $missing);
    }
    
    /**
     * Obtenir les règles de sécurité Firebase Storage recommandées
     */
    public static function getRecommendedStorageRules(): string
    {
        return json_encode([
            'rules' => [
                'rules_version' => '2',
                'service' => 'firebase.storage',
                'match' => '/b/{bucket}/o',
                'allow' => [
                    'read' => 'if true',
                    'write' => 'if request.auth != null' // Sécurisé: nécessite une authentification
                ]
            ]
        ], JSON_PRETTY_PRINT);
    }
}
