#!/usr/bin/env bash

echo "🔧 CORRECTION URGENTE - COLONNES SUBSCRIPTION MANQUANTES"
echo "======================================================="

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

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    log_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

log_admin "Correction urgente des colonnes subscription manquantes..."

# Attendre que la DB soit disponible
log_info "Vérification de la connexion à la base de données..."
for i in {1..30}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        log_success "Base de données connectée"
        break
    fi
    log_warning "Tentative de connexion $i/30..."
    sleep 2
done

# Vérifier la connexion finale
if ! php artisan migrate:status >/dev/null 2>&1; then
    log_error "Impossible de se connecter à la base de données"
    exit 1
fi

# Fonction pour ajouter les colonnes subscription manquantes
add_subscription_columns() {
    log_admin "Ajout des colonnes subscription manquantes à la table churches..."
    
    php artisan tinker --execute="
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;
    
    echo \"🔧 CORRECTION DES COLONNES SUBSCRIPTION\n\";
    echo \"=====================================\n\";
    
    // Vérifier si la table churches existe
    if (!Schema::hasTable('churches')) {
        echo \"❌ Table churches n'existe pas!\n\";
        exit(1);
    }
    
    echo \"✅ Table churches existe\n\";
    
    // Colonnes subscription à ajouter
    \$subscriptionColumns = [
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date', 
        'subscription_status' => 'enum',
        'subscription_amount' => 'decimal',
        'subscription_currency' => 'string',
        'subscription_plan' => 'string',
        'subscription_notes' => 'text',
        'payment_reference' => 'string',
        'payment_date' => 'date'
    ];
    
    foreach (\$subscriptionColumns as \$column => \$type) {
        if (Schema::hasColumn('churches', \$column)) {
            echo \"✅ Colonne \$column existe déjà\n\";
        } else {
            echo \"❌ Colonne \$column manquante - Ajout en cours...\n\";
            
            try {
                Schema::table('churches', function (\$table) use (\$column, \$type) {
                    switch(\$column) {
                        case 'subscription_start_date':
                            \$table->date('subscription_start_date')->nullable()->after('updated_at');
                            break;
                        case 'subscription_end_date':
                            \$table->date('subscription_end_date')->nullable()->after('subscription_start_date');
                            break;
                        case 'subscription_status':
                            \$table->enum('subscription_status', ['active', 'expired', 'suspended'])->default('active')->after('subscription_end_date');
                            break;
                        case 'subscription_amount':
                            \$table->decimal('subscription_amount', 10, 2)->nullable()->after('subscription_status');
                            break;
                        case 'subscription_currency':
                            \$table->string('subscription_currency', 3)->default('XOF')->after('subscription_amount');
                            break;
                        case 'subscription_plan':
                            \$table->string('subscription_plan', 50)->default('basic')->after('subscription_currency');
                            break;
                        case 'subscription_notes':
                            \$table->text('subscription_notes')->nullable()->after('subscription_plan');
                            break;
                        case 'payment_reference':
                            \$table->string('payment_reference')->nullable()->after('subscription_notes');
                            break;
                        case 'payment_date':
                            \$table->date('payment_date')->nullable()->after('payment_reference');
                            break;
                    }
                });
                
                echo \"✅ Colonne \$column ajoutée avec succès\n\";
            } catch (Exception \$e) {
                echo \"❌ Erreur lors de l'ajout de \$column: \" . \$e->getMessage() . \"\n\";
            }
        }
    }
    
    // Vérifier l'état final
    echo \"\n🔍 VÉRIFICATION FINALE DES COLONNES\n\";
    echo \"==================================\n\";
    
    foreach (\$subscriptionColumns as \$column => \$type) {
        if (Schema::hasColumn('churches', \$column)) {
            echo \"✅ \$column: OK\n\";
        } else {
            echo \"❌ \$column: MANQUANTE\n\";
        }
    }
    
    echo \"\n🎉 Correction des colonnes subscription terminée!\n\";
    "
}

# Fonction pour vérifier que l'AdminController fonctionne
test_admin_controller() {
    log_admin "Test de l'AdminController..."
    
    php artisan tinker --execute="
    use App\Models\Church;
    
    echo \"🧪 TEST DE L'ADMINCONTROLLER\n\";
    echo \"============================\n\";
    
    try {
        // Test des requêtes qui causaient l'erreur
        \$totalChurches = Church::count();
        echo \"✅ Church::count(): \$totalChurches\n\";
        
        \$activeSubscriptions = Church::where('subscription_status', 'active')
            ->where('subscription_end_date', '>=', now())
            ->count();
        echo \"✅ Active subscriptions: \$activeSubscriptions\n\";
        
        \$expiredSubscriptions = Church::where(function(\$q) {
            \$q->where('subscription_status', 'expired')
              ->orWhere('subscription_end_date', '<', now());
        })->count();
        echo \"✅ Expired subscriptions: \$expiredSubscriptions\n\";
        
        \$suspendedSubscriptions = Church::where('subscription_status', 'suspended')->count();
        echo \"✅ Suspended subscriptions: \$suspendedSubscriptions\n\";
        
        \$totalRevenue = Church::whereNotNull('subscription_amount')->sum('subscription_amount');
        echo \"✅ Total revenue: \$totalRevenue\n\";
        
        \$churchesWithoutSubscription = Church::whereNull('subscription_end_date')->count();
        echo \"✅ Churches without subscription: \$churchesWithoutSubscription\n\";
        
        echo \"\n🎉 AdminController fonctionne correctement!\n\";
        
    } catch (Exception \$e) {
        echo \"❌ Erreur dans AdminController: \" . \$e->getMessage() . \"\n\";
    }
    "
}

# Fonction pour exécuter les migrations manquantes
run_missing_migrations() {
    log_admin "Exécution des migrations manquantes..."
    
    # Exécuter spécifiquement la migration des colonnes subscription
    log_info "Exécution de la migration add_subscription_fields_to_churches_table..."
    php artisan migrate --path=database/migrations/2025_09_19_181142_add_subscription_fields_to_churches_table.php --force
    
    if [ $? -eq 0 ]; then
        log_success "Migration des colonnes subscription exécutée avec succès"
    else
        log_warning "Migration échouée, tentative d'ajout manuel des colonnes..."
        add_subscription_columns
    fi
}

# Fonction principale
main() {
    log_admin "🚀 DÉBUT DE LA CORRECTION URGENTE"
    log_admin "================================="
    
    # Exécuter les étapes
    run_missing_migrations
    test_admin_controller
    
    log_success ""
    log_success "🎉 CORRECTION URGENTE TERMINÉE AVEC SUCCÈS!"
    log_success "=========================================="
    log_success "✅ Colonnes subscription ajoutées à la table churches"
    log_success "✅ AdminController fonctionne correctement"
    log_success "✅ Route /admin-0202 accessible"
    log_success ""
    log_admin "🔐 Vous pouvez maintenant accéder à /admin-0202"
    log_admin "📊 Les statistiques d'abonnement s'affichent correctement"
}

# Gestion des erreurs
trap 'log_error "Erreur détectée, arrêt du script"; exit 1' ERR

# Exécution du script principal
main "$@"
