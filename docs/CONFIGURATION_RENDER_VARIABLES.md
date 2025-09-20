# 🔧 Configuration Render - Variables d'Environnement

## 📋 Variables d'Environnement à Ajouter dans Render

Pour que la migration forcée se fasse automatiquement, vous devez ajouter ces variables dans le dashboard Render :

### 🚀 **Variables Principales (OBLIGATOIRES)**

```bash
# Activation de la migration forcée
FORCE_ADMIN_MIGRATION=true
RENDER_ADMIN_MIGRATION=true

# Configuration de l'application
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### 🔐 **Configuration Super Admin**

```bash
# Informations du super admin par défaut
SUPER_ADMIN_EMAIL=admin@eglix.com
SUPER_ADMIN_PASSWORD=admin123!
```

### 🛡️ **Configuration de Sécurité**

```bash
# Cookies sécurisés
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### ⚡ **Configuration du Cache**

```bash
# Drivers de cache
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 📊 **Configuration des Logs**

```bash
# Logs Render
LOG_CHANNEL=stderr
LOG_DEPRECATIONS_CHANNEL=null
```

## 🎯 **Comment Ajouter les Variables dans Render**

### **Étape 1 : Accéder au Dashboard Render**
1. Connectez-vous à [render.com](https://render.com)
2. Sélectionnez votre service
3. Allez dans l'onglet **"Environment"**

### **Étape 2 : Ajouter les Variables**
1. Cliquez sur **"Add Environment Variable"**
2. Ajoutez chaque variable une par une :

| Variable | Valeur |
|----------|--------|
| `FORCE_ADMIN_MIGRATION` | `true` |
| `RENDER_ADMIN_MIGRATION` | `true` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `LOG_LEVEL` | `error` |
| `SUPER_ADMIN_EMAIL` | `admin@eglix.com` |
| `SUPER_ADMIN_PASSWORD` | `admin123!` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SESSION_HTTP_ONLY` | `true` |
| `SESSION_SAME_SITE` | `strict` |
| `CACHE_DRIVER` | `file` |
| `SESSION_DRIVER` | `file` |
| `QUEUE_CONNECTION` | `sync` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_DEPRECATIONS_CHANNEL` | `null` |

### **Étape 3 : Configurer les Commandes de Déploiement**
Dans l'onglet **"Settings"** :

| Champ | Valeur |
|-------|--------|
| **Build Command** | `./script/render-deploy-with-admin-migration.sh` |
| **Start Command** | `php artisan serve --host=0.0.0.0 --port=$PORT` |

## 🔄 **Processus de Déploiement**

### **Avec Migration Forcée Activée**
1. **Déploiement** → Le script détecte `FORCE_ADMIN_MIGRATION=true`
2. **Migration** → Exécution automatique de la migration forcée
3. **Vérification** → Contrôle de l'état final
4. **Démarrage** → Application prête avec super admin

### **Sans Migration Forcée**
1. **Déploiement** → Migration Laravel standard
2. **Démarrage** → Application normale

## ✅ **Vérification Post-Déploiement**

### **1. Vérifier les Logs Render**
```bash
# Dans les logs Render, vous devriez voir :
✅ Migration forcée activée via variables d'environnement
🔐 Début de la migration forcée super admin...
✅ Tables critiques créées
✅ Super admin créé: admin@eglix.com
```

### **2. Tester l'Accès Super Admin**
1. Allez sur `https://votre-app.onrender.com/admin-0202`
2. Connectez-vous avec :
   - **Email :** admin@eglix.com
   - **Mot de passe :** admin123!

### **3. Vérifier les Tables**
```bash
# Dans les logs ou via tinker
✅ Table churches: X enregistrements
✅ Table roles: X enregistrements
✅ Table permissions: X enregistrements
✅ Table subscriptions: X enregistrements
```

## 🚨 **Dépannage**

### **Problème : Migration ne se lance pas**
**Solution :** Vérifiez que `FORCE_ADMIN_MIGRATION=true` est bien défini

### **Problème : Erreur de permissions**
**Solution :** Vérifiez que le Build Command pointe vers le bon script

### **Problème : Base de données non disponible**
**Solution :** Le script attend automatiquement jusqu'à 5 minutes

## 🔒 **Sécurité**

### **⚠️ IMPORTANT :**
- Changez le mot de passe par défaut après le premier déploiement
- Les variables d'environnement sont sécurisées dans Render
- Le super admin par défaut est créé uniquement si aucun n'existe

### **Recommandations :**
1. **Premier déploiement :** Utilisez les variables par défaut
2. **Après déploiement :** Changez `SUPER_ADMIN_PASSWORD`
3. **En production :** Désactivez `FORCE_ADMIN_MIGRATION` après migration

## 📞 **Support**

Si vous rencontrez des problèmes :
1. Vérifiez les logs Render
2. Contrôlez les variables d'environnement
3. Testez l'accès `/admin-0202`
4. Contactez l'équipe de développement
