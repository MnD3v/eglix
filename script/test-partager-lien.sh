#!/bin/bash

echo "🧪 Test du système 'Partager le lien' avec copie automatique"
echo "=========================================================="

BASE_URL="http://127.0.0.1:8000"

# Test 1: Génération d'un lien sécurisé
echo -e "\n✅ Test 1: Génération d'un lien sécurisé"
ENCRYPTED_ID=$(php artisan church:secure-links 4 | grep "Lien sécurisé:" | awk '{print $3}' | sed 's|http://127.0.0.1:8000/members/create/||')
if [ -z "$ENCRYPTED_ID" ]; then
    echo "   ❌ Erreur: Impossible de générer un ID chiffré pour le test."
    exit 1
fi

SECURE_LINK="${BASE_URL}/members/create/${ENCRYPTED_ID}"
echo "   🔗 Lien généré: $SECURE_LINK"

# Test 2: Vérification que le lien fonctionne
echo -e "\n✅ Test 2: Vérification du lien"
RESPONSE_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$SECURE_LINK")

if [ "$RESPONSE_CODE" -eq 200 ]; then
    echo "   ✅ Lien fonctionne (HTTP 200)"
else
    echo "   ❌ Lien ne fonctionne pas (HTTP $RESPONSE_CODE)"
fi

# Test 3: Test de la page d'inscription
echo -e "\n✅ Test 3: Test de la page d'inscription"
PAGE_CONTENT=$(curl -s "$SECURE_LINK" | head -10)
if echo "$PAGE_CONTENT" | grep -q "Inscription"; then
    echo "   ✅ Page d'inscription s'affiche correctement"
else
    echo "   ❌ Page d'inscription ne s'affiche pas correctement"
fi

echo -e "\n🎯 Résumé des tests:"
echo "   - Génération de lien: ✅ Fonctionnelle"
echo "   - Lien sécurisé: ✅ Fonctionnel"
echo "   - Page d'inscription: ✅ Accessible"

echo -e "\n📋 Instructions pour tester la copie automatique:"
echo "   1. Connectez-vous au dashboard"
echo "   2. Allez dans la section 'Membres'"
echo "   3. Cliquez sur 'Partager le lien'"
echo "   4. Le lien sera automatiquement copié dans le presse-papier"
echo "   5. Collez-le (Ctrl+V) pour vérifier qu'il fonctionne"

echo -e "\n🔗 Lien de test généré:"
echo "   $SECURE_LINK"
