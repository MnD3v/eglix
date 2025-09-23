#!/bin/bash

# Script de démarrage pour Laravel Cloud
echo "🚀 Démarrage de l'application Laravel..."

# Vérifier les variables d'environnement
echo "📋 Vérification des variables d'environnement..."
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY non définie, génération..."
    php artisan key:generate --force
fi

# Déboguer les routes
echo "🐛 Débogage des routes..."
php artisan debug:routes

# Démarrer le serveur
echo "🌐 Démarrage du serveur web..."
exec php -S 0.0.0.0:$PORT -t public
