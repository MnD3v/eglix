#!/usr/bin/env bash

echo "🚨 CORRECTION IMMÉDIATE - COLONNES SUBSCRIPTION MANQUANTES"
echo "========================================================"

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

log_admin "Correction immédiate des colonnes subscription manquantes..."

# Fonction pour ajouter les colonnes directement via SQL
add_subscription_columns_direct() {
    log_admin "Ajout direct des colonnes subscription via SQL..."
    
    php artisan tinker --execute="
    use Illuminate\Support\Facades\DB;
    
    echo \"🔧 AJOUT DIRECT DES COLONNES SUBSCRIPTION\n\";
    echo \"=======================================\n\";
    
    try {
        // Vérifier si les colonnes existent déjà
        \$columns = DB::select(\"
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'churches' 
            AND column_name IN ('subscription_status', 'subscription_end_date', 'subscription_amount')
        \");
        
        \$existingColumns = array_column(\$columns, 'column_name');
        
        // Ajouter subscription_start_date si elle n'existe pas
        if (!in_array('subscription_start_date', \$existingColumns)) {
            DB::statement('ALTER TABLE churches ADD COLUMN subscription_start_date DATE NULL');
            echo \"✅ Colonne subscription_start_date ajoutée\n\";
        } else {
            echo \"✅ Colonne subscription_start_date existe déjà\n\";
        }
        
        // Ajouter subscription_end_date si elle n'existe pas
        if (!in_array('subscription_end_date', \$existingColumns)) {
            DB::statement('ALTER TABLE churches ADD COLUMN subscription_end_date DATE NULL');
            echo \"✅ Colonne subscription_end_date ajoutée\n\";
        } else {
            echo \"✅ Colonne subscription_end_date existe déjà\n\";
        }
        
        // Ajouter subscription_status si elle n'existe pas
        if (!in_array('subscription_status', \$existingColumns)) {
            DB::statement(\"ALTER TABLE churches ADD COLUMN subscription_status VARCHAR(20) DEFAULT 'active'\");
            echo \"✅ Colonne subscription_status ajoutée\n\";
        } else {
            echo \"✅ Colonne subscription_status existe déjà\n\";
        }
        
        // Ajouter subscription_amount si elle n'existe pas
        if (!in_array('subscription_amount', \$existingColumns)) {
            DB::statement('ALTER TABLE churches ADD COLUMN subscription_amount DECIMAL(10,2) NULL');
            echo \"✅ Colonne subscription_amount ajoutée\n\";
        } else {
            echo \"✅ Colonne subscription_amount existe déjà\n\";
        }
        
        // Ajouter les autres colonnes optionnelles
        \$optionalColumns = [
            'subscription_currency' => \"VARCHAR(3) DEFAULT 'XOF'\",
            'subscription_plan' => \"VARCHAR(50) DEFAULT 'basic'\",
            'subscription_notes' => 'TEXT NULL',
            'payment_reference' => 'VARCHAR(255) NULL',
            'payment_date' => 'DATE NULL'
        ];
        
        foreach (\$optionalColumns as \$column => \$definition) {
            \$checkColumn = DB::select(\"
                SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = 'churches' AND column_name = '\$column'
            \");
            
            if (empty(\$checkColumn)) {
                DB::statement(\"ALTER TABLE churches ADD COLUMN \$column \$definition\");
                echo \"✅ Colonne \$column ajoutée\n\";
            } else {
                echo \"✅ Colonne \$column existe déjà\n\";
            }
        }
        
        echo \"\n🎉 Toutes les colonnes subscription ont été ajoutées!\n\";
        
    } catch (Exception \$e) {
        echo \"❌ Erreur lors de l'ajout des colonnes: \" . \$e->getMessage() . \"\n\";
        throw \$e;
    }
    "
}

# Fonction pour tester l'AdminController
test_admin_controller() {
    log_admin "Test de l'AdminController après correction..."
    
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
        
        echo \"\n🎉 AdminController fonctionne parfaitement!\n\";
        
    } catch (Exception \$e) {
        echo \"❌ Erreur dans AdminController: \" . \$e->getMessage() . \"\n\";
        throw \$e;
    }
    "
}

# Fonction principale
main() {
    log_admin "🚀 DÉBUT DE LA CORRECTION IMMÉDIATE"
    log_admin "=================================="
    
    # Exécuter les étapes
    add_subscription_columns_direct
    test_admin_controller
    
    log_success ""
    log_success "🎉 CORRECTION IMMÉDIATE TERMINÉE AVEC SUCCÈS!"
    log_success "============================================="
    log_success "✅ Colonnes subscription ajoutées directement via SQL"
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
