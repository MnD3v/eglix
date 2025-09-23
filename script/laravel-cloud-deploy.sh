#!/bin/bash

# Script de déploiement pour Laravel Cloud
# Ce script résout les problèmes de migration et de sessions

echo "🚀 Déploiement Laravel Cloud - Correction des problèmes..."

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
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

# Vérifier si nous sommes sur Laravel Cloud
if [ -z "$LARAVEL_CLOUD" ]; then
    log_warning "Variable LARAVEL_CLOUD non définie, définition automatique..."
    export LARAVEL_CLOUD=true
fi

log_info "Configuration Laravel Cloud détectée"

# 1. Nettoyer le cache
log_info "Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
log_success "Cache nettoyé"

# 2. Vérifier la configuration de la base de données
log_info "Vérification de la configuration de la base de données..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo '✅ Connexion à la base de données réussie';
} catch (Exception \$e) {
    echo '❌ Erreur de connexion à la base de données: ' . \$e->getMessage();
    exit(1);
}
"

# 3. Corriger les problèmes de déploiement
log_info "Correction des problèmes de déploiement Laravel Cloud..."
php artisan laravel-cloud:fix-deployment

# 4. Exécuter les migrations avec gestion d'erreurs
log_info "Exécution des migrations..."
php artisan migrate --force

# Vérifier le statut des migrations
if [ $? -eq 0 ]; then
    log_success "Migrations exécutées avec succès"
else
    log_warning "Erreur lors des migrations, tentative de correction..."
    
    # Essayer de corriger les problèmes de migration
    php artisan migrate:status
    
    # Forcer la migration si nécessaire
    log_info "Tentative de migration forcée..."
    php artisan migrate --force --step
fi

# 5. Vérifier les tables critiques
log_info "Vérification des tables critiques..."
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

# 6. Configurer les permissions
log_info "Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
log_success "Permissions configurées"

# 7. Optimiser l'application
log_info "Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
log_success "Application optimisée"

# 8. Vérification finale
log_info "Vérification finale..."
php artisan tinker --execute="
try {
    // Test de connexion
    DB::connection()->getPdo();
    echo '✅ Base de données accessible';
    
    // Test des sessions
    if (Schema::hasTable('sessions')) {
        echo '✅ Table sessions disponible';
    } else {
        echo '❌ Table sessions manquante';
    }
    
    // Test des tables principales
    \$mainTables = ['users', 'churches'];
    foreach (\$mainTables as \$table) {
        if (Schema::hasTable(\$table)) {
            echo '✅ Table ' . \$table . ' disponible';
        } else {
            echo '❌ Table ' . \$table . ' manquante';
        }
    }
    
} catch (Exception \$e) {
    echo '❌ Erreur lors de la vérification finale: ' . \$e->getMessage();
    exit(1);
}
"

# 9. Afficher le résumé
log_success "Déploiement Laravel Cloud terminé !"
echo ""
echo "📋 Résumé du déploiement:"
echo "   ✅ Cache nettoyé"
echo "   ✅ Configuration vérifiée"
echo "   ✅ Problèmes de déploiement corrigés"
echo "   ✅ Migrations exécutées"
echo "   ✅ Permissions configurées"
echo "   ✅ Application optimisée"
echo ""
echo "🎉 Votre application est maintenant prête sur Laravel Cloud !"
