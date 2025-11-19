

## 🚀 Étapes pour démarrer

### 1. Ouvrir le projet
- Cliquez sur le bouton **“Open in Codespaces”** dans GitHub.  
- Attendez quelques minutes : l’environnement PHP 8.2 + Composer + symfonyCli sera automatiquement configuré.  
- Installer les dépendances Symfony en ligne de commande en tapant `composer install`.

---

### 2. Configurer la base de données
la base de données est hébergée sur le serveur du BTS à l'adresse btssio.dedyn.io.  

- Créer un fichier `.env.local` à la racine du projet en y copiant cette ligne :
`DATABASE_URL="mysql://votrenom:votremdp@btssio.dedyn.io:3306/votrenom_GestionMusiqueLabel?serverVersion=5.7.33&charset=utf8mb4` 
en remplacant "votrenom" par votre identifiant sur le serveur Mysql du lycée et "votremdp" par votre mot de passe sur ce serveur.

- Créez la base de données sur votre serveur Mysql en tapant en ligne de commande `php bin/console doctrine:database:create` (la base de données créée aura pour nom **votrenom_GestionMusiqueLabel**)

- Créer la structures de la base (les tables et leurs relations) en tapant en ligne de commande : `php bin/console doctrine:schema:update --force` (vérifiez le résultat)

- Lancez les fixtures afin d'alimenter la base en tapant en ligne de commande : `php bin/console doctrine:fixtures:load`

### 3. compte admin
l'admin peut se connecter avec son identifiant `admin@gmail.com` et son mot de passe `admin1234` afin d'avoir accès aux fonctionnalités d'administration (CRUD)


