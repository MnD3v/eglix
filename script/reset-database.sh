#!/usr/bin/env bash

echo "=========================================="
echo "🔄 RESET COMPLET DE LA BASE DE DONNÉES"
echo "=========================================="

# Vérifier que nous sommes en mode développement ou que DB_RESET est explicitement activé
if [ "${APP_ENV}" = "production" ] && [ "${DB_RESET_ON_DEPLOY}" != "1" ] && [ "${DB_RESET_ON_DEPLOY}" != "true" ]; then
    echo "❌ ERREUR: Reset de base de données désactivé en production"
    echo "Pour forcer le reset en production, définissez DB_RESET_ON_DEPLOY=1"
    exit 1
fi

echo "🗑️  Nettoyage des caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

echo "🗑️  Suppression de toutes les tables..."
php artisan migrate:fresh --force --no-interaction || true

echo "🌱 Exécution des migrations..."
php artisan migrate --force --no-interaction || true

echo "🌱 Exécution des seeders..."
php artisan db:seed --force --no-interaction || true

echo "🔗 Création du lien de stockage..."
php artisan storage:link || true

echo "⚡ Optimisation de l'autoloader..."
composer dump-autoload --optimize --working-dir=/var/www/html || true

echo "✅ Reset de la base de données terminé avec succès!"
echo "=========================================="
