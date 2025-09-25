<?php

namespace App\Services;

use App\Models\Church;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class ChurchLinkService
{
    /**
     * Génère un lien unique crypté pour l'inscription à une église
     */
    public function generateRegistrationLink(Church $church): string
    {
        // Crypter directement l'ID de l'église
        $encryptedChurchId = Crypt::encryptString($church->id);
        
        // Encoder pour URL (supprimer les caractères spéciaux)
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encryptedChurchId));
    }
    
    /**
     * Décrypte et valide un lien d'inscription
     */
    public function decryptRegistrationLink(string $encryptedToken): ?array
    {
        try {
            // Décoder depuis l'URL (remettre les caractères spéciaux)
            $encryptedToken = str_replace(['-', '_'], ['+', '/'], $encryptedToken);
            
            // Ajouter le padding si nécessaire
            $padding = strlen($encryptedToken) % 4;
            if ($padding) {
                $encryptedToken .= str_repeat('=', 4 - $padding);
            }
            
            // Décoder base64
            $encryptedChurchId = base64_decode($encryptedToken);
            
            // Décrypter l'ID de l'église
            $churchId = Crypt::decryptString($encryptedChurchId);
            
            // Vérifier que l'église existe et est active
            $church = Church::find($churchId);
            if (!$church || !$church->is_active) {
                return null;
            }
            
            return [
                'church' => $church,
                'church_id' => $churchId,
                'church_name' => $church->name
            ];
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Génère un lien court pour le partage
     */
    public function generateShortLink(Church $church): string
    {
        $encryptedToken = $this->generateRegistrationLink($church);
        return route('members.register', ['token' => $encryptedToken]);
    }
    
    /**
     * Valide si un lien est encore valide (optionnel: avec expiration)
     */
    public function isLinkValid(array $decryptedData, int $maxAgeHours = 168): bool
    {
        // Pour cette version simplifiée, on considère que le lien est toujours valide
        // tant que l'église existe et est active
        return isset($decryptedData['church']) && $decryptedData['church']->is_active;
    }
}
