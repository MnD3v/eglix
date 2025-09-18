#!/usr/bin/env bash

echo "=========================================="
echo "🚀 DÉPLOIEMENT LARAVEL"
echo "=========================================="

echo "📦 Installation des dépendances..."
composer install --no-dev --working-dir=/var/www/html

echo "🔧 Configuration des permissions..."
# Correction des permissions pour storage et bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Permissions spécifiques pour SQLite
if [ -f "/var/www/html/database/database.sqlite" ]; then
    chmod 664 /var/www/html/database/database.sqlite
    chmod 775 /var/www/html/database
fi

# Changement de propriétaire si possible (certains environnements l'interdisent)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database 2>/dev/null || true

echo "📁 Création des répertoires de stockage..."
# S'assurer que les répertoires de storage existent
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions  
mkdir -p /var/www/html/storage/framework/views

echo "🧹 Nettoyage des caches..."
# Nettoyer les caches avant de les reconstruire
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Option de reset complet de la base de données
if [ "${DB_RESET_ON_DEPLOY}" = "1" ] || [ "${DB_RESET_ON_DEPLOY}" = "true" ]; then
    echo "🔄 RESET COMPLET DE LA BASE DE DONNÉES ACTIVÉ"
    echo "🗑️  Suppression de toutes les tables..."
    php artisan migrate:fresh --force --no-interaction || true
    
    echo "🌱 Exécution des migrations..."
    php artisan migrate --force --no-interaction || true
    
    echo "🌱 Exécution des seeders..."
    php artisan db:seed --force --no-interaction || true
else
    echo "📊 Exécution des migrations..."
    php artisan migrate --force --no-interaction || true
fi

echo "💾 Mise en cache de la configuration..."
php artisan config:cache

echo "🛣️  Mise en cache des routes..."
php artisan route:cache

echo "👁️  Mise en cache des vues..."
php artisan view:cache

echo "🔗 Création du lien de stockage..."
php artisan storage:link || true

echo "⚡ Optimisation de l'autoloader..."
composer dump-autoload --optimize --working-dir=/var/www/html

echo "✅ Déploiement terminé avec succès!"
echo "=========================================="
