#!/bin/bash

# Script de test pour le système d'inscription publique des membres
# Usage: ./test-public-registration.sh

echo "🧪 Test du système d'inscription publique des membres"
echo "=================================================="

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# URL de base
BASE_URL="http://127.0.0.1:8000"

echo -e "${BLUE}📋 Vérification des églises disponibles...${NC}"

# Vérifier que le serveur Laravel fonctionne
if ! curl -s "$BASE_URL" > /dev/null; then
    echo -e "${RED}❌ Le serveur Laravel n'est pas accessible sur $BASE_URL${NC}"
    echo -e "${YELLOW}💡 Assurez-vous que le serveur fonctionne avec: php artisan serve${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Serveur Laravel accessible${NC}"

# Liste des églises disponibles (basée sur la sortie précédente)
declare -A CHURCHES=(
    ["4"]="AD - Dongoyo"
    ["5"]="Adventiste Dongoyo"
    ["6"]="AD - Dongoyo"
    ["7"]="DA Bohou"
    ["8"]="Eglise Dongoyo"
    ["11"]="Eglise Dongoyo"
    ["12"]="Catholique"
)

echo -e "\n${BLUE}🏛️ Églises disponibles pour les tests:${NC}"
for id in "${!CHURCHES[@]}"; do
    echo -e "   ID: ${id} - ${CHURCHES[$id]}"
done

echo -e "\n${BLUE}🔗 Génération des liens d'inscription publique:${NC}"
for id in "${!CHURCHES[@]}"; do
    url="$BASE_URL/members/create/$id"
    echo -e "   ${GREEN}${CHURCHES[$id]}${NC}: ${YELLOW}$url${NC}"
done

echo -e "\n${BLUE}🧪 Test des URLs d'inscription...${NC}"

# Tester quelques URLs d'inscription
test_church_ids=("4" "5" "7" "12")

for church_id in "${test_church_ids[@]}"; do
    url="$BASE_URL/members/create/$church_id"
    church_name="${CHURCHES[$church_id]}"
    
    echo -e "\n${BLUE}🔍 Test de l'église: $church_name (ID: $church_id)${NC}"
    
    # Tester l'accès à la page d'inscription
    response=$(curl -s -o /dev/null -w "%{http_code}" "$url")
    
    if [ "$response" = "200" ]; then
        echo -e "   ${GREEN}✅ Page d'inscription accessible${NC}"
        echo -e "   ${YELLOW}   URL: $url${NC}"
    elif [ "$response" = "404" ]; then
        echo -e "   ${RED}❌ Église non trouvée (ID: $church_id)${NC}"
    else
        echo -e "   ${RED}❌ Erreur HTTP: $response${NC}"
    fi
done

echo -e "\n${BLUE}📝 Exemple de données de test pour l'inscription:${NC}"
cat << 'EOF'
{
  "first_name": "Jean",
  "last_name": "Dupont",
  "email": "jean.dupont@example.com",
  "phone": "+237 123 456 789",
  "address": "123 Rue de la Paix, Yaoundé",
  "gender": "male",
  "marital_status": "married",
  "birth_date": "1985-06-15",
  "baptized_at": "2000-08-20",
  "baptism_responsible": "Pasteur Martin",
  "joined_at": "2024-01-01",
  "notes": "Membre actif de la communauté"
}
EOF

echo -e "\n${BLUE}🚀 Instructions pour tester manuellement:${NC}"
echo -e "1. ${YELLOW}Ouvrez votre navigateur${NC}"
echo -e "2. ${YELLOW}Allez sur une des URLs d'inscription ci-dessus${NC}"
echo -e "3. ${YELLOW}Remplissez le formulaire avec les données de test${NC}"
echo -e "4. ${YELLOW}Soumettez le formulaire${NC}"
echo -e "5. ${YELLOW}Vérifiez la page de confirmation${NC}"

echo -e "\n${BLUE}🔧 Commandes utiles:${NC}"
echo -e "• ${YELLOW}Voir les membres inscrits:${NC} php artisan tinker --execute=\"App\\Models\\Member::with('church')->get()->each(function(\$m) { echo \\\"{\$m->first_name} {\$m->last_name} - {\$m->church->name}\\\" . PHP_EOL; });\""
echo -e "• ${YELLOW}Voir les églises:${NC} php artisan tinker --execute=\"App\\Models\\Church::all(['id', 'name', 'is_active'])->each(function(\$c) { echo \\\"ID: {\$c->id}, Nom: {\$c->name}, Actif: \" . (\$c->is_active ? 'Oui' : 'Non') . \\\"\\\" . PHP_EOL; });\""

echo -e "\n${GREEN}✅ Test terminé!${NC}"
echo -e "${BLUE}📚 Consultez SYSTEME_INSCRIPTION_PUBLIQUE.md pour plus d'informations${NC}"
