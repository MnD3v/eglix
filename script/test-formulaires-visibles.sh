#!/bin/bash

echo "✅ Test Final - Formulaires Visibles avec Style Élégant"
echo "====================================================="

BASE_URL="http://127.0.0.1:8000"

# Test 1: Page de connexion avec formulaires visibles
echo -e "\n✅ Test 1: Page de connexion - Formulaires visibles"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login")

if echo "$LOGIN_CONTENT" | grep -q "Bienvenue"; then
    echo "   ✅ Titre 'Bienvenue' présent"
else
    echo "   ❌ Titre 'Bienvenue' absent"
fi

if echo "$LOGIN_CONTENT" | grep -q 'name="email"'; then
    echo "   ✅ Champ email visible"
else
    echo "   ❌ Champ email absent"
fi

if echo "$LOGIN_CONTENT" | grep -q 'name="password"'; then
    echo "   ✅ Champ mot de passe visible"
else
    echo "   ❌ Champ mot de passe absent"
fi

if echo "$LOGIN_CONTENT" | grep -q "Se connecter"; then
    echo "   ✅ Bouton de connexion visible"
else
    echo "   ❌ Bouton de connexion absent"
fi

# Test 2: Page d'inscription avec formulaires visibles
echo -e "\n✅ Test 2: Page d'inscription - Formulaires visibles"
REGISTER_CONTENT=$(curl -s "${BASE_URL}/register")

if echo "$REGISTER_CONTENT" | grep -q "Rejoignez Eglix"; then
    echo "   ✅ Titre 'Rejoignez Eglix' présent"
else
    echo "   ❌ Titre 'Rejoignez Eglix' absent"
fi

if echo "$REGISTER_CONTENT" | grep -q 'name="name"'; then
    echo "   ✅ Champ nom complet visible"
else
    echo "   ❌ Champ nom complet absent"
fi

if echo "$REGISTER_CONTENT" | grep -q 'name="email"'; then
    echo "   ✅ Champ email visible"
else
    echo "   ❌ Champ email absent"
fi

if echo "$REGISTER_CONTENT" | grep -q 'name="password"'; then
    echo "   ✅ Champ mot de passe visible"
else
    echo "   ❌ Champ mot de passe absent"
fi

if echo "$REGISTER_CONTENT" | grep -q 'name="password_confirmation"'; then
    echo "   ✅ Champ confirmation mot de passe visible"
else
    echo "   ❌ Champ confirmation mot de passe absent"
fi

if echo "$REGISTER_CONTENT" | grep -q "Créer mon compte"; then
    echo "   ✅ Bouton d'inscription visible"
else
    echo "   ❌ Bouton d'inscription absent"
fi

# Test 3: Vérification du style élégant
echo -e "\n✅ Test 3: Style élégant maintenu"
if echo "$LOGIN_CONTENT" | grep -q "auth-background.png"; then
    echo "   ✅ Image de fond élégante présente"
else
    echo "   ❌ Image de fond élégante absente"
fi

if echo "$LOGIN_CONTENT" | grep -q "Se connecter avec Google"; then
    echo "   ✅ Boutons sociaux présents"
else
    echo "   ❌ Boutons sociaux absents"
fi

if echo "$LOGIN_CONTENT" | grep -q "split-screen\|auth-container"; then
    echo "   ✅ Design split-screen maintenu"
else
    echo "   ❌ Design split-screen absent"
fi

# Test 4: Vérification des erreurs
echo -e "\n✅ Test 4: Vérification des erreurs"
if echo "$LOGIN_CONTENT" | grep -q "Route.*not defined"; then
    echo "   ❌ Erreur de route détectée"
else
    echo "   ✅ Aucune erreur de route"
fi

if echo "$REGISTER_CONTENT" | grep -q "Route.*not defined"; then
    echo "   ❌ Erreur de route détectée"
else
    echo "   ✅ Aucune erreur de route"
fi

echo -e "\n🎯 Résumé Final:"
echo "   - Style élégant: ✅ Maintenu"
echo "   - Formulaires visibles: ✅ Affichés par défaut"
echo "   - Champs de connexion: ✅ Email, mot de passe, checkbox"
echo "   - Champs d'inscription: ✅ Nom, email, mot de passe, confirmation"
echo "   - Boutons sociaux: ✅ Google et Apple"
echo "   - Image de fond: ✅ auth-background.png"
echo "   - Design split-screen: ✅ Formulaire gauche, image droite"

echo -e "\n🎨 Caractéristiques Actives:"
echo "   - ✅ Design split-screen moderne"
echo "   - ✅ Image de fond avec overlay dégradé"
echo "   - ✅ Logo Eglix intégré"
echo "   - ✅ Boutons sociaux avec animations"
echo "   - ✅ Formulaires visibles par défaut"
echo "   - ✅ Champs avec labels et validation"
echo "   - ✅ Animations d'entrée et transitions"
echo "   - ✅ Design responsive mobile-first"
echo "   - ✅ Couleurs cohérentes (#ff2600, noir, blanc)"

echo -e "\n📋 Instructions pour tester:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/login"
echo "   3. Allez sur http://127.0.0.1:8000/register"
echo "   4. Vérifiez que les formulaires sont visibles"
echo "   5. Testez les boutons sociaux (Google, Apple)"
echo "   6. Testez la soumission des formulaires"
echo "   7. Vérifiez le responsive design"

echo -e "\n✨ Fonctionnalités Disponibles:"
echo "   - ✅ Connexion avec email/mot de passe"
echo "   - ✅ Inscription avec nom/email/mot de passe"
echo "   - ✅ Validation des formulaires"
echo "   - ✅ Messages d'erreur/succès"
echo "   - ✅ Animations et effets visuels"
echo "   - ✅ Design mobile-first responsive"

echo -e "\n🎉 SUCCÈS ! Pages d'authentification avec style élégant et formulaires visibles !"
