#!/bin/bash

# Script pour forcer toutes les migrations en production
# Ce script supprime toutes les tables et refait toutes les migrations

set -e

echo "🚨 ATTENTION: Ce script va supprimer TOUTES les données de la base de données !"
echo "📋 Assurez-vous d'avoir une sauvegarde avant de continuer."
echo ""
read -p "Êtes-vous sûr de vouloir continuer ? (tapez 'OUI' pour confirmer): " confirmation

if [ "$confirmation" != "OUI" ]; then
    echo "❌ Opération annulée."
    exit 1
fi

echo "🔄 Début du processus de migration forcée..."

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Supprimer toutes les tables
echo "🗑️ Suppression de toutes les tables..."
php artisan db:wipe --force || {
    echo "⚠️ db:wipe échoué, tentative de suppression manuelle..."
    
    # Liste des tables à supprimer
    TABLES=(
        "migrations"
        "users"
        "churches"
        "roles"
        "members"
        "tithes"
        "offerings"
        "donations"
        "expenses"
        "projects"
        "services"
        "service_assignments"
        "service_roles"
        "events"
        "journal_entries"
        "journal_images"
        "offering_types"
        "function_types"
        "sessions"
        "password_reset_tokens"
        "failed_jobs"
        "personal_access_tokens"
    )
    
    for table in "${TABLES[@]}"; do
        echo "Suppression de la table: $table"
        php artisan tinker --execute="DB::statement('DROP TABLE IF EXISTS \"$table\" CASCADE;');" || true
    done
}

# Recréer toutes les migrations
echo "🔄 Exécution de toutes les migrations..."
php artisan migrate:fresh --force --seed || {
    echo "⚠️ migrate:fresh échoué, tentative de migration normale..."
    php artisan migrate --force || true
}

# Vérifier les migrations
echo "✅ Vérification des migrations..."
php artisan migrate:status

# Optimiser l'application
echo "🔧 Optimisation de l'application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

echo "✅ Migration forcée terminée avec succès!"
echo "📊 Vérifiez que toutes les tables sont créées correctement."
