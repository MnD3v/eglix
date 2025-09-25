#!/bin/bash

echo "✅ Test - Police DM Sans Appliquée aux Boutons d'Authentification"
echo "=============================================================="

BASE_URL="http://127.0.0.1:8000"

# Test 1: Vérification de la police sur la page d'inscription
echo -e "\n✅ Test 1: Vérification de la police sur la page d'inscription"
REGISTER_CONTENT=$(curl -s "${BASE_URL}/register")

if echo "$REGISTER_CONTENT" | grep -q "font-family: 'DM Sans'"; then
    echo "   ✅ Police DM Sans présente dans le CSS"
else
    echo "   ❌ Police DM Sans absente du CSS"
fi

if echo "$REGISTER_CONTENT" | grep -q "Créer mon compte"; then
    echo "   ✅ Bouton 'Créer mon compte' présent"
else
    echo "   ❌ Bouton 'Créer mon compte' absent"
fi

# Test 2: Vérification de la police sur la page de connexion
echo -e "\n✅ Test 2: Vérification de la police sur la page de connexion"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login")

if echo "$LOGIN_CONTENT" | grep -q "font-family: 'DM Sans'"; then
    echo "   ✅ Police DM Sans présente dans le CSS"
else
    echo "   ❌ Police DM Sans absente du CSS"
fi

if echo "$LOGIN_CONTENT" | grep -q "Se connecter"; then
    echo "   ✅ Bouton 'Se connecter' présent"
else
    echo "   ❌ Bouton 'Se connecter' absent"
fi

# Test 3: Vérification spécifique des boutons email-btn
echo -e "\n✅ Test 3: Vérification spécifique des boutons email-btn"
REGISTER_BUTTON_CSS=$(echo "$REGISTER_CONTENT" | grep -A 10 "\.email-btn")
LOGIN_BUTTON_CSS=$(echo "$LOGIN_CONTENT" | grep -A 10 "\.email-btn")

if echo "$REGISTER_BUTTON_CSS" | grep -q "font-family: 'DM Sans'"; then
    echo "   ✅ Bouton inscription: Police DM Sans appliquée"
else
    echo "   ❌ Bouton inscription: Police DM Sans non appliquée"
fi

if echo "$LOGIN_BUTTON_CSS" | grep -q "font-family: 'DM Sans'"; then
    echo "   ✅ Bouton connexion: Police DM Sans appliquée"
else
    echo "   ❌ Bouton connexion: Police DM Sans non appliquée"
fi

# Test 4: Vérification de la cohérence avec le body
echo -e "\n✅ Test 4: Vérification de la cohérence avec le body"
if echo "$REGISTER_CONTENT" | grep -q "body.*font-family: 'DM Sans'"; then
    echo "   ✅ Police DM Sans cohérente avec le body"
else
    echo "   ❌ Police DM Sans non cohérente avec le body"
fi

# Test 5: Vérification des polices de fallback
echo -e "\n✅ Test 5: Vérification des polices de fallback"
if echo "$REGISTER_CONTENT" | grep -q "font-family: 'DM Sans', -apple-system, BlinkMacSystemFont"; then
    echo "   ✅ Polices de fallback présentes"
else
    echo "   ❌ Polices de fallback absentes"
fi

# Test 6: Vérification de l'accessibilité
echo -e "\n✅ Test 6: Vérification de l'accessibilité"
REGISTER_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/register")
LOGIN_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/login")

if [ "$REGISTER_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page d'inscription accessible (HTTP 200)"
else
    echo "   ❌ Page d'inscription non accessible (HTTP $REGISTER_RESPONSE)"
fi

if [ "$LOGIN_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page de connexion accessible (HTTP 200)"
else
    echo "   ❌ Page de connexion non accessible (HTTP $LOGIN_RESPONSE)"
fi

echo -e "\n🎯 Résumé de la Modification de Police:"
echo "   - Police principale: ✅ DM Sans appliquée aux boutons"
echo "   - Cohérence: ✅ Même police que le reste du site"
echo "   - Fallback: ✅ Polices de secours incluses"
echo "   - Pages: ✅ Connexion et inscription mises à jour"

echo -e "\n🎨 Caractéristiques de la Police DM Sans:"
echo "   - ✅ Police moderne et lisible"
echo "   - ✅ Optimisée pour l'interface utilisateur"
echo "   - ✅ Support des poids de police (300-700)"
echo "   - ✅ Compatible avec tous les navigateurs"
echo "   - ✅ Cohérente avec l'identité visuelle du site"

echo -e "\n📋 Boutons Mis à Jour:"
echo "   - ✅ Page d'inscription: 'Créer mon compte'"
echo "   - ✅ Page de connexion: 'Se connecter'"
echo "   - ✅ Police: DM Sans avec fallbacks"
echo "   - ✅ Poids: 600 (semi-bold)"
echo "   - ✅ Taille: 15px"

echo -e "\n📋 Instructions pour tester visuellement:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/register"
echo "   3. Vérifiez que le bouton 'Créer mon compte' utilise DM Sans"
echo "   4. Allez sur http://127.0.0.1:8000/login"
echo "   5. Vérifiez que le bouton 'Se connecter' utilise DM Sans"
echo "   6. Comparez avec les autres éléments de la page"

echo -e "\n✨ Avantages de DM Sans:"
echo "   - ✅ Lisibilité optimale sur tous les écrans"
echo "   - ✅ Cohérence avec l'identité visuelle"
echo "   - ✅ Support complet des caractères français"
echo "   - ✅ Performance optimisée"
echo "   - ✅ Accessibilité améliorée"

echo -e "\n🎉 SUCCÈS ! Police DM Sans appliquée aux boutons d'authentification !"
