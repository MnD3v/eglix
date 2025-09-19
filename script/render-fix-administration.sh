#!/usr/bin/env bash

echo "🚀 DÉPLOIEMENT RENDER - CORRECTION administration_functions"
echo "=========================================================="

# Variables d'environnement pour Render
export APP_ENV=production
export APP_DEBUG=false
export FIX_ADMINISTRATION_FUNCTIONS=1

echo "🔧 Configuration des variables d'environnement..."
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "FIX_ADMINISTRATION_FUNCTIONS: $FIX_ADMINISTRATION_FUNCTIONS"

# Attendre que la DB soit disponible
echo "⏳ Attente de la base de données..."
for i in {1..30}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        echo "✅ Base de données connectée"
        break
    fi
    echo "Tentative $i/30..."
    sleep 2
done

# Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Vérifier l'état actuel
echo "🔍 Vérification de l'état actuel..."
php artisan tinker --execute="
try {
    \$exists = Schema::hasTable('administration_functions');
    if (\$exists) {
        echo \"✅ Table administration_functions existe\n\";
        \$count = DB::table('administration_functions')->count();
        echo \"📊 Nombre d'enregistrements: \$count\n\";
    } else {
        echo \"❌ Table administration_functions n'existe pas - CORRECTION NÉCESSAIRE\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification: \" . \$e->getMessage() . \"\n\";
}
"

# Créer la table administration_functions si elle n'existe pas
echo "🛠️ Correction de la table administration_functions..."
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

# Créer la table administration_function_types si nécessaire
echo "🛠️ Vérification de la table administration_function_types..."
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

# Exécuter les migrations pour s'assurer que tout est à jour
echo "🔄 Exécution des migrations..."
php artisan migrate --force --no-interaction

# Vérifier que tout fonctionne
echo "✅ Vérification finale..."
php artisan tinker --execute="
try {
    \$count = DB::table('administration_functions')->count();
    echo \"✅ Table administration_functions fonctionne correctement\n\";
    echo \"📊 Nombre d'enregistrements: \$count\n\";
    
    // Test de l'AdministrationController
    \$controller = new \App\Http\Controllers\AdministrationController();
    echo \"✅ AdministrationController peut être instancié\n\";
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

echo "🎉 DÉPLOIEMENT RENDER TERMINÉ AVEC SUCCÈS!"
echo "La table administration_functions est maintenant disponible."
echo "L'erreur 500 sur /administration devrait être résolue."
