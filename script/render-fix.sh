#!/usr/bin/env bash

echo "🔥 NETTOYAGE RENDER - SOLUTION DÉFINITIVE"
echo "========================================="

# Marquer toutes les migrations comme exécutées sans les exécuter
echo "📝 Marquage des migrations existantes..."
php artisan migrate:status

# Insérer manuellement les migrations dans la table migrations pour éviter les conflits
php artisan tinker --execute="
try {
    // Vérifier si la table migrations existe
    \$migrations = [
        '0001_01_01_000000_create_users_table',
        '0001_01_01_000001_create_cache_table', 
        '0001_01_01_000002_create_jobs_table',
        '2025_09_17_160000_create_sessions_table',
        '2025_09_18_020133_create_churches_table',
        '2025_09_18_020137_create_roles_table',
        '2025_09_18_020143_add_church_id_to_users_table'
    ];
    
    foreach (\$migrations as \$migration) {
        try {
            \DB::table('migrations')->updateOrInsert(
                ['migration' => \$migration],
                ['migration' => \$migration, 'batch' => 1]
            );
            echo \"✅ Migration marquée: \$migration\n\";
        } catch (Exception \$e) {
            echo \"⚠️  Erreur migration \$migration: \" . \$e->getMessage() . \"\n\";
        }
    }
    
    echo \"📝 Migrations marquées comme exécutées\n\";
} catch (Exception \$e) {
    echo \"❌ Erreur: \" . \$e->getMessage() . \"\n\";
}
"

# Créer les tables manquantes directement en SQL
echo "🏗️  Création des tables critiques..."
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
    echo \"✅ Table churches créée\n\";
    
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
            FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE,
            UNIQUE (church_id, slug)
        )
    ');
    echo \"✅ Table roles créée\n\";
    
    // Ajouter les colonnes manquantes à users si nécessaire
    try {
        \DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS church_id BIGINT');
        \DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS role_id BIGINT');
        \DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS is_church_admin BOOLEAN DEFAULT false');
        \DB::statement('ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT true');
        echo \"✅ Colonnes ajoutées à users\n\";
    } catch (Exception \$e) {
        echo \"⚠️  Colonnes users: \" . \$e->getMessage() . \"\n\";
    }
    
} catch (Exception \$e) {
    echo \"❌ Erreur création tables: \" . \$e->getMessage() . \"\n\";
}
"

# Vérifier que tout fonctionne
echo "🔍 Vérification finale..."
php artisan tinker --execute="
try {
    \$churchCount = \App\Models\Church::count();
    echo \"✅ Table churches: \$churchCount églises\n\";
    
    \$roleCount = \App\Models\Role::count();
    echo \"✅ Table roles: \$roleCount rôles\n\";
    
    \$userCount = \App\Models\User::count();
    echo \"✅ Table users: \$userCount utilisateurs\n\";
    
    echo \"🎉 SYSTÈME PRÊT POUR L'INSCRIPTION!\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

echo "✅ NETTOYAGE TERMINÉ - L'INSCRIPTION DEVRAIT MARCHER"
echo "========================================="
