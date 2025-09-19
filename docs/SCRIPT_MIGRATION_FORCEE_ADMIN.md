# 🔧 Script de Migration Forcée - Administration

## 📋 Description

Ce script force la création et la migration de tous les éléments nécessaires pour la partie administration de l'application Eglix.

## 🎯 Objectif

Résoudre les problèmes de tables manquantes ou de colonnes `church_id` absentes qui peuvent causer des erreurs dans la partie administration.

## 📁 Fichiers Créés

### 1. Script Shell Local
- **Fichier :** `script/force-admin-migration.sh`
- **Usage :** Exécution locale du script
- **Permissions :** Exécutable (`chmod +x`)

### 2. Script Docker
- **Fichier :** `docker/force-admin-migration.sh`
- **Usage :** Exécution dans un environnement Docker
- **Permissions :** Exécutable (`chmod +x`)

### 3. Commande Artisan
- **Fichier :** `app/Console/Commands/ForceAdminMigration.php`
- **Usage :** `php artisan admin:force-migration`

## 🚀 Utilisation

### Option 1 : Script Shell Local
```bash
# Rendre le script exécutable
chmod +x script/force-admin-migration.sh

# Exécuter le script
./script/force-admin-migration.sh
```

### Option 2 : Script Docker
```bash
# Dans un conteneur Docker
chmod +x docker/force-admin-migration.sh
./docker/force-admin-migration.sh
```

### Option 3 : Commande Artisan
```bash
# Avec confirmation
php artisan admin:force-migration

# Sans confirmation (force)
php artisan admin:force-migration --force
```

## 🔧 Fonctionnalités

### Tables Créées
- `administration_functions` - Fonctions d'administration des membres
- `administration_function_types` - Types de fonctions (Pasteur, Diacre, etc.)
- `roles` - Rôles utilisateurs
- `permissions` - Permissions système
- `churches` - Églises

### Colonnes Ajoutées
Ajoute `church_id` aux tables suivantes :
- `administration_functions`
- `administration_function_types`
- `users`
- `members`
- `tithes`
- `offerings`
- `donations`
- `expenses`
- `projects`
- `services`
- `church_events`
- `service_roles`
- `service_assignments`
- `journal_entries`
- `journal_images`

### Données Insérées

#### Types de Fonctions d'Administration
- Pasteur Principal
- Pasteur Assistant
- Ancien
- Diacre
- Secrétaire
- Trésorier

#### Rôles Utilisateurs
- Administrateur
- Pasteur
- Secrétaire
- Membre

#### Permissions Système
- `members.view` - Voir les membres
- `members.create` - Créer des membres
- `members.edit` - Modifier les membres
- `members.delete` - Supprimer les membres
- `tithes.view` - Voir les dîmes
- `tithes.manage` - Gérer les dîmes
- `offerings.view` - Voir les offrandes
- `offerings.manage` - Gérer les offrandes
- `reports.view` - Voir les rapports
- `administration.view` - Gérer l'administration
- `users.view` - Gérer les utilisateurs

## 🛡️ Sécurité

### Vérifications Effectuées
- ✅ Connexion à la base de données
- ✅ Existence des tables avant création
- ✅ Existence des colonnes avant ajout
- ✅ Existence des données avant insertion
- ✅ Gestion des erreurs avec try/catch

### Précautions
- Le script vérifie l'existence avant de créer
- Les données sont insérées seulement si elles n'existent pas
- Les erreurs sont gérées gracieusement
- Le script peut être exécuté plusieurs fois sans problème

## 📊 Résultat Attendu

### Messages de Succès
```
🔧 SCRIPT DE MIGRATION FORCÉE - ADMINISTRATION
==============================================

ℹ️  Début de la migration forcée des éléments d'administration...
✅ Base de données connectée
✅ Table administration_functions existe déjà
✅ Table administration_function_types créée avec succès
✅ Colonne church_id ajoutée à members
✅ Type de fonction ajouté: Pasteur Principal
✅ Rôle ajouté: Administrateur
✅ Permission ajoutée: Voir les membres
✅ Migrations Laravel exécutées avec succès

🎉 MIGRATION FORCÉE TERMINÉE AVEC SUCCÈS!
```

### Vérification Finale
```
📊 RÉSUMÉ FINAL:
================
✅ administration_functions: 0 enregistrements
✅ administration_function_types: 6 enregistrements
✅ roles: 4 enregistrements
✅ permissions: 11 enregistrements
✅ churches: 0 enregistrements

🏢 VÉRIFICATION DES COLONNES CHURCH_ID:
=====================================
✅ users: church_id présent
✅ members: church_id présent
✅ administration_functions: church_id présent

🎉 VÉRIFICATION TERMINÉE!
```

## 🚨 Résolution de Problèmes

### Erreur de Connexion DB
```
❌ Impossible de se connecter à la base de données
```
**Solution :** Vérifiez la configuration de la base de données dans `.env`

### Erreur de Permissions
```
❌ Permission denied: ./script/force-admin-migration.sh
```
**Solution :** `chmod +x script/force-admin-migration.sh`

### Tables Existantes
```
ℹ️  Table administration_functions existe déjà
```
**Normal :** Le script détecte les tables existantes et continue

### Colonnes Existantes
```
✅ Colonne church_id présente dans members
```
**Normal :** Le script vérifie l'existence avant d'ajouter

## 📝 Logs et Debug

### Niveau de Verbosité
- **Info :** Messages informatifs (bleu)
- **Success :** Succès (vert)
- **Warning :** Avertissements (jaune)
- **Error :** Erreurs (rouge)

### Fichiers de Log
Les erreurs sont également loggées dans :
- `storage/logs/laravel.log`

## 🔄 Maintenance

### Exécution Répétée
Le script peut être exécuté plusieurs fois sans problème :
- Vérifie l'existence avant création
- N'insère que les données manquantes
- Idempotent et sûr

### Mise à Jour
Pour ajouter de nouveaux éléments :
1. Modifiez le script correspondant
2. Ajoutez les nouvelles tables/colonnes/données
3. Testez en local avant déploiement

## 📞 Support

En cas de problème :
1. Vérifiez les logs dans `storage/logs/laravel.log`
2. Exécutez `php artisan migrate:status` pour voir l'état
3. Vérifiez la configuration de la base de données
4. Contactez l'équipe de développement

---

**🎉 Le script de migration forcée est maintenant prêt à résoudre tous les problèmes d'administration !**
