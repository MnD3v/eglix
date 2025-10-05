#!/bin/bash

# Script de déploiement pour Laravel Cloud - Système Multi-Églises
# Ce script corrige les problèmes de migration PostgreSQL

echo "🚀 Déploiement du système multi-églises..."

# 1. Vérifier l'état des migrations
echo "📋 Vérification de l'état des migrations..."
php artisan migrate:status

# 2. Corriger le problème PostgreSQL
echo "🔧 Correction du problème PostgreSQL..."
php artisan fix:postgresql-migration

# 3. Exécuter les nouvelles migrations
echo "📦 Exécution des nouvelles migrations..."
php artisan migrate --force

# 4. Corriger les associations utilisateur-église
echo "👥 Correction des associations utilisateur-église..."
php artisan fix:user-church-associations

# 5. Tester le système
echo "🧪 Test du système multi-églises..."
php artisan test-multi-church-system

# 6. Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 7. Redémarrer les workers (si nécessaire)
echo "🔄 Redémarrage des workers..."
php artisan queue:restart

echo "✅ Déploiement terminé avec succès !"
