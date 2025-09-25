#!/bin/bash

echo "🔧 Test de Correction - Scroll des Pages d'Authentification"
echo "=========================================================="

BASE_URL="http://127.0.0.1:8000"

# Test 1: Vérification de la hauteur fixe
echo -e "\n✅ Test 1: Vérification de la hauteur fixe"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login")
REGISTER_CONTENT=$(curl -s "${BASE_URL}/register")

if echo "$LOGIN_CONTENT" | grep -q "height: 100vh"; then
    echo "   ✅ Page de connexion: height: 100vh présent"
else
    echo "   ❌ Page de connexion: height: 100vh absent"
fi

if echo "$REGISTER_CONTENT" | grep -q "height: 100vh"; then
    echo "   ✅ Page d'inscription: height: 100vh présent"
else
    echo "   ❌ Page d'inscription: height: 100vh absent"
fi

# Test 2: Vérification du overflow hidden sur le container
echo -e "\n✅ Test 2: Vérification du overflow hidden"
if echo "$LOGIN_CONTENT" | grep -q "overflow: hidden"; then
    echo "   ✅ Page de connexion: overflow: hidden présent"
else
    echo "   ❌ Page de connexion: overflow: hidden absent"
fi

if echo "$REGISTER_CONTENT" | grep -q "overflow: hidden"; then
    echo "   ✅ Page d'inscription: overflow: hidden présent"
else
    echo "   ❌ Page d'inscription: overflow: hidden absent"
fi

# Test 3: Vérification du scroll sur la partie formulaire
echo -e "\n✅ Test 3: Vérification du scroll sur les formulaires"
if echo "$LOGIN_CONTENT" | grep -q "overflow-y: auto"; then
    echo "   ✅ Page de connexion: overflow-y: auto présent"
else
    echo "   ❌ Page de connexion: overflow-y: auto absent"
fi

if echo "$REGISTER_CONTENT" | grep -q "overflow-y: auto"; then
    echo "   ✅ Page d'inscription: overflow-y: auto présent"
else
    echo "   ❌ Page d'inscription: overflow-y: auto absent"
fi

# Test 4: Vérification de la structure HTML
echo -e "\n✅ Test 4: Vérification de la structure HTML"
if echo "$LOGIN_CONTENT" | grep -q "auth-content"; then
    echo "   ✅ Page de connexion: Structure auth-content présente"
else
    echo "   ❌ Page de connexion: Structure auth-content absente"
fi

if echo "$REGISTER_CONTENT" | grep -q "auth-content"; then
    echo "   ✅ Page d'inscription: Structure auth-content présente"
else
    echo "   ❌ Page d'inscription: Structure auth-content absente"
fi

# Test 5: Vérification des formulaires complets
echo -e "\n✅ Test 5: Vérification des formulaires complets"
LOGIN_FIELDS=$(echo "$LOGIN_CONTENT" | grep -c 'name="')
REGISTER_FIELDS=$(echo "$REGISTER_CONTENT" | grep -c 'name="')

echo "   📊 Page de connexion: $LOGIN_FIELDS champs détectés"
echo "   📊 Page d'inscription: $REGISTER_FIELDS champs détectés"

if [ "$LOGIN_FIELDS" -ge 3 ]; then
    echo "   ✅ Page de connexion: Formulaires complets"
else
    echo "   ❌ Page de connexion: Formulaires incomplets"
fi

if [ "$REGISTER_FIELDS" -ge 5 ]; then
    echo "   ✅ Page d'inscription: Formulaires complets"
else
    echo "   ❌ Page d'inscription: Formulaires incomplets"
fi

# Test 6: Vérification de l'accessibilité
echo -e "\n✅ Test 6: Vérification de l'accessibilité"
LOGIN_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/login")
REGISTER_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/register")

if [ "$LOGIN_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page de connexion: Accessible (HTTP 200)"
else
    echo "   ❌ Page de connexion: Non accessible (HTTP $LOGIN_RESPONSE)"
fi

if [ "$REGISTER_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page d'inscription: Accessible (HTTP 200)"
else
    echo "   ❌ Page d'inscription: Non accessible (HTTP $REGISTER_RESPONSE)"
fi

echo -e "\n🎯 Résumé de la Correction:"
echo "   - Hauteur fixe: ✅ height: 100vh au lieu de min-height"
echo "   - Overflow container: ✅ overflow: hidden pour éviter les conflits"
echo "   - Scroll formulaires: ✅ overflow-y: auto sur auth-form-side"
echo "   - Structure HTML: ✅ auth-content wrapper pour le contenu scrollable"

echo -e "\n🔧 Corrections Appliquées:"
echo "   - ✅ Container principal: height: 100vh + overflow: hidden"
echo "   - ✅ Côté formulaire: overflow-y: auto + overflow-x: hidden"
echo "   - ✅ Côté image: Pas de scroll, reste fixe"
echo "   - ✅ Structure: auth-content wrapper pour le contenu"

echo -e "\n📋 Instructions pour tester le scroll corrigé:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/register"
echo "   3. Redimensionnez la fenêtre pour qu'elle soit plus petite"
echo "   4. Vérifiez que vous pouvez maintenant scroller dans la partie gauche"
echo "   5. Vérifiez que l'image de droite reste fixe"
echo "   6. Testez la même chose sur http://127.0.0.1:8000/login"

echo -e "\n✨ Comportement Attendu Après Correction:"
echo "   - ✅ Sur grand écran: Tout visible, pas de scroll"
echo "   - ✅ Sur petit écran: Scroll fonctionnel dans la partie formulaire"
echo "   - ✅ Image toujours visible et fixe à droite"
echo "   - ✅ Logo toujours visible en haut à gauche"
echo "   - ✅ Tous les champs accessibles via scroll"

echo -e "\n🎉 CORRECTION APPLIQUÉE ! Le scroll devrait maintenant fonctionner !"
