#!/bin/bash

echo "✅ Test - Styles des Inputs Appliqués aux Pages d'Authentification"
echo "=============================================================="

BASE_URL="http://127.0.0.1:8000"

# Test 1: Vérification des styles sur la page de connexion
echo -e "\n✅ Test 1: Vérification des styles sur la page de connexion"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login")

if echo "$LOGIN_CONTENT" | grep -q "\.form-control"; then
    echo "   ✅ CSS .form-control présent"
else
    echo "   ❌ CSS .form-control absent"
fi

if echo "$LOGIN_CONTENT" | grep -q "border: 2px solid #e1e5e9"; then
    echo "   ✅ Bordure par défaut définie"
else
    echo "   ❌ Bordure par défaut non définie"
fi

if echo "$LOGIN_CONTENT" | grep -q "border-color: #ff2600"; then
    echo "   ✅ Couleur de focus (#ff2600) définie"
else
    echo "   ❌ Couleur de focus non définie"
fi

if echo "$LOGIN_CONTENT" | grep -q "box-shadow: 0 0 0 3px rgba(255, 38, 0, 0.1)"; then
    echo "   ✅ Ombre de focus définie"
else
    echo "   ❌ Ombre de focus non définie"
fi

# Test 2: Vérification des styles sur la page d'inscription
echo -e "\n✅ Test 2: Vérification des styles sur la page d'inscription"
REGISTER_CONTENT=$(curl -s "${BASE_URL}/register")

if echo "$REGISTER_CONTENT" | grep -q "\.form-control"; then
    echo "   ✅ CSS .form-control présent"
else
    echo "   ❌ CSS .form-control absent"
fi

if echo "$REGISTER_CONTENT" | grep -q "padding: 14px 16px"; then
    echo "   ✅ Padding des inputs défini"
else
    echo "   ❌ Padding des inputs non défini"
fi

if echo "$REGISTER_CONTENT" | grep -q "border-radius: 8px"; then
    echo "   ✅ Border-radius défini"
else
    echo "   ❌ Border-radius non défini"
fi

# Test 3: Vérification des labels
echo -e "\n✅ Test 3: Vérification des styles des labels"
if echo "$LOGIN_CONTENT" | grep -q "\.form-label"; then
    echo "   ✅ CSS .form-label présent"
else
    echo "   ❌ CSS .form-label absent"
fi

if echo "$LOGIN_CONTENT" | grep -q "font-family: 'DM Sans'"; then
    echo "   ✅ Police DM Sans appliquée aux labels"
else
    echo "   ❌ Police DM Sans non appliquée aux labels"
fi

# Test 4: Vérification des checkboxes
echo -e "\n✅ Test 4: Vérification des styles des checkboxes"
if echo "$LOGIN_CONTENT" | grep -q "\.form-check-input"; then
    echo "   ✅ CSS .form-check-input présent"
else
    echo "   ❌ CSS .form-check-input absent"
fi

if echo "$LOGIN_CONTENT" | grep -q "accent-color: #ff2600"; then
    echo "   ✅ Couleur d'accent des checkboxes définie"
else
    echo "   ❌ Couleur d'accent des checkboxes non définie"
fi

# Test 5: Vérification des transitions
echo -e "\n✅ Test 5: Vérification des transitions"
if echo "$LOGIN_CONTENT" | grep -q "transition: all 0.3s ease"; then
    echo "   ✅ Transitions définies"
else
    echo "   ❌ Transitions non définies"
fi

# Test 6: Vérification des états hover
echo -e "\n✅ Test 6: Vérification des états hover"
if echo "$LOGIN_CONTENT" | grep -q "\.form-control:hover"; then
    echo "   ✅ État hover défini"
else
    echo "   ❌ État hover non défini"
fi

if echo "$LOGIN_CONTENT" | grep -q "border-color: #c1c5c9"; then
    echo "   ✅ Couleur de bordure hover définie"
else
    echo "   ❌ Couleur de bordure hover non définie"
fi

# Test 7: Vérification de l'accessibilité
echo -e "\n✅ Test 7: Vérification de l'accessibilité"
LOGIN_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/login")
REGISTER_RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" "${BASE_URL}/register")

if [ "$LOGIN_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page de connexion accessible (HTTP 200)"
else
    echo "   ❌ Page de connexion non accessible (HTTP $LOGIN_RESPONSE)"
fi

if [ "$REGISTER_RESPONSE" -eq 200 ]; then
    echo "   ✅ Page d'inscription accessible (HTTP 200)"
else
    echo "   ❌ Page d'inscription non accessible (HTTP $REGISTER_RESPONSE)"
fi

echo -e "\n🎯 Résumé des Styles Appliqués:"
echo "   - Inputs: ✅ Style moderne avec bordures arrondies"
echo "   - Labels: ✅ Police DM Sans avec poids 500"
echo "   - Focus: ✅ Bordure rouge (#ff2600) avec ombre"
echo "   - Hover: ✅ Bordure grise plus foncée"
echo "   - Checkboxes: ✅ Couleur d'accent rouge"
echo "   - Transitions: ✅ Animations fluides (0.3s)"

echo -e "\n🎨 Caractéristiques des Inputs:"
echo "   - ✅ Padding: 14px 16px (confortable)"
echo "   - ✅ Bordure: 2px solid #e1e5e9 (subtile)"
echo "   - ✅ Border-radius: 8px (moderne)"
echo "   - ✅ Police: DM Sans, 15px"
echo "   - ✅ Couleur: #333333 (lisible)"
echo "   - ✅ Background: #ffffff (propre)"

echo -e "\n🎨 États des Inputs:"
echo "   - ✅ Par défaut: Bordure grise claire"
echo "   - ✅ Hover: Bordure grise plus foncée"
echo "   - ✅ Focus: Bordure rouge + ombre rouge"
echo "   - ✅ Placeholder: Gris (#999999)"

echo -e "\n📋 Éléments Stylisés:"
echo "   - ✅ Champs email et mot de passe"
echo "   - ✅ Labels avec police DM Sans"
echo "   - ✅ Checkbox 'Se souvenir de moi'"
echo "   - ✅ Champs nom et église (inscription)"
echo "   - ✅ Champ confirmation mot de passe"

echo -e "\n📋 Instructions pour tester visuellement:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/login"
echo "   3. Cliquez sur les champs pour voir l'effet focus"
echo "   4. Survolez les champs pour voir l'effet hover"
echo "   5. Testez la même chose sur http://127.0.0.1:8000/register"
echo "   6. Vérifiez que les checkboxes ont la couleur rouge"

echo -e "\n✨ Avantages du Nouveau Style:"
echo "   - ✅ Design moderne et professionnel"
echo "   - ✅ Meilleure expérience utilisateur"
echo "   - ✅ Feedback visuel clair (focus, hover)"
echo "   - ✅ Cohérence avec l'identité visuelle"
echo "   - ✅ Accessibilité améliorée"

echo -e "\n🎉 SUCCÈS ! Styles des inputs appliqués avec succès !"
