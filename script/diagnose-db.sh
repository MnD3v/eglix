#!/usr/bin/env bash

echo "=========================================="
echo "🔍 DIAGNOSTIC BASE DE DONNÉES"
echo "=========================================="

# Vérifier la connexion
echo "🔌 Test de connexion à la base de données..."
php artisan migrate:status

echo ""
echo "📋 Tables existantes:"
php artisan tinker --execute="
\$tables = \DB::select(\"SELECT tablename FROM pg_tables WHERE schemaname = 'public'\");
foreach (\$tables as \$table) {
    echo '- ' . \$table->tablename . PHP_EOL;
}
"

echo ""
echo "🔍 Vérification des modèles critiques:"
php artisan tinker --execute="
\$models = [
    'Church' => \App\Models\Church::class,
    'Role' => \App\Models\Role::class,
    'User' => \App\Models\User::class,
    'Member' => \App\Models\Member::class,
];

foreach (\$models as \$name => \$class) {
    try {
        \$count = \$class::count();
        echo \"✅ \$name: \$count enregistrements\n\";
    } catch (Exception \$e) {
        echo \"❌ \$name: ERREUR - \" . \$e->getMessage() . \"\n\";
    }
}
"

echo ""
echo "📊 État des migrations:"
php artisan migrate:status | grep -E "(Pending|Ran)"

echo "=========================================="
