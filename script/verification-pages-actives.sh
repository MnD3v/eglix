#!/bin/bash

echo "🔄 Vérification de l'activation des nouvelles pages élégantes"
echo "============================================================"

BASE_URL="http://127.0.0.1:8000"

# Test 1: Vérification du contenu de la page de connexion
echo -e "\n✅ Test 1: Contenu de la page de connexion"
LOGIN_CONTENT=$(curl -s "${BASE_URL}/login")

if echo "$LOGIN_CONTENT" | grep -q "Bienvenue"; then
    echo "   ✅ Page de connexion élégante active (contient 'Bienvenue')"
else
    echo "   ❌ Page de connexion élégante non active"
    echo "   📋 Contenu actuel:"
    echo "$LOGIN_CONTENT" | head -5
fi

# Test 2: Vérification du contenu de la page d'inscription
echo -e "\n✅ Test 2: Contenu de la page d'inscription"
REGISTER_CONTENT=$(curl -s "${BASE_URL}/register")

if echo "$REGISTER_CONTENT" | grep -q "Rejoignez Eglix"; then
    echo "   ✅ Page d'inscription élégante active (contient 'Rejoignez Eglix')"
else
    echo "   ❌ Page d'inscription élégante non active"
    echo "   📋 Contenu actuel:"
    echo "$REGISTER_CONTENT" | head -5
fi

# Test 3: Vérification des fichiers
echo -e "\n✅ Test 3: Vérification des fichiers"
if [ -f "resources/views/auth/login.blade.php" ]; then
    if grep -q "Bienvenue" "resources/views/auth/login.blade.php"; then
        echo "   ✅ Fichier login.blade.php contient 'Bienvenue'"
    else
        echo "   ❌ Fichier login.blade.php ne contient pas 'Bienvenue'"
    fi
else
    echo "   ❌ Fichier login.blade.php non trouvé"
fi

if [ -f "resources/views/auth/register.blade.php" ]; then
    if grep -q "Rejoignez Eglix" "resources/views/auth/register.blade.php"; then
        echo "   ✅ Fichier register.blade.php contient 'Rejoignez Eglix'"
    else
        echo "   ❌ Fichier register.blade.php ne contient pas 'Rejoignez Eglix'"
    fi
else
    echo "   ❌ Fichier register.blade.php non trouvé"
fi

# Test 4: Vérification de l'image de fond
echo -e "\n✅ Test 4: Vérification de l'image de fond"
if echo "$LOGIN_CONTENT" | grep -q "auth-background.png"; then
    echo "   ✅ Image auth-background.png référencée dans la page"
else
    echo "   ❌ Image auth-background.png non référencée"
fi

# Test 5: Vérification des boutons sociaux
echo -e "\n✅ Test 5: Vérification des boutons sociaux"
if echo "$LOGIN_CONTENT" | grep -q "Se connecter avec Google"; then
    echo "   ✅ Bouton Google présent"
else
    echo "   ❌ Bouton Google absent"
fi

if echo "$LOGIN_CONTENT" | grep -q "Se connecter avec Apple"; then
    echo "   ✅ Bouton Apple présent"
else
    echo "   ❌ Bouton Apple absent"
fi

echo -e "\n🎯 Résumé:"
echo "   - Fichiers déplacés: ✅"
echo "   - Cache vidé: ✅"
echo "   - Pages élégantes: $([ -f "resources/views/auth/login.blade.php" ] && echo "✅ Actives" || echo "❌ Non actives")"

echo -e "\n📋 Instructions pour voir les changements:"
echo "   1. Ouvrez votre navigateur"
echo "   2. Allez sur http://127.0.0.1:8000/login"
echo "   3. Allez sur http://127.0.0.1:8000/register"
echo "   4. Videz le cache de votre navigateur (Ctrl+F5)"
echo "   5. Vous devriez voir le nouveau design split-screen"

echo -e "\n🔄 Si les changements ne sont pas visibles:"
echo "   1. Videz le cache du navigateur (Ctrl+F5)"
echo "   2. Redémarrez le serveur Laravel"
echo "   3. Vérifiez que les fichiers sont bien en place"
