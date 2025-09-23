# Guide de Déploiement Laravel Cloud

## 🚀 Déploiement sur Laravel Cloud

Ce guide vous aide à déployer votre application Laravel sur Laravel Cloud en résolvant les problèmes de migration et de sessions.

## 📋 Prérequis

1. **Compte Laravel Cloud** - Créez un compte sur [Laravel Cloud](https://laravel.cloud)
2. **Base de données PostgreSQL** - Configurez une base de données PostgreSQL
3. **Variables d'environnement** - Configurez toutes les variables nécessaires
4. **Pas de Docker** - Laravel Cloud utilise Nixpacks automatiquement

## 🔧 Configuration

### 1. Variables d'Environnement

Ajoutez ces variables dans votre dashboard Laravel Cloud (pas besoin de Docker) :

```bash
# Plateforme
LARAVEL_CLOUD=true
APP_ENV=production

# Base de données
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
DB_SSLMODE=require
DB_CHARSET=utf8

# Sessions
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Cache
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# Sécurité
APP_KEY=your-app-key
APP_DEBUG=false
APP_URL=https://your-app-url.laravel.cloud
```

### 2. Configuration Firebase (si utilisé)

```bash
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_PRIVATE_KEY_ID=your-private-key-id
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\nyour-private-key\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=your-client-email
FIREBASE_CLIENT_ID=your-client-id
```

## 🚀 Déploiement

### Méthode 1 : Déploiement Automatique (Recommandé)

Laravel Cloud utilise `nixpacks.toml` pour configurer automatiquement le déploiement :

1. **Configuration automatique** :
   - Les extensions PHP nécessaires sont installées
   - Les dépendances Composer sont installées
   - Les corrections de déploiement sont exécutées
   - Les migrations sont lancées
   - L'application est optimisée

2. **Variables d'environnement** :
   ```bash
   LARAVEL_CLOUD=true
   APP_ENV=production
   SESSION_DRIVER=database
   ```

### Méthode 2 : Script Manuel (Optionnel)

1. **Téléchargez le script de déploiement** :
   ```bash
   # Le script est déjà dans votre projet
   script/laravel-cloud-deploy.sh
   ```

2. **Exécutez le script** :
   ```bash
   chmod +x script/laravel-cloud-deploy.sh
   ./script/laravel-cloud-deploy.sh
   ```

### Méthode 3 : Commandes Manuelles

1. **Nettoyage du cache** :
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Correction des problèmes** :
   ```bash
   php artisan laravel-cloud:fix-deployment
   php artisan fix:sessions-conflict
   ```

3. **Exécution des migrations** :
   ```bash
   php artisan migrate --force
   ```

4. **Optimisation** :
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## 🔍 Résolution des Problèmes

### Problème 1 : Table Sessions Dupliquée

**Erreur** :
```
SQLSTATE[42P07]: Duplicate table: 7 ERROR: relation "sessions" already exists
```

**Solution** :
- La migration `0001_01_01_000000_create_users_table` vérifie maintenant l'existence de la table sessions
- La commande `fix:sessions-conflict` résout automatiquement ce problème
- L'auto-correction dans `AppServiceProvider` ne crée plus la table sur Laravel Cloud

### Problème 2 : Colonnes Subscription Manquantes

**Erreur** :
```
SQLSTATE[42703]: Undefined column: 7 ERROR: column "subscription_status" does not exist
```

**Solution** :
- L'auto-correction dans `AppServiceProvider` ajoute automatiquement les colonnes manquantes
- La commande `laravel-cloud:fix-deployment` vérifie et corrige ces colonnes

### Problème 3 : Tables Manquantes

**Erreur** :
```
SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "churches" does not exist
```

**Solution** :
- Exécutez les migrations : `php artisan migrate --force`
- Vérifiez que toutes les migrations sont présentes dans `database/migrations/`

### Problème 4 : Connexion PostgreSQL SSL

**Erreur** :
```
SQLSTATE[08006] [7] connection to server failed: ERROR: connection is insecure (try using `sslmode=require`)
```

**Solution** :
- Ajoutez `DB_SSLMODE=require` dans vos variables d'environnement
- Vérifiez que les extensions PostgreSQL sont installées
- Testez la connexion avec : `php artisan test:postgresql-connection`

## 📊 Vérification du Déploiement

### 1. Vérification des Tables

```bash
php artisan tinker --execute="
\$tables = ['users', 'churches', 'sessions', 'migrations'];
foreach (\$tables as \$table) {
    try {
        DB::select('SELECT 1 FROM ' . \$table . ' LIMIT 1');
        echo '✅ Table ' . \$table . ' existe';
    } catch (Exception \$e) {
        echo '❌ Table ' . \$table . ' manquante: ' . \$e->getMessage();
    }
}
"
```

### 2. Vérification des Sessions

```bash
php artisan tinker --execute="
if (Schema::hasTable('sessions')) {
    echo '✅ Table sessions disponible';
    \$count = DB::table('sessions')->count();
    echo '📊 Nombre de sessions: ' . \$count;
} else {
    echo '❌ Table sessions manquante';
}
"
```

### 3. Vérification des Colonnes Subscription

```bash
php artisan tinker --execute="
if (Schema::hasTable('churches')) {
    \$columns = DB::select(\"
        SELECT column_name 
        FROM information_schema.columns 
        WHERE table_name = 'churches' 
        AND column_name LIKE 'subscription_%'
    \");
    echo '📋 Colonnes subscription: ' . implode(', ', array_column(\$columns, 'column_name'));
} else {
    echo '❌ Table churches manquante';
}
"
```

## 🔧 Commandes Utiles

### Commandes de Diagnostic

```bash
# Statut des migrations
php artisan migrate:status

# Vérification de la base de données
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connexion OK';"

# Vérification des tables
php artisan tinker --execute="Schema::getAllTables();"

# Vérification des colonnes
php artisan tinker --execute="Schema::getColumnListing('churches');"
```

### Commandes de Maintenance

```bash
# Nettoyage complet
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Réparation
php artisan laravel-cloud:fix-deployment
```

## 📝 Logs et Monitoring

### Vérification des Logs

```bash
# Logs de l'application
tail -f storage/logs/laravel.log

# Logs de déploiement
# Vérifiez les logs dans votre dashboard Laravel Cloud
```

### Monitoring

- **Dashboard Laravel Cloud** - Surveillez les performances et les erreurs
- **Logs d'application** - Vérifiez `storage/logs/laravel.log`
- **Métriques de base de données** - Surveillez les requêtes et les performances

## 🆘 Support

### En cas de problème :

1. **Vérifiez les logs** dans votre dashboard Laravel Cloud
2. **Exécutez la commande de diagnostic** : `php artisan laravel-cloud:fix-deployment`
3. **Vérifiez les variables d'environnement** dans votre dashboard
4. **Contactez le support Laravel Cloud** si nécessaire

### Commandes de Dépannage

```bash
# Diagnostic complet
php artisan laravel-cloud:fix-deployment

# Résolution du conflit de table sessions
php artisan fix:sessions-conflict

# Test de connexion PostgreSQL
php artisan test:postgresql-connection

# Vérification de la configuration
php artisan config:show

# Test de connexion
php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';"

# Vérification des migrations
php artisan migrate:status
```

## ✅ Checklist de Déploiement

- [ ] Variables d'environnement configurées
- [ ] Base de données PostgreSQL accessible
- [ ] Script de déploiement exécuté
- [ ] Conflit de table sessions résolu
- [ ] Migrations exécutées avec succès
- [ ] Tables critiques vérifiées
- [ ] Sessions configurées
- [ ] Cache optimisé
- [ ] Application accessible
- [ ] Logs vérifiés
- [ ] Tests fonctionnels passés

## 🎉 Félicitations !

Votre application Laravel est maintenant déployée sur Laravel Cloud avec toutes les corrections nécessaires !

## 📝 Notes Importantes

- **Pas de Docker** : Laravel Cloud utilise Nixpacks automatiquement
- **Configuration automatique** : Le fichier `nixpacks.toml` gère tout
- **Extensions PHP** : Installées automatiquement (intl, zip, pdo_pgsql)
- **Déploiement simplifié** : Plus besoin de scripts Docker complexes
