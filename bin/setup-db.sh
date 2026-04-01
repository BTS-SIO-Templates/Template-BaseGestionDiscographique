#!/bin/bash

# Couleurs pour le feedback terminal
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== Initialisation de l'environnement Symfony ===${NC}"


# 1. Récupération des identifiants si la base n'est pas encore créée
if grep -q "mysql://root:root" .env || ! grep -q "DATABASE_URL" .env; then
    read -p "Entrez votre nom d'utilisateur SGBD : " DB_USER
    read -p "Entrez le nom de votre base de données (par défaut: TPsymfony) : " DB_BD
    DB_BD=${DB_BD:-TPsymfony}
    read -s -p "Entrez votre mot de passe SGBD : " DB_PASS
    echo -e "\n"
    # Construction du nom de la base de données
    DB_NAME="${DB_USER}_${DB_BD}"
    
    # 2. Mise à jour dynamique du .env
    # Nous utilisons '|' comme séparateur pour éviter les conflits avec les '/' de l'URL
    if [ -f .env ]; then
        sed -i "s|DATABASE_URL=.*|DATABASE_URL=\"mysql://$DB_USER:$DB_PASS@btssio.dedyn.io:3306/$DB_NAME?serverVersion=8.0.32\"|" .env
        echo -e "${GREEN}[OK]${NC} Fichier .env configuré pour la base : $DB_NAME"
    else
        echo -e "Erreur : Fichier .env introuvable."
        exit 1
    fi
else
    echo "--- Configuration .env déjà existante. Passage à l'étape suivante. ---"
fi

# 3. Exécution des commandes Symfony
echo -e "${BLUE}Installation des dépendances (composer)...${NC}"
composer install
# 4 Création de la BDD si elle n'existe pas
echo -e "${BLUE}Création de la base de données...${NC}"
php bin/console doctrine:database:create --if-not-exists

# 5. Protection des données existantes pour les fixtures
# On demande confirmation si on détecte que ce n'est pas la première fois
if [ -f .setup_done ]; then
    read -p "Voulez-vous réinitialiser les données (Fixtures) ? [y/N] " CONFIRM
    if [[ "$CONFIRM" =~ ^[yY]$ ]]; then
        echo -e "${BLUE}Mise à jour du schéma...${NC}"
        php bin/console doctrine:schema:update --force --complete
        echo -e "${BLUE}Chargement des fixtures...${NC}"
        php bin/console doctrine:fixtures:load --no-interaction
    fi
else
    # Premier lancement
    echo -e "${BLUE}Mise à jour du schéma...${NC}"
    php bin/console doctrine:schema:update --force --complete
    echo -e "${BLUE}Chargement des fixtures...${NC}"
    php bin/console doctrine:fixtures:load --no-interaction
    touch .setup_done # On crée un fichier témoin (à ne pas commiter)
fi

echo -e "${GREEN}=== Configuration terminée avec succès ! ===${NC}"
