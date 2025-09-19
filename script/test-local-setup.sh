#!/usr/bin/env bash

echo "🧪 TEST LOCAL - Vérification administration_functions"
echo "===================================================="

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

echo "✅ Répertoire Laravel détecté"

# Vérifier la connexion à la base de données
echo ""
echo "🔍 Vérification de la connexion à la base de données..."
if php artisan migrate:status >/dev/null 2>&1; then
    echo "✅ Connexion à la base de données OK"
else
    echo "❌ Impossible de se connecter à la base de données"
    exit 1
fi

# Vérifier l'existence de la table administration_functions
echo ""
echo "🔍 Vérification de la table administration_functions..."
php artisan tinker --execute="
try {
    \$exists = Schema::hasTable('administration_functions');
    if (\$exists) {
        echo \"✅ Table administration_functions existe\n\";
        \$count = DB::table('administration_functions')->count();
        echo \"📊 Nombre d'enregistrements: \$count\n\";
    } else {
        echo \"❌ Table administration_functions n'existe pas\n\";
        echo \"💡 Exécutez: php artisan migrate --force\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification: \" . \$e->getMessage() . \"\n\";
}
"

# Vérifier l'existence de la table administration_function_types
echo ""
echo "🔍 Vérification de la table administration_function_types..."
php artisan tinker --execute="
try {
    \$exists = Schema::hasTable('administration_function_types');
    if (\$exists) {
        echo \"✅ Table administration_function_types existe\n\";
        \$count = DB::table('administration_function_types')->count();
        echo \"📊 Nombre d'enregistrements: \$count\n\";
    } else {
        echo \"❌ Table administration_function_types n'existe pas\n\";
        echo \"💡 Exécutez: php artisan migrate --force\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification: \" . \$e->getMessage() . \"\n\";
}
"

# Tester l'AdministrationController
echo ""
echo "🔍 Test de l'AdministrationController..."
php artisan tinker --execute="
try {
    \$controller = new \App\Http\Controllers\AdministrationController();
    echo \"✅ AdministrationController peut être instancié\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR AdministrationController: \" . \$e->getMessage() . \"\n\";
}
"

# Vérifier les routes
echo ""
echo "🔍 Vérification des routes d'administration..."
php artisan route:list --name=administration || echo "⚠️ Aucune route administration trouvée"

# Vérifier les migrations
echo ""
echo "🔍 Statut des migrations..."
php artisan migrate:status | grep administration || echo "⚠️ Aucune migration administration trouvée"

echo ""
echo "🎯 RÉSUMÉ DU TEST:"
echo "=================="
echo "Si toutes les vérifications sont ✅, votre environnement local est prêt."
echo "Vous pouvez maintenant déployer en production avec les scripts fournis."
echo ""
echo "📋 Prochaines étapes pour la production:"
echo "1. Ajoutez FIX_ADMINISTRATION_FUNCTIONS=1 dans Render"
echo "2. Redéployez votre application"
echo "3. Vérifiez que l'erreur 500 est résolue"
