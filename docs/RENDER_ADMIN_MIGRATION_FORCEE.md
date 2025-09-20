# 🚀 Migration Forcée Super Admin - Render Production

## 📋 Description

Ce guide explique comment forcer la migration de la section super admin sur Render en production après que les données aient changé et nécessitent une re-migration.

## 🎯 Problème Résolu

Après la première migration, les données ont changé et il faut reforcer la migration en production pour :
- Créer les tables critiques manquantes
- Ajouter les colonnes `church_id`, `is_super_admin`, etc.
- Créer les données de base (rôles, permissions, super admin)
- S'assurer que la route `/admin-0202` fonctionne

## 📁 Fichiers Créés

### 1. Script Principal
- **Fichier :** `script/render-force-admin-migration.sh`
- **Usage :** Migration forcée complète sur Render
- **Permissions :** Exécutable (`chmod +x`)

### 2. Script de Déploiement
- **Fichier :** `script/render-deploy-with-admin-migration.sh`
- **Usage :** Déploiement complet avec migration forcée
- **Permissions :** Exécutable (`chmod +x`)

### 3. Commande Artisan
- **Fichier :** `app/Console/Commands/ForceRenderAdminMigration.php`
- **Usage :** `php artisan render:force-admin-migration`

### 4. Configuration Render
- **Fichier :** `render-admin-migration.env`
- **Usage :** Variables d'environnement pour Render

## 🚀 Utilisation sur Render

### Option 1 : Script de Migration Forcée
```bash
# Dans le terminal Render ou via SSH
chmod +x script/render-force-admin-migration.sh
./script/render-force-admin-migration.sh
```

### Option 2 : Script de Déploiement Complet
```bash
# Pour un déploiement complet avec migration
chmod +x script/render-deploy-with-admin-migration.sh
./script/render-deploy-with-admin-migration.sh
```

### Option 3 : Commande Artisan
```bash
# Avec confirmation
php artisan render:force-admin-migration

# Sans confirmation (force)
php artisan render:force-admin-migration --force
```

## 🔧 Fonctionnalités

### Tables Créées/Vérifiées
- `churches` - Églises avec champs d'abonnement
- `roles` - Rôles utilisateurs (Super Admin, Church Admin, etc.)
- `permissions` - Permissions système
- `subscriptions` - Abonnements des églises

### Colonnes Ajoutées
Ajoute les colonnes critiques aux tables existantes :
- `users.church_id` - Référence vers l'église
- `users.role_id` - Référence vers le rôle
- `users.is_super_admin` - Statut super admin
- `users.is_active` - Statut actif

### Données de Base Créées
- **Rôles :** Super Admin, Church Admin, Pastor, Member
- **Permissions :** manage_all_churches, manage_subscriptions, etc.
- **Super Admin :** admin@eglix.com (mot de passe: admin123!)

## 🔒 Sécurité

### Accès Super Admin
- **Route :** `/admin-0202`
- **Email :** admin@eglix.com
- **Mot de passe :** admin123!
- **⚠️ IMPORTANT :** Changez le mot de passe par défaut !

### Protection des Données
- Sauvegarde automatique avant migration
- Vérifications de sécurité
- Gestion des erreurs

## 📊 Vérification

### Après Migration
1. **Accès admin :** Vérifiez `/admin-0202`
2. **Super admin :** Connectez-vous avec admin@eglix.com
3. **Tables :** Vérifiez que toutes les tables existent
4. **Données :** Vérifiez les rôles et permissions

### Commandes de Vérification
```bash
# Vérifier le statut des migrations
php artisan migrate:status

# Vérifier les tables
php artisan tinker
>>> Schema::hasTable('churches')
>>> Schema::hasTable('roles')

# Vérifier les super admins
>>> User::where('is_super_admin', true)->count()
```

## 🚨 Dépannage

### Problèmes Courants

#### 1. Base de données non disponible
```bash
# Attendre et réessayer
sleep 30
./script/render-force-admin-migration.sh
```

#### 2. Permissions insuffisantes
```bash
# Rendre les scripts exécutables
chmod +x script/*.sh
```

#### 3. Migration échouée
```bash
# Exécuter en mode force
php artisan render:force-admin-migration --force
```

### Logs de Débogage
```bash
# Vérifier les logs Laravel
tail -f storage/logs/laravel.log

# Vérifier les logs Render
# Dans le dashboard Render > Logs
```

## 🔄 Processus de Migration

### Étapes Automatiques
1. **Vérification DB** - Attente de la connexion
2. **Sauvegarde** - Création d'une sauvegarde de sécurité
3. **Tables** - Création des tables critiques
4. **Colonnes** - Ajout des colonnes manquantes
5. **Données** - Création des données de base
6. **Migrations** - Exécution des migrations Laravel
7. **Vérification** - Contrôle de l'état final
8. **Nettoyage** - Suppression des fichiers temporaires

### Temps d'Exécution
- **Migration complète :** 2-5 minutes
- **Vérification :** 30 secondes
- **Nettoyage :** 10 secondes

## 📞 Support

### En Cas de Problème
1. Vérifiez les logs Render
2. Exécutez les commandes de vérification
3. Contactez l'équipe de développement

### Informations Utiles
- **Environnement :** Production Render
- **Base de données :** PostgreSQL (fournie par Render)
- **PHP :** Version 8.2+
- **Laravel :** Version 10+
