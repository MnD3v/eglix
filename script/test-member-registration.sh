#!/bin/bash

# Script de test pour l'inscription publique
echo "🧪 Test d'inscription publique d'un membre"
echo "=========================================="

# URL de base
BASE_URL="http://127.0.0.1:8000"
CHURCH_ID="4"

echo "📋 Test avec l'église ID: $CHURCH_ID"

# Obtenir le token CSRF
echo "🔑 Récupération du token CSRF..."
CSRF_TOKEN=$(curl -s "$BASE_URL/members/create/$CHURCH_ID" | grep -o 'name="_token" value="[^"]*"' | cut -d'"' -f4)

if [ -z "$CSRF_TOKEN" ]; then
    echo "❌ Impossible de récupérer le token CSRF"
    exit 1
fi

echo "✅ Token CSRF récupéré: ${CSRF_TOKEN:0:20}..."

# Données de test
FIRST_NAME="Test"
LAST_NAME="Membre"
EMAIL="test.membre@example.com"
PHONE="+237123456789"
ADDRESS="123 Rue de Test, Yaoundé"
GENDER="male"
MARITAL_STATUS="single"
BIRTH_DATE="1990-01-01"
NOTES="Test automatique"

echo "📝 Soumission du formulaire..."

# Soumettre le formulaire
RESPONSE=$(curl -s -w "%{http_code}" -X POST "$BASE_URL/members/create/$CHURCH_ID" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8" \
  -d "first_name=$FIRST_NAME" \
  -d "last_name=$LAST_NAME" \
  -d "email=$EMAIL" \
  -d "phone=$PHONE" \
  -d "address=$ADDRESS" \
  -d "gender=$GENDER" \
  -d "marital_status=$MARITAL_STATUS" \
  -d "birth_date=$BIRTH_DATE" \
  -d "notes=$NOTES" \
  -d "_token=$CSRF_TOKEN")

# Extraire le code de statut HTTP
HTTP_CODE="${RESPONSE: -3}"
RESPONSE_BODY="${RESPONSE%???}"

echo "📊 Code de réponse HTTP: $HTTP_CODE"

if [ "$HTTP_CODE" = "302" ]; then
    echo "✅ Redirection détectée (probablement vers la page de succès)"
    
    # Extraire l'URL de redirection
    REDIRECT_URL=$(echo "$RESPONSE_BODY" | grep -i "location:" | cut -d' ' -f2 | tr -d '\r\n')
    if [ -n "$REDIRECT_URL" ]; then
        echo "🔄 Redirection vers: $REDIRECT_URL"
    fi
elif [ "$HTTP_CODE" = "200" ]; then
    echo "⚠️  Page retournée (possible erreur de validation)"
    echo "📄 Contenu de la réponse:"
    echo "$RESPONSE_BODY" | head -20
else
    echo "❌ Erreur HTTP: $HTTP_CODE"
    echo "📄 Contenu de la réponse:"
    echo "$RESPONSE_BODY" | head -20
fi

echo ""
echo "🔍 Vérification des logs Laravel..."
echo "=================================="

# Vérifier les logs récents
if [ -f "storage/logs/laravel.log" ]; then
    echo "📋 Dernières entrées de log:"
    tail -n 10 storage/logs/laravel.log | grep -E "(processPublicRegistration|Membre créé|Église trouvée)" || echo "Aucun log pertinent trouvé"
else
    echo "❌ Fichier de log non trouvé"
fi

echo ""
echo "🔍 Vérification des membres en base..."
echo "===================================="

# Vérifier si le membre a été créé
php artisan tinker --execute="
\$member = App\Models\Member::where('first_name', 'Test')->where('last_name', 'Membre')->first();
if (\$member) {
    echo '✅ Membre trouvé: ID ' . \$member->id . ', Nom: ' . \$member->first_name . ' ' . \$member->last_name . ', Église ID: ' . \$member->church_id . PHP_EOL;
} else {
    echo '❌ Aucun membre Test Membre trouvé en base' . PHP_EOL;
}
"

echo ""
echo "✅ Test terminé!"
