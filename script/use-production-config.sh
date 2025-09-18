#!/bin/bash

# Script pour utiliser la configuration de production
# Remplace temporairement la configuration des service providers

set -e

echo "🔧 Configuration pour la production..."

# Sauvegarder la configuration actuelle
if [ -f config/app.php ]; then
    cp config/app.php config/app.php.backup
    echo "✅ Configuration sauvegardée"
fi

# Utiliser la configuration de production
if [ -f config/production-providers.php ]; then
    # Extraire la section providers de la config de production
    sed -n '/providers.*=>.*\[/,/],/p' config/production-providers.php > /tmp/production_providers.txt
    
    # Remplacer la section providers dans config/app.php
    awk '
    BEGIN { in_providers = 0; providers_replaced = 0 }
    /providers.*=>.*\[/ { 
        in_providers = 1
        if (!providers_replaced) {
            print "    \"providers\" => ["
            providers_replaced = 1
        }
        next
    }
    in_providers && /],/ { 
        in_providers = 0
        print "    ],"
        next
    }
    in_providers { next }
    { print }
    ' config/app.php > /tmp/app_new.php
    
    mv /tmp/app_new.php config/app.php
    echo "✅ Configuration de production appliquée"
else
    echo "⚠️ Fichier de configuration de production non trouvé"
fi

echo "🚀 Configuration terminée!"
