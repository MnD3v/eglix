#!/bin/bash

# Script de test de sécurité pour les formulaires Eglix
# Vérifie que tous les formulaires sont sécurisés en production

echo "🔒 TEST DE SÉCURITÉ DES FORMULAIRES - Eglix"
echo "=============================================="

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction pour tester une URL
test_form_security() {
    local url=$1
    local form_name=$2
    
    echo -e "\n${YELLOW}Test: $form_name${NC}"
    echo "URL: $url"
    
    # Test 1: Vérifier les headers de sécurité
    echo "1. Vérification des headers de sécurité..."
    headers=$(curl -s -I "$url" 2>/dev/null)
    
    if echo "$headers" | grep -q "Strict-Transport-Security"; then
        echo -e "   ✅ ${GREEN}HSTS activé${NC}"
    else
        echo -e "   ❌ ${RED}HSTS manquant${NC}"
    fi
    
    if echo "$headers" | grep -q "X-Frame-Options"; then
        echo -e "   ✅ ${GREEN}X-Frame-Options présent${NC}"
    else
        echo -e "   ❌ ${RED}X-Frame-Options manquant${NC}"
    fi
    
    if echo "$headers" | grep -q "Content-Security-Policy"; then
        echo -e "   ✅ ${GREEN}CSP configuré${NC}"
    else
        echo -e "   ❌ ${RED}CSP manquant${NC}"
    fi
    
    # Test 2: Vérifier la présence du token CSRF
    echo "2. Vérification du token CSRF..."
    page_content=$(curl -s "$url" 2>/dev/null)
    
    if echo "$page_content" | grep -q 'name="csrf-token"'; then
        echo -e "   ✅ ${GREEN}Token CSRF présent${NC}"
    else
        echo -e "   ❌ ${RED}Token CSRF manquant${NC}"
    fi
    
    # Test 3: Vérifier HTTPS
    echo "3. Vérification HTTPS..."
    if [[ $url == https://* ]]; then
        echo -e "   ✅ ${GREEN}HTTPS activé${NC}"
    else
        echo -e "   ❌ ${RED}HTTP détecté${NC}"
    fi
    
    # Test 4: Vérifier les cookies sécurisés
    echo "4. Vérification des cookies sécurisés..."
    if echo "$headers" | grep -q "Set-Cookie.*Secure"; then
        echo -e "   ✅ ${GREEN}Cookies sécurisés${NC}"
    else
        echo -e "   ⚠️  ${YELLOW}Cookies non sécurisés (normal en développement)${NC}"
    fi
}

# URLs à tester (remplacer par vos vraies URLs)
BASE_URL="https://eglix.lafia.tech"

echo "Test des formulaires sur: $BASE_URL"
echo ""

# Test des différents formulaires
test_form_security "$BASE_URL/members/create" "Formulaire d'ajout de membre"
test_form_security "$BASE_URL/tithes/create" "Formulaire d'ajout de dîme"
test_form_security "$BASE_URL/donations/create" "Formulaire d'ajout de don"
test_form_security "$BASE_URL/offerings/create" "Formulaire d'ajout d'offrande"
test_form_security "$BASE_URL/expenses/create" "Formulaire d'ajout de dépense"
test_form_security "$BASE_URL/services/create" "Formulaire de planification de culte"
test_form_security "$BASE_URL/events/create" "Formulaire d'ajout d'événement"
test_form_security "$BASE_URL/projects/create" "Formulaire d'ajout de projet"

echo ""
echo "=============================================="
echo "🔒 TEST TERMINÉ"
echo ""
echo "Si tous les tests montrent ✅, vos formulaires sont sécurisés !"
echo "Si des tests montrent ❌, vérifiez la configuration de sécurité."
echo ""
echo "Pour plus de détails, consultez les logs Laravel :"
echo "tail -f storage/logs/laravel.log"
