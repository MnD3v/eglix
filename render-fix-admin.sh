#!/usr/bin/env bash

echo "🚨 CORRECTION PRODUCTION - TABLES ADMINISTRATION MANQUANTES"
echo "============================================================"

# Exécuter la commande de correction
php artisan admin:fix-tables --force

# Vérifier que tout fonctionne
php artisan migrate:status | grep administration

echo "✅ Correction terminée !"
echo "Vérifiez maintenant : https://eglix.lafia.tech/administration"
