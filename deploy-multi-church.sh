#!/bin/bash

# Script de déploiement du système multi-églises
echo "=== Déploiement du Système Multi-Églises Eglix ==="
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

echo "1. Sauvegarde de la base de données..."
# Créer une sauvegarde avant la migration
php artisan db:backup --destination=local --disk=local --path=backups/pre-multi-church-$(date +%Y%m%d_%H%M%S).sql
echo "✅ Sauvegarde créée"

echo ""
echo "2. Exécution des migrations..."
php artisan migrate --force
echo "✅ Migrations exécutées"

echo ""
echo "3. Migration des utilisateurs existants..."
php artisan users:migrate-to-multi-church
echo "✅ Utilisateurs migrés"

echo ""
echo "4. Vérification du système..."
php test-multi-church.php
echo "✅ Vérification terminée"

echo ""
echo "5. Nettoyage des fichiers temporaires..."
rm -f test-multi-church.php
echo "✅ Nettoyage terminé"

echo ""
echo "=== Déploiement terminé avec succès ==="
echo ""
echo "📋 Prochaines étapes:"
echo "1. Connectez-vous à l'application"
echo "2. Vérifiez que le sélecteur d'église apparaît dans la sidebar"
echo "3. Testez le changement d'église"
echo "4. Ajoutez des utilisateurs à plusieurs églises si nécessaire"
echo ""
echo "📖 Documentation: docs/SYSTEME_MULTI_EGLISES.md"
echo "🆘 Support: Contactez l'équipe de développement"
