#!/usr/bin/env sh
set -e

# Ensure writable dirs
umask 0002
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true
# Ensure permissive rwx for dirs and rw for files
find storage -type d -exec chmod 775 {} \; || true
find storage -type f -exec chmod 664 {} \; || true
chmod -R 775 bootstrap/cache || true
chmod -R a+r public || true

# Ensure log file exists and writable
touch storage/logs/laravel.log || true
chown www-data:www-data storage/logs/laravel.log || true
chmod 664 storage/logs/laravel.log || true

# Ensure SQLite database is present and writable when used
if [ -f database/database.sqlite ] || grep -qE '^DB_CONNECTION=sqlite' .env 2>/dev/null; then
  mkdir -p database || true
  [ -f database/database.sqlite ] || touch database/database.sqlite || true
  chown -R www-data:www-data database || true
  chmod 775 database || true
  chmod 664 database/database.sqlite || true
fi

# Use runtime env from Render (avoid stale cached config)
php artisan config:clear || echo "⚠️ Config clear échoué, continuons..."
php artisan route:clear || echo "⚠️ Route clear échoué, continuons..."
php artisan view:clear || echo "⚠️ View clear échoué, continuons..."
php artisan event:clear || echo "⚠️ Event clear échoué, continuons..."

# Try to discover packages safely
php artisan package:discover --ansi || echo "⚠️ Package discover échoué, continuons..."

# Exécuter le script de correction administration_functions si nécessaire
if [ "${FIX_ADMINISTRATION_FUNCTIONS}" = "1" ] || [ "${FIX_ADMINISTRATION_FUNCTIONS}" = "true" ] || [ "${FIX_ADMINISTRATION_FUNCTIONS}" = "TRUE" ]; then
    echo "[start.sh] FIX_ADMINISTRATION_FUNCTIONS est activé -> exécution du script de correction"
    /usr/local/bin/fix-administration-functions.sh || echo "⚠️ Script de correction administration_functions échoué, continuons..."
fi

# Exécuter le script de migration forcée si nécessaire
if [ "${FORCE_MIGRATIONS}" = "1" ] || [ "${FORCE_MIGRATIONS}" = "true" ] || [ "${FORCE_MIGRATIONS}" = "TRUE" ]; then
    echo "[start.sh] FORCE_MIGRATIONS est activé -> exécution du script de migration forcée"
    /usr/local/bin/force-migrations.sh || echo "⚠️ Script de migration forcée échoué, continuons..."
fi

# Ensure we have an .env; create from example if absent
if [ ! -f .env ]; then
  cp .env.example .env || true
fi

# Generate and persist key if not provided by env
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force || true
  # Export the generated key for the current process
  if [ -f .env ]; then
    export APP_KEY=$(grep -E '^APP_KEY=' .env | cut -d '=' -f2-)
  fi
fi

# Optional: reset database on deploy (use with caution in production)
if [ "${DB_RESET_ON_DEPLOY}" = "1" ] || [ "${DB_RESET_ON_DEPLOY}" = "true" ] || [ "${DB_RESET_ON_DEPLOY}" = "TRUE" ]; then
  echo "[start.sh] DB_RESET_ON_DEPLOY is enabled -> running migrate:fresh"
  php artisan migrate:fresh --force --no-interaction || true
fi

# Run migrations with retries (DB might not be ready yet)
MAX_TRIES=20
SLEEP_SECONDS=3
TRY=1
until php artisan migrate:status >/dev/null 2>&1 || [ $TRY -gt $MAX_TRIES ]; do
  echo "[start.sh] Waiting for database... attempt $TRY/$MAX_TRIES"
  TRY=$((TRY+1))
  sleep $SLEEP_SECONDS
done

php artisan migrate --force --no-interaction || true
php artisan storage:link || true

exec apache2-foreground


