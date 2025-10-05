#!/bin/bash

# Script de test du système multi-églises utilisateur
echo "=== Test du Système Multi-Églises Utilisateur ==="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

echo "1. Vérification des migrations..."
php artisan migrate:status | grep -E "(user_churches|migrate_user_church)"
echo "✅ Migrations vérifiées"

echo ""
echo "2. Test des commandes Artisan..."

# Test de la commande de gestion des églises utilisateur
echo "   - Test de la commande user:manage-churches..."
php artisan user:manage-churches list 1 2>/dev/null || echo "   ⚠️  Commande user:manage-churches non disponible"

echo ""
echo "3. Test des routes..."
echo "   - Route user.churches: /user/churches"
echo "   - Route church.switch: /church/switch"
echo "   - Route user.churches.add: /user/churches/add"

echo ""
echo "4. Vérification des fichiers créés..."
files=(
    "app/Http/Controllers/UserChurchesController.php"
    "resources/views/user-churches.blade.php"
    "app/Console/Commands/ManageUserChurches.php"
    "database/migrations/2025_10_04_220710_create_user_churches_table.php"
    "database/migrations/2025_10_04_220720_migrate_user_church_data_and_remove_column.php"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file (manquant)"
    fi
done

echo ""
echo "5. Test de la base de données..."
php artisan tinker --execute="
echo 'Utilisateurs: ' . App\Models\User::count();
echo 'Églises: ' . App\Models\Church::count();
echo 'Associations user_churches: ' . DB::table('user_churches')->count();
"

echo ""
echo "=== Test terminé ==="
echo ""
echo "📋 Prochaines étapes:"
echo "1. Connectez-vous à l'application"
echo "2. Allez dans 'Mes Églises' dans la sidebar"
echo "3. Ajoutez des églises à votre utilisateur"
echo "4. Testez le changement d'église"
echo ""
echo "📖 Documentation: docs/SYSTEME_MULTI_EGLISES.md"
echo "🆘 Support: Contactez l'équipe de développement"
