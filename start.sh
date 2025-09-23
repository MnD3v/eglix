#!/bin/bash

# Script de démarrage pour Laravel Cloud
echo "🚀 Démarrage de l'application Laravel..."

# Vérifier les variables d'environnement
echo "📋 Vérification des variables d'environnement..."
if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY non définie, génération..."
    php artisan key:generate --force
fi

# Vérifier la base de données et les routes
echo "🔍 Vérification de la base de données et des routes..."
php artisan test:routes

# Démarrer le serveur
echo "🌐 Démarrage du serveur web..."
exec php -S 0.0.0.0:$PORT -t public
