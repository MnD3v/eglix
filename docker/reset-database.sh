#!/usr/bin/env bash

echo "=========================================="
echo "🐳 RESET DOCKER - BASE DE DONNÉES"
echo "=========================================="

# Vérifier que nous sommes dans un conteneur Docker
if [ ! -f /.dockerenv ]; then
    echo "⚠️  Ce script est conçu pour s'exécuter dans un conteneur Docker"
    echo "Pour l'exécuter localement, utilisez: ./script/reset-database.sh"
fi

echo "🔍 Vérification de la connexion à la base de données..."
# Attendre que la base de données soit disponible
MAX_TRIES=30
SLEEP_SECONDS=2
TRY=1

until php artisan migrate:status >/dev/null 2>&1 || [ $TRY -gt $MAX_TRIES ]; do
    echo "⏳ Attente de la base de données... tentative $TRY/$MAX_TRIES"
    TRY=$((TRY+1))
    sleep $SLEEP_SECONDS
done

if [ $TRY -gt $MAX_TRIES ]; then
    echo "❌ Impossible de se connecter à la base de données après $MAX_TRIES tentatives"
    exit 1
fi

echo "✅ Connexion à la base de données établie"

echo "🧹 Nettoyage des caches..."
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

echo "✅ Reset Docker terminé avec succès!"
echo "=========================================="
