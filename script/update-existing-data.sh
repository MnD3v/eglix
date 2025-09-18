#!/usr/bin/env bash

echo "⚡ MISE À JOUR DES DONNÉES EXISTANTES"
echo "===================================="

php artisan tinker --execute="
try {
    // Récupérer le premier utilisateur créé (admin)
    \$admin = \App\Models\User::first();
    if (\$admin && \$admin->church_id) {
        \$churchId = \$admin->church_id;
        echo \"🏢 Church ID trouvé: \$churchId\n\";
        
        // Mettre à jour tous les enregistrements sans church_id
        \$tables = [
            'members' => \App\Models\Member::class,
            'tithes' => \App\Models\Tithe::class,
            'offerings' => \App\Models\Offering::class,
            'donations' => \App\Models\Donation::class,
            'expenses' => \App\Models\Expense::class,
            'projects' => \App\Models\Project::class,
            'services' => \App\Models\Service::class,
            'church_events' => \App\Models\ChurchEvent::class,
        ];
        
        foreach (\$tables as \$tableName => \$modelClass) {
            try {
                if (class_exists(\$modelClass)) {
                    \$updated = \$modelClass::whereNull('church_id')->update(['church_id' => \$churchId]);
                    if (\$updated > 0) {
                        echo \"✅ \$tableName: \$updated enregistrements mis à jour\n\";
                    } else {
                        echo \"ℹ️  \$tableName: déjà à jour\n\";
                    }
                }
            } catch (Exception \$e) {
                echo \"⚠️  \$tableName: \" . \$e->getMessage() . \"\n\";
            }
        }
        
        echo \"🎉 MISE À JOUR DES DONNÉES TERMINÉE!\n\";
    } else {
        echo \"⚠️  Aucun utilisateur admin trouvé\n\";
    }
    
} catch (Exception \$e) {
    echo \"❌ ERREUR: \" . \$e->getMessage() . \"\n\";
}
"
