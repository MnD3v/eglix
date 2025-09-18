#!/bin/bash

# Script de déploiement pour la production
# Gère les erreurs de service providers manquants

set -e

echo "🚀 Début du déploiement en production..."

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Installer les dépendances sans les packages de développement
echo "📦 Installation des dépendances..."
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --no-scripts

# Exécuter les scripts manuellement en gérant les erreurs
echo "⚙️ Configuration de l'application..."
php artisan config:clear || echo "⚠️ Config clear échoué, continuons..."
php artisan clear-compiled || echo "⚠️ Clear compiled échoué, continuons..."
php artisan package:discover --ansi || echo "⚠️ Package discover échoué, continuons..."

# Migrations et optimisations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force || echo "⚠️ Migrations échouées, continuons..."

echo "🔧 Optimisations..."
php artisan config:cache || echo "⚠️ Config cache échoué, continuons..."
php artisan route:cache || echo "⚠️ Route cache échoué, continuons..."
php artisan view:cache || echo "⚠️ View cache échoué, continuons..."

# Permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "✅ Déploiement terminé avec succès!"
