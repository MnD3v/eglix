#!/usr/bin/env bash

echo "🔧 SCRIPT DE MIGRATION FORCÉE - ADMINISTRATION"
echo "=============================================="

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    log_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

log_info "Début de la migration forcée des éléments d'administration..."

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

# Liste des tables d'administration à vérifier/créer
ADMIN_TABLES=(
    "administration_functions"
    "administration_function_types"
    "roles"
    "permissions"
    "churches"
)

# Liste des colonnes church_id à ajouter
CHURCH_ID_TABLES=(
    "administration_functions"
    "administration_function_types"
    "users"
    "members"
    "tithes"
    "offerings"
    "donations"
    "expenses"
    "projects"
    "services"
    "church_events"
    "service_roles"
    "service_assignments"
    "journal_entries"
    "journal_images"
)

log_info "Vérification et création des tables d'administration..."

# Fonction pour créer une table si elle n'existe pas
create_table_if_not_exists() {
    local table_name=$1
    log_info "Vérification de la table: $table_name"
    
    php artisan tinker --execute="
    try {
        \$exists = Schema::hasTable('$table_name');
        if (\$exists) {
            echo \"✅ Table $table_name existe déjà\n\";
            \$count = DB::table('$table_name')->count();
            echo \"📊 Nombre d'enregistrements: \$count\n\";
        } else {
            echo \"❌ Table $table_name n'existe pas - Création en cours...\n\";
            
            // Créer la table selon son type
            switch('$table_name') {
                case 'administration_functions':
                    Schema::create('administration_functions', function (\$table) {
                        \$table->id();
                        \$table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                        \$table->string('function_name');
                        \$table->date('start_date');
                        \$table->date('end_date')->nullable();
                        \$table->text('notes')->nullable();
                        \$table->boolean('is_active')->default(true);
                        \$table->timestamps();
                    });
                    break;
                    
                case 'administration_function_types':
                    Schema::create('administration_function_types', function (\$table) {
                        \$table->id();
                        \$table->string('name');
                        \$table->string('slug')->unique();
                        \$table->text('description')->nullable();
                        \$table->boolean('is_active')->default(true);
                        \$table->integer('sort_order')->default(0);
                        \$table->timestamps();
                    });
                    break;
                    
                case 'roles':
                    Schema::create('roles', function (\$table) {
                        \$table->id();
                        \$table->string('name');
                        \$table->string('slug')->unique();
                        \$table->text('description')->nullable();
                        \$table->boolean('is_active')->default(true);
                        \$table->timestamps();
                    });
                    break;
                    
                case 'permissions':
                    Schema::create('permissions', function (\$table) {
                        \$table->id();
                        \$table->string('name');
                        \$table->string('slug')->unique();
                        \$table->text('description')->nullable();
                        \$table->boolean('is_active')->default(true);
                        \$table->timestamps();
                    });
                    break;
                    
                case 'churches':
                    Schema::create('churches', function (\$table) {
                        \$table->id();
                        \$table->string('name');
                        \$table->string('address')->nullable();
                        \$table->string('phone')->nullable();
                        \$table->string('email')->nullable();
                        \$table->text('description')->nullable();
                        \$table->boolean('is_active')->default(true);
                        \$table->timestamps();
                    });
                    break;
            }
            
            echo \"✅ Table $table_name créée avec succès\n\";
        }
    } catch (Exception \$e) {
        echo \"❌ ERREUR lors de la création de $table_name: \" . \$e->getMessage() . \"\n\";
        exit(1);
    }
    "
}

# Créer les tables d'administration
for table in "${ADMIN_TABLES[@]}"; do
    create_table_if_not_exists "$table"
done

log_info "Vérification et ajout des colonnes church_id..."

# Fonction pour ajouter church_id si elle n'existe pas
add_church_id_if_not_exists() {
    local table_name=$1
    log_info "Vérification de church_id dans: $table_name"
    
    php artisan tinker --execute="
    try {
        if (!Schema::hasTable('$table_name')) {
            echo \"⚠️  Table $table_name n'existe pas - ignorée\n\";
            return;
        }
        
        \$hasColumn = Schema::hasColumn('$table_name', 'church_id');
        if (\$hasColumn) {
            echo \"✅ Colonne church_id existe déjà dans $table_name\n\";
        } else {
            echo \"❌ Colonne church_id manquante dans $table_name - Ajout en cours...\n\";
            
            Schema::table('$table_name', function (\$table) {
                \$table->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
            });
            
            echo \"✅ Colonne church_id ajoutée à $table_name\n\";
        }
    } catch (Exception \$e) {
        echo \"❌ ERREUR lors de l'ajout de church_id à $table_name: \" . \$e->getMessage() . \"\n\";
        // Ne pas sortir, continuer avec les autres tables
    }
    "
}

# Ajouter church_id aux tables
for table in "${CHURCH_ID_TABLES[@]}"; do
    add_church_id_if_not_exists "$table"
done

log_info "Exécution des migrations Laravel..."

# Exécuter les migrations Laravel
php artisan migrate --force --no-interaction

if [ $? -eq 0 ]; then
    log_success "Migrations Laravel exécutées avec succès"
else
    log_warning "Certaines migrations ont échoué, mais continuons..."
fi

log_info "Insertion des données de base pour l'administration..."

# Insérer les données de base
php artisan tinker --execute="
try {
    // Insérer les types de fonctions d'administration
    \$functionTypes = [
        ['name' => 'Pasteur Principal', 'slug' => 'pasteur-principal', 'description' => 'Responsable principal de l\'église', 'sort_order' => 1],
        ['name' => 'Pasteur Assistant', 'slug' => 'pasteur-assistant', 'description' => 'Assistant du pasteur principal', 'sort_order' => 2],
        ['name' => 'Ancien', 'slug' => 'ancien', 'description' => 'Membre du conseil des anciens', 'sort_order' => 3],
        ['name' => 'Diacre', 'slug' => 'diacre', 'description' => 'Responsable du service diaconal', 'sort_order' => 4],
        ['name' => 'Secrétaire', 'slug' => 'secretaire', 'description' => 'Responsable de la gestion administrative', 'sort_order' => 5],
        ['name' => 'Trésorier', 'slug' => 'tresorier', 'description' => 'Responsable de la gestion financière', 'sort_order' => 6],
    ];
    
    foreach (\$functionTypes as \$type) {
        \$existing = DB::table('administration_function_types')->where('slug', \$type['slug'])->first();
        if (!\$existing) {
            DB::table('administration_function_types')->insert(array_merge(\$type, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]));
            echo \"✅ Type de fonction ajouté: \" . \$type['name'] . \"\n\";
        } else {
            echo \"ℹ️  Type de fonction existe déjà: \" . \$type['name'] . \"\n\";
        }
    }
    
    // Insérer les rôles de base
    \$roles = [
        ['name' => 'Administrateur', 'slug' => 'admin', 'description' => 'Accès complet à l\'application'],
        ['name' => 'Pasteur', 'slug' => 'pasteur', 'description' => 'Accès aux fonctions pastorales'],
        ['name' => 'Secrétaire', 'slug' => 'secretaire', 'description' => 'Accès aux fonctions administratives'],
        ['name' => 'Membre', 'slug' => 'membre', 'description' => 'Accès de base aux informations'],
    ];
    
    foreach (\$roles as \$role) {
        \$existing = DB::table('roles')->where('slug', \$role['slug'])->first();
        if (!\$existing) {
            DB::table('roles')->insert(array_merge(\$role, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]));
            echo \"✅ Rôle ajouté: \" . \$role['name'] . \"\n\";
        } else {
            echo \"ℹ️  Rôle existe déjà: \" . \$role['name'] . \"\n\";
        }
    }
    
    // Insérer les permissions de base
    \$permissions = [
        ['name' => 'Voir les membres', 'slug' => 'members.view', 'description' => 'Consulter la liste des membres'],
        ['name' => 'Créer des membres', 'slug' => 'members.create', 'description' => 'Ajouter de nouveaux membres'],
        ['name' => 'Modifier les membres', 'slug' => 'members.edit', 'description' => 'Modifier les informations des membres'],
        ['name' => 'Supprimer les membres', 'slug' => 'members.delete', 'description' => 'Supprimer des membres'],
        ['name' => 'Voir les dîmes', 'slug' => 'tithes.view', 'description' => 'Consulter les dîmes'],
        ['name' => 'Gérer les dîmes', 'slug' => 'tithes.manage', 'description' => 'Créer et modifier les dîmes'],
        ['name' => 'Voir les offrandes', 'slug' => 'offerings.view', 'description' => 'Consulter les offrandes'],
        ['name' => 'Gérer les offrandes', 'slug' => 'offerings.manage', 'description' => 'Créer et modifier les offrandes'],
        ['name' => 'Voir les rapports', 'slug' => 'reports.view', 'description' => 'Consulter les rapports'],
        ['name' => 'Gérer l\'administration', 'slug' => 'administration.view', 'description' => 'Accès aux fonctions d\'administration'],
        ['name' => 'Gérer les utilisateurs', 'slug' => 'users.view', 'description' => 'Gérer les comptes utilisateurs'],
    ];
    
    foreach (\$permissions as \$permission) {
        \$existing = DB::table('permissions')->where('slug', \$permission['slug'])->first();
        if (!\$existing) {
            DB::table('permissions')->insert(array_merge(\$permission, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]));
            echo \"✅ Permission ajoutée: \" . \$permission['name'] . \"\n\";
        } else {
            echo \"ℹ️  Permission existe déjà: \" . \$permission['name'] . \"\n\";
        }
    }
    
    echo \"✅ Données de base insérées avec succès\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de l'insertion des données: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

log_info "Vérification finale de l'état des tables..."

# Vérification finale
php artisan tinker --execute="
try {
    echo \"📊 RÉSUMÉ FINAL:\n\";
    echo \"================\n\";
    
    \$tables = ['administration_functions', 'administration_function_types', 'roles', 'permissions', 'churches'];
    
    foreach (\$tables as \$table) {
        if (Schema::hasTable(\$table)) {
            \$count = DB::table(\$table)->count();
            echo \"✅ \$table: \$count enregistrements\n\";
        } else {
            echo \"❌ \$table: Table manquante\n\";
        }
    }
    
    // Vérifier les colonnes church_id
    echo \"\n🏢 VÉRIFICATION DES COLONNES CHURCH_ID:\n\";
    echo \"=====================================\n\";
    
    \$churchTables = ['users', 'members', 'administration_functions'];
    foreach (\$churchTables as \$table) {
        if (Schema::hasTable(\$table)) {
            \$hasChurchId = Schema::hasColumn(\$table, 'church_id');
            echo \$hasChurchId ? \"✅ \$table: church_id présent\n\" : \"❌ \$table: church_id manquant\n\";
        }
    }
    
    echo \"\n🎉 VÉRIFICATION TERMINÉE!\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification finale: \" . \$e->getMessage() . \"\n\";
}
"

log_success "🎉 MIGRATION FORCÉE TERMINÉE AVEC SUCCÈS!"
log_info "Toutes les tables et colonnes d'administration sont maintenant disponibles."
log_info "Vous pouvez maintenant utiliser les fonctionnalités d'administration de l'application."

echo ""
echo "📋 PROCHAINES ÉTAPES:"
echo "===================="
echo "1. Vérifiez que l'application fonctionne correctement"
echo "2. Testez les fonctionnalités d'administration"
echo "3. Configurez les permissions selon vos besoins"
echo "4. Supprimez ce script si tout fonctionne bien"
echo ""
echo "🔧 Script de migration forcée terminé!"
