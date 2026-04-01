

## 🚀 Étapes pour démarrer

Bienvenue ! Pour configurer votre base de données et installer les dépendances, 
copiez-collez la commande suivante dans votre terminal :

```bash
./bin/setup-db.sh
```

### 1. Assistant de démarrage
la base de données sera créée sur le serveur du BTS à l'adresse btssio.dedyn.io.  

il vous sera demandé :

- votre nom d'utilisateur pour vous connecter au SGBDR MySql

- votre mot de passe pour vous connecter au SGBDR MySql

- le nom que vous voulez donner à votre base de données (sachant qu'il le prefixera automatique de votre nom d'utilisateur suivi d'un "_")

### 2. Que fait l'assistant de démarrage ?

- il lance le chargement des dépendance avec `composer install`
- il lance la création de la base de données avec `php bin/console doctrine:database:create` (sauf si elle existe déjà)
- il lance la création des tables de la base de données avec `php bin/console doctrine:schema:update --force --complete`
- il lance les fixtures si elles ne sont pas déja crées ou si vous le demandez avec `php bin/console doctrine:fixtures:load --no-interaction`

### 2. compte admin
l'admin peut se connecter avec son identifiant `admin@gmail.com` et son mot de passe `admin1234` afin d'avoir accès aux fonctionnalités d'administration (CRUD)


