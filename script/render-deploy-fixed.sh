#!/bin/bash

# Script de déploiement spécifique pour Render
# Gère les erreurs de service providers manquants

set -e

echo "🚀 Déploiement Render - Début..."

# Variables d'environnement Render
export APP_ENV=production
export APP_DEBUG=false

# Nettoyer les caches existants
echo "🧹 Nettoyage des caches..."
php artisan config:clear || echo "⚠️ Config clear échoué"
php artisan cache:clear || echo "⚠️ Cache clear échoué"
php artisan route:clear || echo "⚠️ Route clear échoué"
php artisan view:clear || echo "⚠️ View clear échoué"

# Installer les dépendances sans les packages de développement
echo "📦 Installation des dépendances..."
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

# Configuration de base
echo "⚙️ Configuration de base..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ Fichier .env créé"
fi

# Générer la clé d'application si nécessaire
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    php artisan key:generate --force || echo "⚠️ Génération de clé échouée"
fi

# Découvrir les packages de manière sécurisée
echo "🔍 Découverte des packages..."
php artisan package:discover --ansi || echo "⚠️ Package discover échoué, continuons..."

# Migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force || echo "⚠️ Migrations échouées, continuons..."

# Optimisations (optionnelles, peuvent échouer)
echo "🔧 Optimisations..."
php artisan config:cache || echo "⚠️ Config cache échoué"
php artisan route:cache || echo "⚠️ Route cache échoué"
php artisan view:cache || echo "⚠️ View cache échoué"

# Permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "✅ Déploiement Render terminé!"
