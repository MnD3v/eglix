#!/usr/bin/env bash

echo "🚀 DÉPLOIEMENT RENDER - FORCE MIGRATIONS"
echo "========================================"

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

# FORCER les migrations
echo "🔥 EXÉCUTION FORCÉE DES MIGRATIONS"
php artisan migrate --force --no-interaction

# Vérifier que ça a marché
echo "🔍 Vérification..."
php artisan tinker --execute="
try {
    \$count = \App\Models\Church::count();
    echo \"✅ Table churches: \$count églises\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

echo "✅ DÉPLOIEMENT TERMINÉ"
