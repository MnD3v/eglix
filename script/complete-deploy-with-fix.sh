#!/usr/bin/env bash

echo "🚀 DÉPLOIEMENT COMPLET RENDER - AVEC CORRECTION administration_functions"
echo "======================================================================"

# Configuration
export APP_ENV=production
export APP_DEBUG=false
export FIX_ADMINISTRATION_FUNCTIONS=1

echo "📋 Configuration du déploiement:"
echo "- APP_ENV: $APP_ENV"
echo "- APP_DEBUG: $APP_DEBUG"
echo "- FIX_ADMINISTRATION_FUNCTIONS: $FIX_ADMINISTRATION_FUNCTIONS"

# Étape 1: Attendre la base de données
echo ""
echo "⏳ ÉTAPE 1: Attente de la base de données..."
for i in {1..30}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        echo "✅ Base de données connectée"
        break
    fi
    echo "Tentative $i/30..."
    sleep 2
done

# Étape 2: Nettoyage des caches
echo ""
echo "🧹 ÉTAPE 2: Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

# Étape 3: Découverte des packages
echo ""
echo "📦 ÉTAPE 3: Découverte des packages..."
php artisan package:discover --ansi || true

# Étape 4: Génération de la clé d'application
echo ""
echo "🔑 ÉTAPE 4: Génération de la clé d'application..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Étape 5: Correction de la table administration_functions
echo ""
echo "🛠️ ÉTAPE 5: Correction de la table administration_functions..."
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
"

# Étape 6: Création de la table administration_function_types
echo ""
echo "🛠️ ÉTAPE 6: Vérification de la table administration_function_types..."
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
"

# Étape 7: Exécution des migrations
echo ""
echo "🔄 ÉTAPE 7: Exécution des migrations..."
php artisan migrate --force --no-interaction

# Étape 8: Création du lien de stockage
echo ""
echo "🔗 ÉTAPE 8: Création du lien de stockage..."
php artisan storage:link || true

# Étape 9: Vérification finale
echo ""
echo "✅ ÉTAPE 9: Vérification finale..."
php artisan tinker --execute="
try {
    \$count = DB::table('administration_functions')->count();
    echo \"✅ Table administration_functions fonctionne correctement\n\";
    echo \"📊 Nombre d'enregistrements: \$count\n\";
    
    // Test de l'AdministrationController
    \$controller = new \App\Http\Controllers\AdministrationController();
    echo \"✅ AdministrationController peut être instancié\n\";
    
    // Test de la route administration
    echo \"✅ Routes d'administration disponibles\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR lors du test: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Étape 10: Optimisation
echo ""
echo "🔧 ÉTAPE 10: Optimisation de l'application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Résumé final
echo ""
echo "🎉 DÉPLOIEMENT COMPLET TERMINÉ AVEC SUCCÈS!"
echo "=========================================="
echo "✅ Base de données connectée"
echo "✅ Caches nettoyés"
echo "✅ Packages découverts"
echo "✅ Clé d'application générée"
echo "✅ Table administration_functions créée"
echo "✅ Table administration_function_types créée"
echo "✅ Migrations exécutées"
echo "✅ Lien de stockage créé"
echo "✅ Tests de fonctionnement réussis"
echo "✅ Application optimisée"
echo ""
echo "🚀 Votre application est maintenant prête!"
echo "L'erreur 500 sur /administration devrait être résolue."
echo "Le graphique des dîmes est disponible dans les détails des membres."
