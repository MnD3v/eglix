<?php

return [
    // Configuration Firebase basée sur google-services.json
    'project_id' => env('FIREBASE_PROJECT_ID', 'xboite-d7c80'),
    'project_number' => env('FIREBASE_PROJECT_NUMBER', '457797490593'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'xboite-d7c80.firebasestorage.app'),
    'api_key' => env('FIREBASE_API_KEY', 'AIzaSyA7Ab1IkCU0tpEkclalxx3t2eb76odNuAk'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'xboite-d7c80.firebaseapp.com'),
    'storage_url' => env('FIREBASE_STORAGE_URL', 'https://firebasestorage.googleapis.com/v0/b/xboite-d7c80.firebasestorage.app/o/'),
    'upload_url' => env('FIREBASE_UPLOAD_URL', 'https://firebasestorage.googleapis.com/v0/b/xboite-d7c80.firebasestorage.app/o/'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '457797490593'),
    'app_id' => env('FIREBASE_APP_ID', '1:457797490593:web:eglix-web'),
    
    // Configuration pour vérifier si Firebase est correctement configuré
    'is_configured' => function() {
        $apiKey = env('FIREBASE_API_KEY');
        $projectId = env('FIREBASE_PROJECT_ID');
        $storageBucket = env('FIREBASE_STORAGE_BUCKET');
        
        return $apiKey && 
               $apiKey !== 'xxxx' && 
               $projectId && 
               $projectId !== 'xxxx' &&
               $storageBucket && 
               $storageBucket !== 'xxxx.appspot.com';
    },
    
    // Règles de sécurité Firebase Storage (pour référence)
    'storage_rules' => [
        'rules_version' => '2',
        'service' => 'firebase.storage',
        'match' => '/b/{bucket}/o',
        'allow' => [
            'read' => 'if true', // Permettre la lecture à tous
            'write' => 'if true' // Permettre l'écriture à tous (à sécuriser en production)
        ]
    ]
];
