<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FirebaseHelper;

class FirebaseTestController extends Controller
{
    /**
     * Afficher la configuration Firebase pour le débogage
     */
    public function test()
    {
        $config = [
            'project_id' => config('firebase.project_id'),
            'storage_bucket' => config('firebase.storage_bucket'),
            'api_key' => config('firebase.api_key'),
            'auth_domain' => config('firebase.auth_domain'),
            'messaging_sender_id' => config('firebase.messaging_sender_id'),
            'app_id' => config('firebase.app_id'),
        ];
        
        $isConfigured = FirebaseHelper::isConfigured();
        $error = FirebaseHelper::getConfigurationError();
        
        return response()->json([
            'configured' => $isConfigured,
            'error' => $error,
            'config' => $config,
            'js_config' => FirebaseHelper::getJsConfig(),
            'env_vars' => [
                'FIREBASE_PROJECT_ID' => env('FIREBASE_PROJECT_ID'),
                'FIREBASE_API_KEY' => env('FIREBASE_API_KEY'),
                'FIREBASE_STORAGE_BUCKET' => env('FIREBASE_STORAGE_BUCKET'),
            ]
        ]);
    }
    
    /**
     * Afficher une page de test Firebase
     */
    public function page()
    {
        $isConfigured = FirebaseHelper::isConfigured();
        $error = FirebaseHelper::getConfigurationError();
        $config = FirebaseHelper::getJsConfig();
        
        return view('firebase-test', compact('isConfigured', 'error', 'config'));
    }
}
