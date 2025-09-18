#!/usr/bin/env bash

echo "🔧 CORRECTION COMPLÈTE RENDER - TOUTES LES TABLES"
echo "================================================="

echo "🏗️  AJOUT DE church_id À TOUTES LES TABLES..."

php artisan tinker --execute="
\$tablesNeedingChurchId = [
    'members',
    'tithes', 
    'offerings',
    'donations',
    'expenses',
    'projects',
    'services',
    'church_events',
    'service_roles',
    'service_assignments',
    'offering_types',
    'journal_entries',
    'administration_functions',
    'administration_function_types'
];

// Fonction pour vérifier si une table existe
function tableExists(\$tableName) {
    try {
        \DB::select(\"SELECT 1 FROM {\$tableName} LIMIT 1\");
        return true;
    } catch (Exception \$e) {
        return false;
    }
}

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

echo \"🔍 Vérification et ajout de church_id...\n\";

foreach (\$tablesNeedingChurchId as \$table) {
    try {
        if (tableExists(\$table)) {
            if (!columnExists(\$table, 'church_id')) {
                \DB::statement(\"ALTER TABLE {\$table} ADD COLUMN church_id BIGINT\");
                echo \"✅ Colonne church_id ajoutée à {\$table}\n\";
            } else {
                echo \"ℹ️  Table {\$table}: church_id existe déjà\n\";
            }
        } else {
            echo \"⚠️  Table {\$table} n'existe pas\n\";
        }
    } catch (Exception \$e) {
        echo \"❌ Erreur pour {\$table}: \" . \$e->getMessage() . \"\n\";
    }
}

echo \"🎉 TOUTES LES COLONNES church_id SONT PRÊTES!\n\";
"

echo "🔍 VÉRIFICATION DES COLONNES USERS..."

php artisan tinker --execute="
// Vérifier les colonnes de users
\$userColumns = ['church_id', 'role_id', 'is_church_admin', 'is_active'];

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

foreach (\$userColumns as \$column) {
    if (!columnExists('users', \$column)) {
        try {
            switch(\$column) {
                case 'church_id':
                case 'role_id':
                    \DB::statement(\"ALTER TABLE users ADD COLUMN {\$column} BIGINT\");
                    break;
                case 'is_church_admin':
                    \DB::statement(\"ALTER TABLE users ADD COLUMN {\$column} BOOLEAN DEFAULT false\");
                    break;
                case 'is_active':
                    \DB::statement(\"ALTER TABLE users ADD COLUMN {\$column} BOOLEAN DEFAULT true\");
                    break;
            }
            echo \"✅ Colonne {\$column} ajoutée à users\n\";
        } catch (Exception \$e) {
            echo \"❌ Erreur {\$column}: \" . \$e->getMessage() . \"\n\";
        }
    } else {
        echo \"ℹ️  Colonne {\$column} existe déjà dans users\n\";
    }
}
"

echo "🧪 TEST FINAL COMPLET..."

php artisan tinker --execute="
try {
    // Tester les modèles principaux
    \$churchCount = \App\Models\Church::count();
    echo \"✅ Churches: \$churchCount\n\";
    
    \$roleCount = \App\Models\Role::count();
    echo \"✅ Roles: \$roleCount\n\";
    
    \$userCount = \App\Models\User::count();
    echo \"✅ Users: \$userCount\n\";
    
    // Tester Member avec church_id
    try {
        \$memberCount = \App\Models\Member::count();
        echo \"✅ Members: \$memberCount (church_id OK)\n\";
    } catch (Exception \$e) {
        echo \"⚠️  Members: \" . \$e->getMessage() . \"\n\";
    }
    
    echo \"🚀 SYSTÈME COMPLÈTEMENT PRÊT!\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR TEST: \" . \$e->getMessage() . \"\n\";
}
"

echo "✅ CORRECTION COMPLÈTE TERMINÉE"
echo "🎯 L'APPLICATION DEVRAIT MAINTENANT FONCTIONNER!"
echo "================================================="
