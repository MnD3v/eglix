# Scripts de Migration Forcée

## Problème
En production, certaines migrations ne sont pas exécutées correctement, ce qui peut causer des erreurs comme :
- `SQLSTATE[42703]: Undefined column: column "church_id" does not exist`
- `SQLSTATE[42P01]: Undefined table: relation "churches" does not exist`

## Solutions disponibles

### 1. Script de Migration Sécurisée (Recommandé)
**Fichier :** `script/safe-migration-fix.sh`

Ce script ajoute les colonnes manquantes sans supprimer les données existantes.

```bash
# Exécution locale
./script/safe-migration-fix.sh

# Exécution sur le serveur
ssh user@server "cd /path/to/app && ./script/safe-migration-fix.sh"
```

**Ce que fait le script :**
- ✅ Crée les tables manquantes (`churches`, `roles`)
- ✅ Ajoute les colonnes manquantes (`church_id`, `role_id`, etc.)
- ✅ Préserve toutes les données existantes
- ✅ Exécute les migrations normales
- ✅ Optimise l'application

### 2. Script de Migration Complète (Dangereux)
**Fichier :** `script/force-all-migrations.sh`

⚠️ **ATTENTION : Ce script supprime TOUTES les données !**

```bash
# Exécution locale (avec confirmation)
./script/force-all-migrations.sh

# Le script demande confirmation avant de continuer
```

**Ce que fait le script :**
- 🗑️ Supprime toutes les tables
- 🔄 Recrée toutes les migrations
- 🌱 Exécute les seeders
- ⚡ Optimise l'application

### 3. Script Docker (Pour conteneurs)
**Fichier :** `docker/force-migrations.sh`

Ce script est intégré dans le conteneur Docker et peut être activé via une variable d'environnement.

```bash
# Dans le conteneur Docker
docker exec -it container_name /usr/local/bin/force-migrations.sh

# Ou avec la variable d'environnement
docker run -e FORCE_MIGRATIONS=1 your-image
```

## Utilisation en Production

### Option 1 : Migration Sécurisée (Recommandée)
```bash
# 1. Se connecter au serveur
ssh user@your-server

# 2. Aller dans le dossier de l'application
cd /path/to/your/app

# 3. Exécuter le script sécurisé
./script/safe-migration-fix.sh
```

### Option 2 : Avec Docker
```bash
# 1. Redémarrer le conteneur avec la variable d'environnement
docker run -e FORCE_MIGRATIONS=1 your-image

# 2. Ou exécuter le script directement dans le conteneur
docker exec -it your-container /usr/local/bin/force-migrations.sh
```

### Option 3 : Migration Complète (Si nécessaire)
```bash
# 1. Faire une sauvegarde de la base de données
pg_dump your_database > backup.sql

# 2. Exécuter le script de migration complète
./script/force-all-migrations.sh

# 3. Restaurer les données si nécessaire
psql your_database < backup.sql
```

## Tables et Colonnes Ajoutées

### Tables créées :
- `churches` - Informations sur l'église
- `roles` - Rôles et permissions

### Colonnes ajoutées :
- `users.church_id` - Référence vers l'église
- `users.role_id` - Référence vers le rôle
- `users.is_church_admin` - Statut administrateur
- `users.is_active` - Statut actif
- `members.church_id` - Référence vers l'église
- `tithes.church_id` - Référence vers l'église
- `offerings.church_id` - Référence vers l'église
- `donations.church_id` - Référence vers l'église
- `expenses.church_id` - Référence vers l'église
- `projects.church_id` - Référence vers l'église
- `services.church_id` - Référence vers l'église
- `events.church_id` - Référence vers l'église
- `journal_entries.church_id` - Référence vers l'église

## Vérification

Après exécution, vérifiez que :
1. Toutes les tables existent
2. Toutes les colonnes sont présentes
3. L'application fonctionne correctement
4. Les utilisateurs peuvent se connecter

```bash
# Vérifier le statut des migrations
php artisan migrate:status

# Vérifier la structure de la base de données
php artisan tinker
>>> Schema::hasTable('churches')
>>> Schema::hasColumn('users', 'church_id')
```
