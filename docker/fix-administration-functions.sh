#!/bin/bash

# Script Docker spécifique pour corriger la table administration_functions
# À utiliser dans le conteneur Docker en production

set -e

echo "🐳 Script Docker - Correction administration_functions"
echo "======================================================"

# Variables d'environnement
export APP_ENV=production
export APP_DEBUG=false

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Fonction pour créer la table administration_functions
create_administration_functions_table() {
    echo "📋 Création de la table administration_functions..."
    
    php artisan tinker --execute="
        try {
            if (!Schema::hasTable('administration_functions')) {
                echo \"Création de la table administration_functions...\n\";
                
                Schema::create('administration_functions', function (\$table) {
                    \$table->id();
                    \$table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                    \$table->string('function_name');
                    \$table->date('start_date');
                    \$table->date('end_date')->nullable();
                    \$table->text('notes')->nullable();
                    \$table->boolean('is_active')->default(true);
                    \$table->timestamps();
                });
                
                echo \"✅ Table administration_functions créée avec succès\n\";
            } else {
                echo \"✅ Table administration_functions existe déjà\n\";
            }
        } catch (Exception \$e) {
            echo \"❌ ERREUR lors de la création: \" . \$e->getMessage() . \"\n\";
            exit(1);
        }
    " || {
        echo "⚠️ Erreur lors de la création via Tinker, tentative alternative..."
        
        # Méthode alternative avec SQL direct
        php artisan tinker --execute="
            try {
                DB::statement('
                    CREATE TABLE IF NOT EXISTS administration_functions (
                        id SERIAL PRIMARY KEY,
                        member_id INTEGER NOT NULL,
                        function_name VARCHAR(255) NOT NULL,
                        start_date DATE NOT NULL,
                        end_date DATE NULL,
                        notes TEXT NULL,
                        is_active BOOLEAN DEFAULT true,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
                    )
                ');
                echo \"✅ Table administration_functions créée via SQL direct\n\";
            } catch (Exception \$e) {
                echo \"❌ ERREUR SQL direct: \" . \$e->getMessage() . \"\n\";
                exit(1);
            }
        "
    }
}

# Fonction pour créer la table administration_function_types si nécessaire
create_administration_function_types_table() {
    echo "📋 Vérification de la table administration_function_types..."
    
    php artisan tinker --execute="
        try {
            if (!Schema::hasTable('administration_function_types')) {
                echo \"Création de la table administration_function_types...\n\";
                
                Schema::create('administration_function_types', function (\$table) {
                    \$table->id();
                    \$table->string('name');
                    \$table->text('description')->nullable();
                    \$table->boolean('is_active')->default(true);
                    \$table->integer('sort_order')->default(0);
                    \$table->timestamps();
                });
                
                echo \"✅ Table administration_function_types créée avec succès\n\";
            } else {
                echo \"✅ Table administration_function_types existe déjà\n\";
            }
        } catch (Exception \$e) {
            echo \"⚠️ Erreur administration_function_types: \" . \$e->getMessage() . \"\n\";
        }
    " || true
}

# Exécuter les créations de tables
create_administration_functions_table
create_administration_function_types_table

# Essayer d'exécuter les migrations normales
echo "🔄 Exécution des migrations normales..."
php artisan migrate --force || {
    echo "⚠️ Certaines migrations ont échoué, mais les tables critiques ont été créées."
}

# Vérifier les migrations
echo "✅ Vérification finale des migrations..."
php artisan migrate:status || true

# Test de fonctionnement
echo "🧪 Test de fonctionnement de la table administration_functions..."
php artisan tinker --execute="
try {
    \$count = DB::table('administration_functions')->count();
    echo \"✅ Table administration_functions fonctionne correctement\n\";
    echo \"📊 Nombre d'enregistrements: \$count\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR lors du test: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Optimiser l'application
echo "🔧 Optimisation de l'application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Correction Docker administration_functions terminée avec succès!"
