#!/usr/bin/env bash

echo "=========================================="
echo "🚀 RENDER DEPLOYMENT SCRIPT"
echo "=========================================="

# Vérifier la connexion à la base de données
echo "🔍 Vérification de la connexion à la base de données..."
MAX_TRIES=30
SLEEP_SECONDS=2
TRY=1

until php artisan migrate:status >/dev/null 2>&1 || [ $TRY -gt $MAX_TRIES ]; do
    echo "⏳ Attente de la base de données... tentative $TRY/$MAX_TRIES"
    TRY=$((TRY+1))
    sleep $SLEEP_SECONDS
done

if [ $TRY -gt $MAX_TRIES ]; then
    echo "❌ Impossible de se connecter à la base de données"
    echo "Vérifiez vos variables d'environnement DB_*"
    exit 1
fi

echo "✅ Connexion à la base de données établie"

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan cache:clear || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Vérifier l'état des migrations
echo "📊 Vérification de l'état des migrations..."
php artisan migrate:status

# Exécuter les migrations
echo "🌱 Exécution des migrations..."
php artisan migrate --force --no-interaction

# Vérifier que les tables importantes existent
echo "🔍 Vérification des tables critiques..."
php artisan tinker --execute="
try {
    \App\Models\Church::count();
    echo '✅ Table churches: OK\n';
} catch (Exception \$e) {
    echo '❌ Table churches: ERREUR - ' . \$e->getMessage() . '\n';
}

try {
    \App\Models\Role::count();
    echo '✅ Table roles: OK\n';
} catch (Exception \$e) {
    echo '❌ Table roles: ERREUR - ' . \$e->getMessage() . '\n';
}

try {
    \App\Models\User::count();
    echo '✅ Table users: OK\n';
} catch (Exception \$e) {
    echo '❌ Table users: ERREUR - ' . \$e->getMessage() . '\n';
}
"

# Créer le lien de stockage
echo "🔗 Création du lien de stockage..."
php artisan storage:link || true

# Optimiser l'autoloader
echo "⚡ Optimisation de l'autoloader..."
composer dump-autoload --optimize --working-dir=/var/www/html || true

echo "✅ Déploiement Render terminé avec succès!"
echo "=========================================="
