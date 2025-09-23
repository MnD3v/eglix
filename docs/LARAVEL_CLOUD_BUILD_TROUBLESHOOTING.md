# Guide de Dépannage Laravel Cloud - Problèmes de Build

## 🚨 Problème : "Preparing build environment interminable"

### Causes Possibles

1. **Conflits d'extensions PHP** - Extensions PostgreSQL et MySQL installées simultanément
2. **Commandes de build trop complexes** - Trop de commandes artisan dans la phase build
3. **Dépendances manquantes** - Extensions PHP non disponibles
4. **Configuration Nixpacks incorrecte** - Syntaxe ou paramètres invalides

## 🔧 Solutions

### Solution 1 : Configuration Simplifiée

Remplacez `nixpacks.toml` par `laravel-cloud-simple.toml` :

```toml
[phases.setup]
  nixPkgs = [
    "php82Extensions.intl",
    "php82Extensions.zip"
  ]

[phases.install]
  cmds = [
    "composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader"
  ]

[phases.build]
  cmds = [
    "php artisan key:generate --force || true",
    "php artisan config:clear || true",
    "php artisan route:clear || true",
    "php artisan view:clear || true",
    "php artisan storage:link || true"
  ]

[start]
  cmd = "php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public public/index.php"
```

### Solution 2 : Configuration Minimale

Utilisez `laravel-cloud-minimal.toml` pour un build ultra-rapide :

```toml
[phases.setup]
  nixPkgs = [
    "php82Extensions.intl"
  ]

[phases.install]
  cmds = [
    "composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader"
  ]

[phases.build]
  cmds = [
    "php artisan key:generate --force || true"
  ]

[start]
  cmd = "php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public public/index.php"
```

### Solution 3 : Script de Déploiement Manuel

Utilisez le script `laravel-cloud-simple-deploy.sh` :

```bash
chmod +x script/laravel-cloud-simple-deploy.sh
./script/laravel-cloud-simple-deploy.sh
```

## 📋 Étapes de Dépannage

### 1. Vérifier la Configuration

```bash
# Vérifier le fichier nixpacks.toml
cat nixpacks.toml

# Vérifier les variables d'environnement
echo $LARAVEL_CLOUD
echo $APP_ENV
```

### 2. Tester Localement

```bash
# Tester la configuration localement
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan key:generate --force
```

### 3. Vérifier les Extensions PHP

```bash
# Vérifier les extensions installées
php -m | grep -E "(intl|zip|pdo|mysql|pgsql)"
```

### 4. Nettoyer le Cache

```bash
# Nettoyer tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
```

## 🔍 Diagnostic des Problèmes

### Problème 1 : Extensions PHP Manquantes

**Erreur** :
```
PHP Fatal error: Call to undefined function intl_get_error_code()
```

**Solution** :
```toml
[phases.setup]
  nixPkgs = [
    "php82Extensions.intl"
  ]
```

### Problème 2 : Conflits d'Extensions

**Erreur** :
```
PHP Fatal error: Cannot redeclare function
```

**Solution** :
- Supprimez les extensions en conflit
- Gardez seulement les extensions essentielles

### Problème 3 : Commandes de Build Échouées

**Erreur** :
```
Command failed: php artisan migrate --force
```

**Solution** :
- Déplacez les commandes de migration vers le script de démarrage
- Utilisez `|| true` pour ignorer les erreurs non critiques

### Problème 4 : Timeout de Build

**Erreur** :
```
Build timeout after 10 minutes
```

**Solution** :
- Simplifiez la configuration
- Réduisez le nombre de commandes de build
- Utilisez la configuration minimale

## 🚀 Configuration Recommandée

### Pour MySQL
```toml
[phases.setup]
  nixPkgs = [
    "php82Extensions.intl",
    "php82Extensions.zip"
  ]

[phases.install]
  cmds = [
    "composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader"
  ]

[phases.build]
  cmds = [
    "php artisan key:generate --force || true"
  ]

[start]
  cmd = "php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public public/index.php"
```

### Pour PostgreSQL
```toml
[phases.setup]
  nixPkgs = [
    "php82Extensions.intl",
    "php82Extensions.zip"
  ]

[phases.install]
  cmds = [
    "composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader"
  ]

[phases.build]
  cmds = [
    "php artisan key:generate --force || true"
  ]

[start]
  cmd = "php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public public/index.php"
```

## 📝 Variables d'Environnement Essentielles

```bash
# Plateforme
LARAVEL_CLOUD=true
APP_ENV=production

# Base de données (choisir une seule)
DB_CONNECTION=mysql
# OU
DB_CONNECTION=pgsql

# Sessions
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

## ✅ Checklist de Dépannage

- [ ] Configuration Nixpacks simplifiée
- [ ] Extensions PHP minimales
- [ ] Commandes de build réduites
- [ ] Variables d'environnement correctes
- [ ] Cache nettoyé
- [ ] Test local réussi
- [ ] Script de déploiement testé

## 🆘 Support

Si le problème persiste :

1. **Vérifiez les logs** dans votre dashboard Laravel Cloud
2. **Utilisez la configuration minimale** `laravel-cloud-minimal.toml`
3. **Testez localement** avec les mêmes variables d'environnement
4. **Contactez le support Laravel Cloud** si nécessaire

## 🎯 Résumé

- **Simplifiez** la configuration Nixpacks
- **Réduisez** les extensions PHP
- **Minimisez** les commandes de build
- **Testez** localement avant le déploiement
- **Utilisez** les scripts de déploiement fournis
