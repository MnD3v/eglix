#!/usr/bin/env bash

echo "🐳 SCRIPT DE MIGRATION FORCÉE - ADMINISTRATION (DOCKER)"
echo "======================================================="

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

log_info "Début de la migration forcée des éléments d'administration (Docker)..."

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

log_info "Exécution du script de migration forcée..."

# Exécuter le script principal
php artisan tinker --execute="
try {
    echo \"🔧 MIGRATION FORCÉE - ADMINISTRATION (DOCKER)\n\";
    echo \"============================================\n\";
    
    // Liste des tables d'administration à vérifier/créer
    \$adminTables = [
        'administration_functions',
        'administration_function_types', 
        'roles',
        'permissions',
        'churches'
    ];
    
    // Créer les tables d'administration
    foreach (\$adminTables as \$table) {
        echo \"Vérification de la table: \$table\n\";
        
        if (!Schema::hasTable(\$table)) {
            echo \"❌ Table \$table n'existe pas - Création en cours...\n\";
            
            switch(\$table) {
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
            
            echo \"✅ Table \$table créée avec succès\n\";
        } else {
            echo \"✅ Table \$table existe déjà\n\";
        }
    }
    
    // Ajouter church_id aux tables nécessaires
    \$churchTables = [
        'administration_functions',
        'administration_function_types',
        'users',
        'members',
        'tithes',
        'offerings',
        'donations',
        'expenses',
        'projects',
        'services',
        'church_events',
        'service_roles',
        'service_assignments',
        'journal_entries',
        'journal_images'
    ];
    
    echo \"\nVérification des colonnes church_id...\n\";
    
    foreach (\$churchTables as \$table) {
        if (Schema::hasTable(\$table)) {
            if (!Schema::hasColumn(\$table, 'church_id')) {
                echo \"❌ Colonne church_id manquante dans \$table - Ajout en cours...\n\";
                
                try {
                    Schema::table(\$table, function (\$tableSchema) {
                        \$tableSchema->foreignId('church_id')->nullable()->constrained()->onDelete('cascade');
                    });
                    echo \"✅ Colonne church_id ajoutée à \$table\n\";
                } catch (Exception \$e) {
                    echo \"⚠️  Impossible d'ajouter church_id à \$table: \" . \$e->getMessage() . \"\n\";
                }
            } else {
                echo \"✅ Colonne church_id présente dans \$table\n\";
            }
        } else {
            echo \"⚠️  Table \$table n'existe pas - ignorée\n\";
        }
    }
    
    // Insérer les données de base
    echo \"\nInsertion des données de base...\n\";
    
    // Types de fonctions d'administration
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
        }
    }
    
    // Rôles de base
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
        }
    }
    
    // Permissions de base
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
        }
    }
    
    echo \"\n🎉 MIGRATION FORCÉE TERMINÉE AVEC SUCCÈS!\n\";
    echo \"Toutes les tables et colonnes d'administration sont maintenant disponibles.\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la migration forcée: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

log_success "🎉 MIGRATION FORCÉE DOCKER TERMINÉE AVEC SUCCÈS!"
log_info "Toutes les tables et colonnes d'administration sont maintenant disponibles."
log_info "Vous pouvez maintenant utiliser les fonctionnalités d'administration de l'application."
