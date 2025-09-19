#!/usr/bin/env bash

echo "🚨 CORRECTION URGENTE - TABLE ADMINISTRATION_FUNCTIONS MANQUANTE"
echo "=============================================================="

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

log_info "Correction urgente de la table administration_functions..."

# Fonction pour créer une table si elle n'existe pas
create_table_if_not_exists() {
    local table_name="$1"
    local create_sql="$2"
    
    log_info "Vérification de la table: $table_name"
    
    if ! php artisan tinker --execute="echo \Schema::hasTable('$table_name') ? 'exists' : 'missing';" | grep -q "exists"; then
        log_warning "Table $table_name manquante, création en cours..."
        
        # Exécuter la création via Artisan
        php artisan tinker --execute="
        try {
            \Schema::create('$table_name', function (\$table) {
                \$table->id();
                \$table->unsignedBigInteger('member_id');
                \$table->string('function_name');
                \$table->date('start_date');
                \$table->date('end_date')->nullable();
                \$table->text('notes')->nullable();
                \$table->boolean('is_active')->default(true);
                \$table->unsignedBigInteger('church_id')->nullable();
                \$table->timestamps();
                
                \$table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                \$table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            });
            echo 'Table $table_name créée avec succès';
        } catch (Exception \$e) {
            echo 'Erreur: ' . \$e->getMessage();
        }
        "
        
        if [ $? -eq 0 ]; then
            log_success "Table $table_name créée avec succès"
        else
            log_error "Échec de la création de la table $table_name"
        fi
    else
        log_success "Table $table_name existe déjà"
    fi
}

# Créer la table administration_functions
create_table_if_not_exists "administration_functions" "
CREATE TABLE IF NOT EXISTS administration_functions (
    id SERIAL PRIMARY KEY,
    member_id INTEGER NOT NULL,
    function_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    notes TEXT,
    is_active BOOLEAN DEFAULT true,
    church_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE
);"

# Créer la table administration_function_types
create_table_if_not_exists "administration_function_types" "
CREATE TABLE IF NOT EXISTS administration_function_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    church_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (church_id) REFERENCES churches(id) ON DELETE CASCADE
);"

# Insérer des données de base
log_info "Insertion des données de base..."

php artisan tinker --execute="
try {
    // Insérer des types de fonctions par défaut
    \$types = [
        ['name' => 'Pasteur Principal', 'description' => 'Responsable principal de l\'église'],
        ['name' => 'Pasteur Associé', 'description' => 'Pasteur associé'],
        ['name' => 'Diacre', 'description' => 'Responsable du service diaconal'],
        ['name' => 'Secrétaire', 'description' => 'Responsable de l\'administration'],
        ['name' => 'Trésorier', 'description' => 'Responsable des finances'],
        ['name' => 'Responsable Jeunesse', 'description' => 'Responsable du ministère jeunesse'],
        ['name' => 'Responsable Musique', 'description' => 'Responsable du ministère musical'],
        ['name' => 'Responsable Enfants', 'description' => 'Responsable du ministère enfants']
    ];
    
    foreach (\$types as \$type) {
        \App\Models\AdministrationFunctionType::firstOrCreate(
            ['name' => \$type['name']],
            \$type
        );
    }
    
    echo 'Types de fonctions insérés avec succès';
} catch (Exception \$e) {
    echo 'Erreur lors de l\'insertion: ' . \$e->getMessage();
}
"

# Vérifier que tout fonctionne
log_info "Vérification finale..."

php artisan tinker --execute="
try {
    \$count = \App\Models\AdministrationFunction::count();
    echo 'Nombre d\'enregistrements dans administration_functions: ' . \$count;
} catch (Exception \$e) {
    echo 'Erreur de vérification: ' . \$e->getMessage();
}
"

echo ""
echo "🎉 CORRECTION TERMINÉE!"
echo "======================"
echo ""
echo "📋 VÉRIFICATIONS À EFFECTUER:"
echo "1. Accédez à https://eglix.lafia.tech/administration"
echo "2. Vérifiez que la page se charge sans erreur"
echo "3. Testez la création d'une fonction d'administration"
echo ""
echo "🔧 Si le problème persiste:"
echo "1. Vérifiez les logs Render"
echo "2. Exécutez: php artisan migrate:status"
echo "3. Contactez l'équipe de développement"
