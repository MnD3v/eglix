<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class ChurchIdEncryptionService
{
    /**
     * Chiffrer un ID d'église
     */
    public static function encrypt($churchId)
    {
        return Crypt::encryptString($churchId);
    }

    /**
     * Déchiffrer un ID d'église
     */
    public static function decrypt($encryptedId)
    {
        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('ID d\'église invalide');
        }
    }

    /**
     * Générer un lien d'inscription sécurisé
     */
    public static function generateRegistrationLink($churchId)
    {
        $encryptedId = self::encrypt($churchId);
        return route('members.public.create', ['church_id' => $encryptedId]);
    }

    /**
     * Valider et décrypter un ID d'église depuis la requête
     */
    public static function validateAndDecrypt($encryptedId)
    {
        if (empty($encryptedId)) {
            throw new \InvalidArgumentException('ID d\'église manquant');
        }

        $churchId = self::decrypt($encryptedId);
        
        // Vérifier que l'ID est numérique
        if (!is_numeric($churchId)) {
            throw new \InvalidArgumentException('ID d\'église invalide');
        }

        return (int) $churchId;
    }
}
