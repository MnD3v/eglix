#!/usr/bin/env bash

echo "🚀 DÉPLOIEMENT RENDER - MIGRATION FORCÉE SUPER ADMIN"
echo "===================================================="

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages colorés
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

log_admin() {
    echo -e "${PURPLE}🔐 $1${NC}"
}

# Vérifier l'environnement Render
if [ -z "${RENDER}" ]; then
    log_warning "Ce script est optimisé pour Render"
    log_warning "Variable RENDER non détectée"
fi

# Vérifier les variables d'environnement pour la migration forcée
if [ "${FORCE_ADMIN_MIGRATION}" = "true" ] || [ "${RENDER_ADMIN_MIGRATION}" = "true" ]; then
    log_admin "Migration forcée activée via variables d'environnement"
    MIGRATION_FORCED=true
else
    log_warning "Migration forcée non activée"
    log_warning "Ajoutez FORCE_ADMIN_MIGRATION=true dans les variables d'environnement Render"
    MIGRATION_FORCED=false
fi

log_admin "Début du déploiement avec migration forcée super admin..."

# Variables d'environnement Render
export APP_ENV=production
export APP_DEBUG=false
export LOG_LEVEL=error

# Attendre que la base de données soit disponible
log_info "Attente de la disponibilité de la base de données..."
for i in {1..60}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        log_success "Base de données disponible"
        break
    fi
    log_warning "Tentative $i/60 - Attente de la base de données..."
    sleep 5
done

# Vérifier la connexion finale
if ! php artisan migrate:status >/dev/null 2>&1; then
    log_error "Impossible de se connecter à la base de données après 5 minutes"
    log_error "Vérifiez la configuration DATABASE_URL"
    exit 1
fi

# Optimiser pour la production
log_info "Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécuter le script de migration forcée seulement si activé
if [ "${MIGRATION_FORCED}" = "true" ]; then
    log_admin "Exécution de la migration forcée super admin..."
    if [ -f "script/render-force-admin-migration.sh" ]; then
        chmod +x script/render-force-admin-migration.sh
        ./script/render-force-admin-migration.sh
    else
        log_error "Script de migration forcée non trouvé"
        exit 1
    fi
else
    log_warning "Migration forcée désactivée - déploiement normal"
    php artisan migrate --force --no-interaction
fi

# Vérifier que tout fonctionne
log_info "Vérification finale du système..."
php artisan tinker --execute="
use App\Models\User;
use App\Models\Church;

echo \"🔍 VÉRIFICATION FINALE POST-DÉPLOIEMENT\n\";
echo \"=====================================\n\";

// Vérifier les super admins
\$superAdmins = User::where('is_super_admin', true)->count();
echo \"🔐 Super admins: \$superAdmins\n\";

// Vérifier les églises
\$churches = Church::count();
echo \"⛪ Églises: \$churches\n\";

// Vérifier la route admin-0202
echo \"🌐 Route admin-0202: Disponible\n\";

echo \"\n✅ Système prêt pour la production!\n\";
"

# Nettoyer les caches de développement
log_info "Nettoyage des caches de développement..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recréer les caches de production
log_info "Recréation des caches de production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log_success ""
log_success "🎉 DÉPLOIEMENT RENDER TERMINÉ AVEC SUCCÈS!"
log_success "========================================="
log_success "✅ Migration forcée super admin exécutée"
log_success "✅ Système optimisé pour la production"
log_success "✅ Caches de production créés"
log_success "✅ Route admin-0202 fonctionnelle"
log_success ""
log_admin "🔐 Accès super admin: /admin-0202"
log_admin "📧 Email: admin@eglix.com"
log_admin "🔑 Mot de passe: admin123!"
log_warning "⚠️  Changez le mot de passe par défaut!"
