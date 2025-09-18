#!/usr/bin/env bash

echo "🆘 SCRIPT D'URGENCE RENDER - AJOUT COLONNES USERS"
echo "=================================================="

# FORCER L'AJOUT DES COLONNES MANQUANTES
echo "🔧 AJOUT FORCÉ DES COLONNES À LA TABLE USERS..."

php artisan tinker --execute="
try {
    echo \"🔍 Vérification de la table users...\n\";
    
    // Fonction pour vérifier si une colonne existe
    function columnExists(\$table, \$column) {
        try {
            \$result = \DB::select(\"
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = ? AND column_name = ?
            \", [\$table, \$column]);
            return count(\$result) > 0;
        } catch (Exception \$e) {
            return false;
        }
    }
    
    // Ajouter church_id si elle n'existe pas
    if (!columnExists('users', 'church_id')) {
        \DB::statement('ALTER TABLE users ADD COLUMN church_id BIGINT');
        echo \"✅ Colonne church_id ajoutée\n\";
    } else {
        echo \"ℹ️  Colonne church_id existe déjà\n\";
    }
    
    // Ajouter role_id si elle n'existe pas
    if (!columnExists('users', 'role_id')) {
        \DB::statement('ALTER TABLE users ADD COLUMN role_id BIGINT');
        echo \"✅ Colonne role_id ajoutée\n\";
    } else {
        echo \"ℹ️  Colonne role_id existe déjà\n\";
    }
    
    // Ajouter is_church_admin si elle n'existe pas
    if (!columnExists('users', 'is_church_admin')) {
        \DB::statement('ALTER TABLE users ADD COLUMN is_church_admin BOOLEAN DEFAULT false');
        echo \"✅ Colonne is_church_admin ajoutée\n\";
    } else {
        echo \"ℹ️  Colonne is_church_admin existe déjà\n\";
    }
    
    // Ajouter is_active si elle n'existe pas
    if (!columnExists('users', 'is_active')) {
        \DB::statement('ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT true');
        echo \"✅ Colonne is_active ajoutée\n\";
    } else {
        echo \"ℹ️  Colonne is_active existe déjà\n\";
    }
    
    echo \"🎉 TOUTES LES COLONNES SONT PRÊTES!\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Vérifier que les tables churches et roles existent
echo "🏗️  VÉRIFICATION DES TABLES CRITIQUES..."

php artisan tinker --execute="
try {
    // Créer la table churches si elle n'existe pas
    \DB::statement('
        CREATE TABLE IF NOT EXISTS churches (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            address VARCHAR(255),
            phone VARCHAR(20),
            email VARCHAR(255),
            website VARCHAR(255),
            logo VARCHAR(255),
            settings JSONB,
            is_active BOOLEAN DEFAULT true,
            created_at TIMESTAMP,
            updated_at TIMESTAMP
        )
    ');
    echo \"✅ Table churches OK\n\";
    
    // Créer la table roles si elle n'existe pas
    \DB::statement('
        CREATE TABLE IF NOT EXISTS roles (
            id BIGSERIAL PRIMARY KEY,
            church_id BIGINT NOT NULL,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            description TEXT,
            permissions JSONB,
            is_active BOOLEAN DEFAULT true,
            created_at TIMESTAMP,
            updated_at TIMESTAMP,
            UNIQUE (church_id, slug)
        )
    ');
    echo \"✅ Table roles OK\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR TABLES: \" . \$e->getMessage() . \"\n\";
}
"

# Test final
echo "🧪 TEST FINAL..."
php artisan tinker --execute="
try {
    \$churchCount = \App\Models\Church::count();
    \$roleCount = \App\Models\Role::count();
    \$userCount = \App\Models\User::count();
    
    echo \"✅ Churches: \$churchCount\n\";
    echo \"✅ Roles: \$roleCount\n\";
    echo \"✅ Users: \$userCount\n\";
    echo \"🚀 SYSTÈME PRÊT POUR L'INSCRIPTION!\n\";
    
} catch (Exception \$e) {
    echo \"❌ TEST ÉCHOUÉ: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

echo "✅ SCRIPT D'URGENCE TERMINÉ"
echo "🎯 TENTEZ L'INSCRIPTION MAINTENANT!"
echo "=================================================="
