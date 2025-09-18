#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Setting permissions..."
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

echo "Creating storage directories..."
# S'assurer que les répertoires de storage existent
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions  
mkdir -p /var/www/html/storage/framework/views

echo "Clearing caches..."
# Nettoyer les caches avant de les reconstruire
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Creating storage link..."
php artisan storage:link || true

echo "Optimizing autoloader..."
composer dump-autoload --optimize --working-dir=/var/www/html

echo "Deployment completed successfully!"
