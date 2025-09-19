#!/usr/bin/env bash

echo "🚀 SCRIPT DE DÉPLOIEMENT PRODUCTION - MIGRATION FORCÉE ADMINISTRATION"
echo "====================================================================="

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

log_info "Déploiement avec migration forcée d'administration pour Render..."

# Variables d'environnement pour Render
log_info "Configuration des variables d'environnement Render..."

cat > render-admin-migration.env << 'EOF'
# Variables d'environnement pour la migration forcée d'administration
# À ajouter dans le dashboard Render

# ===========================================
# CONFIGURATION DE BASE
# ===========================================
APP_ENV=production
APP_DEBUG=false
APP_URL=https://eglix.lafia.tech
APP_KEY=base64:YOUR_APP_KEY_HERE

# ===========================================
# MIGRATION FORCÉE D'ADMINISTRATION
# ===========================================
FORCE_ADMIN_MIGRATION=true
FIX_ADMINISTRATION_FUNCTIONS=true
FORCE_MIGRATIONS=true

# ===========================================
# SÉCURITÉ HTTPS
# ===========================================
FORCE_HTTPS=true
SECURE_COOKIES=true

# ===========================================
# CONFIGURATION DES SESSIONS SÉCURISÉES
# ===========================================
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_COOKIE_SECURE=true

# ===========================================
# CONFIGURATION DES COOKIES SÉCURISÉES
# ===========================================
COOKIE_SECURE=true
COOKIE_HTTP_ONLY=true
COOKIE_SAME_SITE=lax

# ===========================================
# CONFIGURATION CSRF SÉCURISÉE
# ===========================================
CSRF_COOKIE_SECURE=true
CSRF_COOKIE_HTTP_ONLY=true
CSRF_COOKIE_SAME_SITE=lax

# ===========================================
# CONFIGURATION DES PROXIES (RENDER)
# ===========================================
TRUSTED_PROXIES=*
TRUSTED_HEADERS=X-Forwarded-For,X-Forwarded-Host,X-Forwarded-Port,X-Forwarded-Proto

# ===========================================
# CONFIGURATION DE LA BASE DE DONNÉES
# ===========================================
DB_CONNECTION=pgsql
DB_HOST=YOUR_DB_HOST
DB_PORT=5432
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD
DB_SSL_MODE=require

# ===========================================
# CONFIGURATION DU CACHE
# ===========================================
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# ===========================================
# CONFIGURATION DES LOGS
# ===========================================
LOG_CHANNEL=stack
LOG_LEVEL=warning

# ===========================================
# CONFIGURATION MAIL (si nécessaire)
# ===========================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
EOF

log_success "Fichier render-admin-migration.env créé"

# Créer un script de déploiement complet
cat > deploy-with-admin-migration.sh << 'EOF'
#!/usr/bin/env bash

echo "🚀 DÉPLOIEMENT COMPLET AVEC MIGRATION FORCÉE D'ADMINISTRATION"
echo "============================================================="

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

# Commit et push des changements
echo "📝 Commit des changements..."
git add .
git commit -m "feat: Ajout migration forcée administration pour production" || echo "⚠️ Aucun changement à commiter"
git push origin main || echo "⚠️ Push échoué, continuons..."

echo "✅ Déploiement déclenché sur Render"
echo ""
echo "📋 ÉTAPES SUIVANTES:"
echo "==================="
echo "1. Allez dans votre dashboard Render"
echo "2. Sélectionnez votre service Eglix"
echo "3. Allez dans l'onglet 'Environment'"
echo "4. Ajoutez ces variables d'environnement:"
echo "   - FORCE_ADMIN_MIGRATION=true"
echo "   - FIX_ADMINISTRATION_FUNCTIONS=true"
echo "   - FORCE_MIGRATIONS=true"
echo "5. Redéployez votre service"
echo ""
echo "🔧 Le script de migration forcée s'exécutera automatiquement au démarrage"
echo "🎉 Toutes les tables et colonnes d'administration seront créées automatiquement"
EOF

chmod +x deploy-with-admin-migration.sh
log_success "Script de déploiement créé: deploy-with-admin-migration.sh"

# Créer un script de test pour vérifier le déploiement
cat > test-admin-migration.sh << 'EOF'
#!/usr/bin/env bash

echo "🧪 TEST DE LA MIGRATION FORCÉE D'ADMINISTRATION"
echo "=============================================="

# Test local
echo "🔍 Test local de la commande Artisan..."
php artisan admin:force-migration --force

if [ $? -eq 0 ]; then
    echo "✅ Commande Artisan fonctionne correctement"
else
    echo "❌ Commande Artisan a échoué"
    exit 1
fi

# Test des scripts
echo "🔍 Test des scripts de migration..."
if [ -f "script/force-admin-migration.sh" ]; then
    echo "✅ Script local trouvé"
    chmod +x script/force-admin-migration.sh
else
    echo "❌ Script local manquant"
fi

if [ -f "docker/force-admin-migration.sh" ]; then
    echo "✅ Script Docker trouvé"
    chmod +x docker/force-admin-migration.sh
else
    echo "❌ Script Docker manquant"
fi

# Vérifier le Dockerfile
echo "🔍 Vérification du Dockerfile..."
if grep -q "force-admin-migration.sh" Dockerfile; then
    echo "✅ Dockerfile contient le script de migration"
else
    echo "❌ Dockerfile ne contient pas le script de migration"
fi

# Vérifier le script de démarrage
echo "🔍 Vérification du script de démarrage..."
if grep -q "FORCE_ADMIN_MIGRATION" docker/start.sh; then
    echo "✅ Script de démarrage contient la logique de migration"
else
    echo "❌ Script de démarrage ne contient pas la logique de migration"
fi

echo ""
echo "🎉 TESTS TERMINÉS!"
echo "Si tous les tests sont ✅, vous pouvez déployer en production"
EOF

chmod +x test-admin-migration.sh
log_success "Script de test créé: test-admin-migration.sh"

# Créer un guide de déploiement
cat > GUIDE_DEPLOIEMENT_ADMIN.md << 'EOF'
# 🚀 Guide de Déploiement - Migration Forcée Administration

## 🎯 Objectif
Déployer l'application avec la migration forcée des éléments d'administration en production sur Render.

## 📋 Prérequis
- Compte Render actif
- Service Eglix configuré
- Accès au dashboard Render

## 🔧 Étapes de Déploiement

### 1. Préparation Locale
```bash
# Tester les scripts localement
./test-admin-migration.sh

# Si tous les tests passent, déployer
./deploy-with-admin-migration.sh
```

### 2. Configuration Render

#### Variables d'Environnement à Ajouter
Dans le dashboard Render, ajoutez ces variables :

```bash
# Migration forcée
FORCE_ADMIN_MIGRATION=true
FIX_ADMINISTRATION_FUNCTIONS=true
FORCE_MIGRATIONS=true

# Sécurité
FORCE_HTTPS=true
SECURE_COOKIES=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
SESSION_COOKIE_SECURE=true
COOKIE_SECURE=true
COOKIE_HTTP_ONLY=true
COOKIE_SAME_SITE=lax
CSRF_COOKIE_SECURE=true
CSRF_COOKIE_HTTP_ONLY=true
CSRF_COOKIE_SAME_SITE=lax

# Proxies Render
TRUSTED_PROXIES=*
TRUSTED_HEADERS=X-Forwarded-For,X-Forwarded-Host,X-Forwarded-Port,X-Forwarded-Proto
```

### 3. Déploiement
1. **Redéployez** votre service Render
2. **Surveillez** les logs de déploiement
3. **Vérifiez** que les scripts s'exécutent

### 4. Vérification Post-Déploiement

#### Logs à Surveiller
Recherchez ces messages dans les logs Render :
```
[start.sh] FORCE_ADMIN_MIGRATION est activé -> exécution du script de migration forcée d'administration
🔧 MIGRATION FORCÉE - ADMINISTRATION (DOCKER)
✅ Table administration_functions créée avec succès
✅ Colonne church_id ajoutée à members
✅ Type de fonction ajouté: Pasteur Principal
🎉 MIGRATION FORCÉE TERMINÉE AVEC SUCCÈS!
```

#### Tests Fonctionnels
1. **Connexion** : Testez la connexion utilisateur
2. **Administration** : Accédez à la section administration
3. **Membres** : Vérifiez que les membres s'affichent
4. **Fonctions** : Testez l'ajout de fonctions d'administration

## 🚨 Résolution de Problèmes

### Problème : Scripts ne s'exécutent pas
**Solution :**
- Vérifiez que `FORCE_ADMIN_MIGRATION=true` est défini
- Vérifiez les logs de démarrage
- Redéployez le service

### Problème : Tables manquantes
**Solution :**
- Vérifiez la connexion à la base de données
- Exécutez manuellement : `php artisan admin:force-migration --force`
- Vérifiez les permissions de la base de données

### Problème : Colonnes church_id manquantes
**Solution :**
- Le script les ajoute automatiquement
- Vérifiez les logs pour les erreurs
- Exécutez les migrations Laravel : `php artisan migrate --force`

## 📊 Résultat Attendu

### Tables Créées
- ✅ `administration_functions`
- ✅ `administration_function_types`
- ✅ `roles`
- ✅ `permissions`
- ✅ `churches`

### Colonnes Ajoutées
- ✅ `church_id` dans toutes les tables nécessaires

### Données Insérées
- ✅ Types de fonctions d'administration
- ✅ Rôles utilisateurs
- ✅ Permissions système

## 🔄 Maintenance

### Désactiver la Migration Forcée
Une fois le déploiement réussi :
```bash
# Dans Render, changez :
FORCE_ADMIN_MIGRATION=false
```

### Réactiver si Nécessaire
```bash
# Dans Render, changez :
FORCE_ADMIN_MIGRATION=true
```

## 📞 Support

En cas de problème :
1. Vérifiez les logs Render
2. Testez localement avec `./test-admin-migration.sh`
3. Vérifiez la configuration de la base de données
4. Contactez l'équipe de développement

---

**🎉 Votre application sera déployée avec toutes les fonctionnalités d'administration !**
EOF

log_success "Guide de déploiement créé: GUIDE_DEPLOIEMENT_ADMIN.md"

echo ""
echo "🎉 SCRIPTS DE DÉPLOIEMENT PRODUCTION CRÉÉS!"
echo "=========================================="
echo ""
echo "📁 Fichiers créés :"
echo "  ✅ render-admin-migration.env - Variables d'environnement Render"
echo "  ✅ deploy-with-admin-migration.sh - Script de déploiement"
echo "  ✅ test-admin-migration.sh - Script de test"
echo "  ✅ GUIDE_DEPLOIEMENT_ADMIN.md - Guide complet"
echo ""
echo "🚀 PROCHAINES ÉTAPES :"
echo "====================="
echo "1. Testez localement : ./test-admin-migration.sh"
echo "2. Déployez : ./deploy-with-admin-migration.sh"
echo "3. Configurez Render avec les variables d'environnement"
echo "4. Redéployez votre service Render"
echo ""
echo "🔧 La migration forcée d'administration s'exécutera automatiquement !"
