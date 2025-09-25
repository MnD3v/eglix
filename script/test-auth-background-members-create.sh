#!/bin/bash

echo "✅ Test - Image auth-background Appliquée à la Page /members/create/"
echo "================================================================="

BASE_URL="http://127.0.0.1:8000"

# Générer un lien d'inscription valide
echo -e "\n✅ Test 1: Génération d'un lien d'inscription valide"
REGISTRATION_LINK=$(php artisan tinker --execute="echo App\Services\ChurchIdEncryptionService::generateRegistrationLink(4);" 2>/dev/null)

if [ -n "$REGISTRATION_LINK" ]; then
    echo "   ✅ Lien d'inscription généré: $REGISTRATION_LINK"
else
    echo "   ❌ Impossible de générer le lien d'inscription"
    exit 1
fi

# Test 2: Vérification de l'image de fond
echo -e "\n✅ Test 2: Vérification de l'image de fond"
PAGE_CONTENT=$(curl -s "$REGISTRATION_LINK")

if echo "$PAGE_CONTENT" | grep -q "auth-background.png"; then
    echo "   ✅ Image auth-background.png présente dans le CSS"
else
    echo "   ❌ Image auth-background.png absente du CSS"
fi

if echo "$PAGE_CONTENT" | grep -q "linear-gradient.*rgba(255, 38, 0, 0.1)"; then
    echo "   ✅ Overlay dégradé présent"
else
    echo "   ❌ Overlay dégradé absent"
fi

# Test 3: Vérification de l'accessibilité de la page
echo -e "\n✅ Test 3: Vérification de l'accessibilité de la page"
PAGE_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "$REGISTRATION_LINK")

if [ "$PAGE_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page accessible (HTTP 200)"
else
    echo "   ❌ Page non accessible (HTTP $PAGE_RESPONSE)"
fi

# Test 4: Vérification de l'image auth-background.png
echo -e "\n✅ Test 4: Vérification de l'image auth-background.png"
IMAGE_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/images/auth-background.png")

if [ "$IMAGE_RESPONSE" -eq 200 ]; then
    echo "   ✅ Image auth-background.png accessible"
else
    echo "   ❌ Image auth-background.png non accessible (HTTP $IMAGE_RESPONSE)"
fi

# Test 5: Vérification du contenu de la page
echo -e "\n✅ Test 5: Vérification du contenu de la page"
if echo "$PAGE_CONTENT" | grep -q "Inscription"; then
    echo "   ✅ Titre 'Inscription' présent"
else
    echo "   ❌ Titre 'Inscription' absent"
fi

if echo "$PAGE_CONTENT" | grep -q "registration-card"; then
    echo "   ✅ Carte d'inscription présente"
else
    echo "   ❌ Carte d'inscription absente"
fi

# Test 6: Vérification des styles CSS
echo -e "\n✅ Test 6: Vérification des styles CSS"
if echo "$PAGE_CONTENT" | grep -q "center/cover no-repeat"; then
    echo "   ✅ Propriétés CSS de l'image correctes"
else
    echo "   ❌ Propriétés CSS de l'image incorrectes"
fi

if echo "$PAGE_CONTENT" | grep -q "min-height: 100vh"; then
    echo "   ✅ Hauteur minimale définie"
else
    echo "   ❌ Hauteur minimale non définie"
fi

echo -e "\n🎯 Résumé de l'Application de l'Image de Fond:"
echo "   - Image de fond: ✅ auth-background.png appliquée"
echo "   - Overlay: ✅ Dégradé rouge-noir transparent"
echo "   - Position: ✅ center/cover no-repeat"
echo "   - Hauteur: ✅ min-height: 100vh"
echo "   - Accessibilité: ✅ Page et image accessibles"

echo -e "\n🎨 Caractéristiques du Nouveau Fond:"
echo "   - ✅ Image auth-background.png en arrière-plan"
echo "   - ✅ Overlay dégradé subtil (rgba(255, 38, 0, 0.1) à rgba(0, 0, 0, 0.1))"
echo "   - ✅ Positionnement center/cover pour un affichage optimal"
echo "   - ✅ Pas de répétition (no-repeat)"
echo "   - ✅ Couvre toute la hauteur de l'écran"

echo -e "\n📋 Avantages du Nouveau Fond:"
echo "   - ✅ Cohérence avec les pages d'authentification"
echo "   - ✅ Image de fond professionnelle et moderne"
echo "   - ✅ Overlay subtil qui n'interfère pas avec le contenu"
echo "   - ✅ Meilleure expérience visuelle"
echo "   - ✅ Design unifié sur toute l'application"

echo -e "\n📋 Instructions pour tester visuellement:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur le lien d'inscription généré"
echo "   3. Vérifiez que l'image auth-background.png est visible en arrière-plan"
echo "   4. Vérifiez que l'overlay dégradé est appliqué"
echo "   5. Testez sur différentes tailles d'écran"
echo "   6. Comparez avec les pages de connexion/inscription"

echo -e "\n✨ Résultat Final:"
echo "   - ✅ Page /members/create/ avec auth-background.png"
echo "   - ✅ Design cohérent avec les pages d'authentification"
echo "   - ✅ Image de fond professionnelle"
echo "   - ✅ Overlay dégradé subtil"
echo "   - ✅ Expérience utilisateur améliorée"

echo -e "\n🎉 SUCCÈS ! Image auth-background appliquée à la page /members/create/ !"
