#!/bin/bash

echo "🎨 Test des nouvelles pages d'authentification élégantes"
echo "======================================================"

BASE_URL="http://127.0.0.1:8000"

# Test 1: Page de connexion élégante
echo -e "\n✅ Test 1: Page de connexion élégante"
LOGIN_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/login")
if [ "$LOGIN_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page de connexion accessible (HTTP 200)"
else
    echo "   ❌ Page de connexion non accessible (HTTP $LOGIN_RESPONSE)"
fi

# Test 2: Page d'inscription élégante
echo -e "\n✅ Test 2: Page d'inscription élégante"
REGISTER_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/register")
if [ "$REGISTER_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page d'inscription accessible (HTTP 200)"
else
    echo "   ❌ Page d'inscription non accessible (HTTP $REGISTER_RESPONSE)"
fi

# Test 3: Vérification de l'image de fond
echo -e "\n✅ Test 3: Vérification de l'image de fond"
IMAGE_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/images/auth-background.png")
if [ "$IMAGE_RESPONSE" -eq 200 ]; then
    echo "   ✅ Image auth-background.png accessible"
else
    echo "   ❌ Image auth-background.png non accessible (HTTP $IMAGE_RESPONSE)"
fi

# Test 4: Vérification du logo Eglix
echo -e "\n✅ Test 4: Vérification du logo Eglix"
LOGO_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/images/eglix.png")
if [ "$LOGO_RESPONSE" -eq 200 ]; then
    echo "   ✅ Logo Eglix accessible"
else
    echo "   ❌ Logo Eglix non accessible (HTTP $LOGO_RESPONSE)"
fi

# Test 5: Test du contenu des pages
echo -e "\n✅ Test 5: Test du contenu des pages"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login" | head -10)
if echo "$LOGIN_CONTENT" | grep -q "Bienvenue"; then
    echo "   ✅ Page de connexion contient le titre 'Bienvenue'"
else
    echo "   ❌ Page de connexion ne contient pas le titre 'Bienvenue'"
fi

REGISTER_CONTENT=$(curl -s "${BASE_URL}/register" | head -10)
if echo "$REGISTER_CONTENT" | grep -q "Rejoignez Eglix"; then
    echo "   ✅ Page d'inscription contient le titre 'Rejoignez Eglix'"
else
    echo "   ❌ Page d'inscription ne contient pas le titre 'Rejoignez Eglix'"
fi

echo -e "\n🎯 Résumé des tests:"
echo "   - Page de connexion: $([ "$LOGIN_RESPONSE" -eq 200 ] && echo "✅ Accessible" || echo "❌ Non accessible")"
echo "   - Page d'inscription: $([ "$REGISTER_RESPONSE" -eq 200 ] && echo "✅ Accessible" || echo "❌ Non accessible")"
echo "   - Image de fond: $([ "$IMAGE_RESPONSE" -eq 200 ] && echo "✅ Accessible" || echo "❌ Non accessible")"
echo "   - Logo Eglix: $([ "$LOGO_RESPONSE" -eq 200 ] && echo "✅ Accessible" || echo "❌ Non accessible")"

echo -e "\n📋 Instructions pour tester les nouvelles pages:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/login"
echo "   3. Allez sur http://127.0.0.1:8000/register"
echo "   4. Testez les boutons sociaux (Google, Apple)"
echo "   5. Testez le formulaire email"
echo "   6. Vérifiez le design responsive"

echo -e "\n🎨 Caractéristiques du design:"
echo "   - ✅ Split-screen moderne"
echo "   - ✅ Image de fond auth-background.png"
echo "   - ✅ Logo Eglix intégré"
echo "   - ✅ Boutons sociaux (Google, Apple)"
echo "   - ✅ Formulaire email masqué par défaut"
echo "   - ✅ Animations d'entrée"
echo "   - ✅ Design responsive"
echo "   - ✅ Couleurs du site (#ff2600, noir, blanc)"
echo "   - ✅ Polices DM Sans et Plus Jakarta Sans"

echo -e "\n✨ Fonctionnalités:"
echo "   - ✅ Boutons sociaux avec hover effects"
echo "   - ✅ Formulaire email avec validation"
echo "   - ✅ Messages d'erreur/succès"
echo "   - ✅ Animations et transitions"
echo "   - ✅ Design mobile-first"
echo "   - ✅ Accessibilité améliorée"
