# Scripts de Déploiement et Reset de Base de Données

## 🚀 Scripts Disponibles

### 1. `script/0-laravel-deploy.sh` - Déploiement Standard
Script de déploiement principal avec option de reset complet.

**Utilisation :**
```bash
# Déploiement normal (migrations incrémentales)
./script/0-laravel-deploy.sh

# Déploiement avec reset complet de la base de données
DB_RESET_ON_DEPLOY=1 ./script/0-laravel-deploy.sh
```

### 2. `script/reset-database.sh` - Reset Complet Local
Script pour reset complet de la base de données en local.

**Utilisation :**
```bash
# Reset complet (développement uniquement)
./script/reset-database.sh

# Reset forcé en production (attention !)
DB_RESET_ON_DEPLOY=1 ./script/reset-database.sh
```

### 3. `docker/reset-database.sh` - Reset Docker
Script optimisé pour les conteneurs Docker avec attente de la base de données.

**Utilisation :**
```bash
# Dans un conteneur Docker
./docker/reset-database.sh
```

## 🔧 Configuration sur Render

### Variables d'Environnement
Ajoutez ces variables dans votre service Render :

```bash
# Pour activer le reset complet à chaque déploiement
DB_RESET_ON_DEPLOY=1

# Configuration de base de données PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=[votre-host-postgres]
DB_PORT=5432
DB_DATABASE=[nom-de-votre-db]
DB_USERNAME=[votre-user]
DB_PASSWORD=[votre-password]
```

### Build Commands sur Render
Dans les Build Commands de votre service web :

```bash
# Option 1: Déploiement avec reset complet
DB_RESET_ON_DEPLOY=1 ./script/0-laravel-deploy.sh

# Option 2: Déploiement normal (recommandé pour la production)
./script/0-laravel-deploy.sh
```

## ⚠️ Avertissements

- **`DB_RESET_ON_DEPLOY=1`** supprime TOUTES les données de la base de données
- Utilisez uniquement en développement ou lors du premier déploiement
- En production, utilisez les migrations incrémentales (`php artisan migrate`)

## 🐛 Résolution de Problèmes

### Erreur "relation does not exist"
```bash
# Solution: Reset complet de la base de données
DB_RESET_ON_DEPLOY=1 ./script/0-laravel-deploy.sh
```

### Erreur de permissions
```bash
# Vérifier les permissions
chmod +x script/*.sh
chmod +x docker/*.sh
```

### Base de données non accessible
Les scripts Docker incluent une attente automatique de la base de données (jusqu'à 60 secondes).
