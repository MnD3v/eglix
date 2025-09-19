#!/usr/bin/env bash

echo "📸 TÉLÉCHARGEMENT DE L'IMAGE DE CONNEXION"
echo "========================================"

# Créer le dossier images s'il n'existe pas
mkdir -p public/images

# URL de l'image
IMAGE_URL="https://i.ibb.co/BvmsKVV/Capture-d-cran-2025-09-18-201325.png"
LOCAL_PATH="public/images/auth-background.png"

echo "🔗 URL de l'image: $IMAGE_URL"
echo "📁 Chemin local: $LOCAL_PATH"

# Télécharger l'image
echo "⬇️ Téléchargement en cours..."
if curl -L -o "$LOCAL_PATH" "$IMAGE_URL"; then
    echo "✅ Image téléchargée avec succès"
    
    # Vérifier que le fichier existe et a une taille raisonnable
    if [ -f "$LOCAL_PATH" ] && [ $(stat -c%s "$LOCAL_PATH") -gt 1000 ]; then
        echo "✅ Fichier vérifié (taille: $(stat -c%s "$LOCAL_PATH") bytes)"
        
        # Créer une copie pour l'inscription
        cp "$LOCAL_PATH" "public/images/auth-background-register.png"
        echo "✅ Copie créée pour la page d'inscription"
        
        echo ""
        echo "🎉 TÉLÉCHARGEMENT TERMINÉ!"
        echo "L'image est maintenant disponible localement:"
        echo "- public/images/auth-background.png"
        echo "- public/images/auth-background-register.png"
        
    else
        echo "❌ Erreur: Le fichier téléchargé est trop petit ou n'existe pas"
        exit 1
    fi
else
    echo "❌ Erreur lors du téléchargement"
    exit 1
fi

