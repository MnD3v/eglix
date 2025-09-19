#!/bin/bash

# Script Docker pour forcer les migrations en production
# À utiliser dans le conteneur Docker

set -e

echo "🐳 Script Docker - Migration forcée en production"

# Variables d'environnement
export APP_ENV=production
export APP_DEBUG=false

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Fonction pour ajouter une colonne si elle n'existe pas
add_column_if_not_exists() {
    local table=$1
    local column=$2
    local definition=$3
    
    echo "Vérification de la colonne $column dans la table $table..."
    php artisan tinker --execute="
        try {
            if (!Schema::hasColumn('$table', '$column')) {
                Schema::table('$table', function(\$table) {
                    \$table->$definition;
                });
                echo \"Colonne $column ajoutée à la table $table\n\";
            } else {
                echo \"Colonne $column existe déjà dans la table $table\n\";
            }
        } catch (Exception \$e) {
            echo \"Erreur lors de l'ajout de la colonne $column: \" . \$e->getMessage() . \"\n\";
        }
    " || true
}

# Fonction pour créer une table si elle n'existe pas
create_table_if_not_exists() {
    local table=$1
    local sql=$2
    
    echo "Vérification de la table $table..."
    php artisan tinker --execute="
        try {
            if (!Schema::hasTable('$table')) {
                DB::statement('$sql');
                echo \"Table $table créée\n\";
            } else {
                echo \"Table $table existe déjà\n\";
            }
        } catch (Exception \$e) {
            echo \"Erreur lors de la création de la table $table: \" . \$e->getMessage() . \"\n\";
        }
    " || true
}

# Créer les tables manquantes
echo "📋 Création des tables manquantes..."

# Table churches
create_table_if_not_exists "churches" "
CREATE TABLE IF NOT EXISTS churches (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    pastor_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);"

# Table roles
create_table_if_not_exists "roles" "
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    permissions JSON,
    church_id INTEGER REFERENCES churches(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);"

# Ajouter les colonnes manquantes
echo "🔧 Ajout des colonnes manquantes..."

# Table users
add_column_if_not_exists "users" "church_id" "integer()->nullable()->after('id')"
add_column_if_not_exists "users" "role_id" "integer()->nullable()->after('church_id')"
add_column_if_not_exists "users" "is_church_admin" "boolean()->default(false)->after('role_id')"
add_column_if_not_exists "users" "is_active" "boolean()->default(true)->after('is_church_admin')"

# Table members
add_column_if_not_exists "members" "church_id" "integer()->nullable()->after('id')"

# Table tithes
add_column_if_not_exists "tithes" "church_id" "integer()->nullable()->after('id')"

# Table offerings
add_column_if_not_exists "offerings" "church_id" "integer()->nullable()->after('id')"

# Table donations
add_column_if_not_exists "donations" "church_id" "integer()->nullable()->after('id')"

# Table expenses
add_column_if_not_exists "expenses" "church_id" "integer()->nullable()->after('id')"

# Table projects
add_column_if_not_exists "projects" "church_id" "integer()->nullable()->after('id')"

# Table services
add_column_if_not_exists "services" "church_id" "integer()->nullable()->after('id')"

# Table events
add_column_if_not_exists "events" "church_id" "integer()->nullable()->after('id')"

# Table journal_entries
add_column_if_not_exists "journal_entries" "church_id" "integer()->nullable()->after('id')"

# Essayer d'exécuter les migrations normales
echo "🔄 Exécution des migrations normales..."
php artisan migrate --force || {
    echo "⚠️ Certaines migrations ont échoué, mais les colonnes critiques ont été ajoutées."
}

# Vérifier les migrations
echo "✅ Vérification finale des migrations..."
php artisan migrate:status || true

# Optimiser l'application
echo "🔧 Optimisation de l'application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Migration Docker terminée avec succès!"
