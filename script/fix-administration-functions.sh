#!/usr/bin/env bash

echo "🔧 CORRECTION SPÉCIFIQUE - TABLE administration_functions"
echo "========================================================"

# Attendre que la DB soit disponible
echo "⏳ Attente de la base de données..."
for i in {1..30}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        echo "✅ Base de données connectée"
        break
    fi
    echo "Tentative $i/30..."
    sleep 2
done

# Vérifier si la table administration_functions existe
echo "🔍 Vérification de la table administration_functions..."
php artisan tinker --execute="
try {
    \$exists = Schema::hasTable('administration_functions');
    if (\$exists) {
        echo \"✅ Table administration_functions existe déjà\n\";
        \$count = DB::table('administration_functions')->count();
        echo \"📊 Nombre d'enregistrements: \$count\n\";
    } else {
        echo \"❌ Table administration_functions n'existe pas\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification: \" . \$e->getMessage() . \"\n\";
}
"

# Créer la table si elle n'existe pas
echo "🛠️ Création de la table administration_functions si nécessaire..."
php artisan tinker --execute="
try {
    if (!Schema::hasTable('administration_functions')) {
        echo \"Création de la table administration_functions...\n\";
        
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
        
        echo \"✅ Table administration_functions créée avec succès\n\";
    } else {
        echo \"✅ Table administration_functions existe déjà\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la création: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Exécuter les migrations pour s'assurer que tout est à jour
echo "🔄 Exécution des migrations..."
php artisan migrate --force --no-interaction

# Vérifier que la table fonctionne
echo "✅ Vérification finale..."
php artisan tinker --execute="
try {
    \$count = DB::table('administration_functions')->count();
    echo \"✅ Table administration_functions fonctionne correctement\n\";
    echo \"📊 Nombre d'enregistrements: \$count\n\";
    
    // Test d'insertion
    \$testId = DB::table('administration_functions')->insertGetId([
        'member_id' => 1,
        'function_name' => 'Test',
        'start_date' => now()->toDateString(),
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    if (\$testId) {
        DB::table('administration_functions')->where('id', \$testId)->delete();
        echo \"✅ Test d'insertion/suppression réussi\n\";
    }
} catch (Exception \$e) {
    echo \"❌ ERREUR lors du test: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS!"
echo "La table administration_functions est maintenant disponible."
