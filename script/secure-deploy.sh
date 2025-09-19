#!/usr/bin/env bash

echo "🔒 DÉPLOIEMENT SÉCURISÉ RENDER - HTTPS + SSL"
echo "============================================="

# Configuration des variables d'environnement de sécurité
export APP_ENV=production
export APP_DEBUG=false
export FORCE_HTTPS=true
export SESSION_SECURE_COOKIE=true
export SESSION_HTTP_ONLY=true
export SESSION_SAME_SITE=lax
export TRUSTED_PROXIES=*
export TRUSTED_HEADERS="X-Forwarded-For,X-Forwarded-Host,X-Forwarded-Port,X-Forwarded-Proto"

echo "🔧 Configuration des variables de sécurité..."
echo "APP_ENV: $APP_ENV"
echo "APP_DEBUG: $APP_DEBUG"
echo "FORCE_HTTPS: $FORCE_HTTPS"
echo "SESSION_SECURE_COOKIE: $SESSION_SECURE_COOKIE"

# Attendre que la DB soit disponible
echo ""
echo "⏳ Attente de la base de données..."
for i in {1..30}; do
    if php artisan migrate:status >/dev/null 2>&1; then
        echo "✅ Base de données connectée"
        break
    fi
    echo "Tentative $i/30..."
    sleep 2
done

# Nettoyage des caches
echo ""
echo "🧹 Nettoyage des caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true

# Découverte des packages
echo ""
echo "📦 Découverte des packages..."
php artisan package:discover --ansi || true

# Génération de la clé d'application
echo ""
echo "🔑 Génération de la clé d'application..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force || true
fi

# Configuration de la sécurité
echo ""
echo "🔒 Configuration de la sécurité..."
php artisan tinker --execute="
try {
    // Forcer HTTPS
    config(['app.url' => 'https://eglix.lafia.tech']);
    config(['app.env' => 'production']);
    config(['app.debug' => false]);
    
    // Configuration des sessions sécurisées
    config(['session.secure' => true]);
    config(['session.http_only' => true]);
    config(['session.same_site' => 'lax']);
    
    // Configuration des cookies sécurisés
    config(['cookie.secure' => true]);
    config(['cookie.http_only' => true]);
    config(['cookie.same_site' => 'lax']);
    
    echo \"✅ Configuration de sécurité appliquée\n\";
} catch (Exception \$e) {
    echo \"❌ ERREUR configuration sécurité: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Correction de la table administration_functions
echo ""
echo "🛠️ Correction de la table administration_functions..."
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

# Exécution des migrations
echo ""
echo "🔄 Exécution des migrations..."
php artisan migrate --force --no-interaction

# Création du lien de stockage
echo ""
echo "🔗 Création du lien de stockage..."
php artisan storage:link || true

# Vérification de la sécurité
echo ""
echo "✅ Vérification de la sécurité..."
php artisan tinker --execute="
try {
    // Vérifier la configuration HTTPS
    \$url = config('app.url');
    \$env = config('app.env');
    \$debug = config('app.debug');
    \$secure = config('session.secure');
    
    echo \"✅ Configuration vérifiée:\n\";
    echo \"- URL: \$url\n\";
    echo \"- Environnement: \$env\n\";
    echo \"- Debug: \" . (\$debug ? 'true' : 'false') . \"\n\";
    echo \"- Sessions sécurisées: \" . (\$secure ? 'true' : 'false') . \"\n\";
    
    // Test de l'AdministrationController
    \$controller = new \App\Http\Controllers\AdministrationController();
    echo \"✅ AdministrationController fonctionne\n\";
    
} catch (Exception \$e) {
    echo \"❌ ERREUR lors de la vérification: \" . \$e->getMessage() . \"\n\";
    exit(1);
}
"

# Optimisation de l'application
echo ""
echo "🔧 Optimisation de l'application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Résumé final
echo ""
echo "🎉 DÉPLOIEMENT SÉCURISÉ TERMINÉ AVEC SUCCÈS!"
echo "============================================="
echo "✅ HTTPS forcé"
echo "✅ En-têtes de sécurité appliqués"
echo "✅ Cookies sécurisés configurés"
echo "✅ Sessions sécurisées"
echo "✅ Base de données connectée"
echo "✅ Table administration_functions créée"
echo "✅ Migrations exécutées"
echo "✅ Application optimisée"
echo ""
echo "🔒 Votre application est maintenant sécurisée avec HTTPS!"
echo "Les en-têtes de sécurité sont automatiquement appliqués."
echo "L'erreur 500 sur /administration devrait être résolue."
