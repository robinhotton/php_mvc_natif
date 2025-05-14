# PHP MVC Docker Environment

Un environnement de développement Docker complet pour créer une application PHP MVC native avec MariaDB, sans nécessiter d'installation locale de PHP.

## 📋 Présentation

Ce projet fournit une structure prête à l'emploi pour développer une application PHP suivant le pattern MVC (Modèle-Vue-Contrôleur). L'ensemble de l'environnement est containerisé avec Docker, ce qui vous permet de :

- Développer sans installer PHP, Apache ou MariaDB localement
- Avoir un environnement de développement uniforme et reproductible
- Démarrer rapidement avec une structure MVC basique mais fonctionnelle
- Bénéficier d'une base de données MariaDB préconfigurée

## 🔧 Technologies utilisées

- **PHP 8.2** avec extensions PDO et MySQLi
- **Apache** avec mod_rewrite pour un routage propre
- **MariaDB 10.7** comme base de données
- **phpMyAdmin** pour la gestion de la base de données
- Architecture **MVC** légère et native

## 🚀 Installation et démarrage

### Prérequis

- Docker et Docker Compose installés sur votre machine
- Git (optionnel, pour cloner le dépôt)

### Étapes d'installation

1. Clonez ce dépôt ou téléchargez-le :
   ```bash
   git clone <URL-du-repo>
   cd <nom-du-repo>
   ```

2. Vérifiez que la structure des fichiers est correcte :
   ```
   votre-projet/
   ├── Dockerfile
   ├── docker-compose.yml
   ├── README.md
   ├── database/
   │   └── init/
   │       └── 001-init.sql
   └── src/
       ├── config/
       │   └── Database.php
       ├── controllers/
       │   └── HomeController.php
       ├── models/
       │   └── User.php
       ├── views/
       │   ├── home.php
       │   └── layout.php
       ├── public/
       │   ├── index.php
       │   └── .htaccess
       └── .htaccess
   ```

3. Lancez l'environnement avec Docker Compose :
   ```bash
   docker-compose up -d
   ```

4. Attendez quelques instants que les conteneurs démarrent complètement.

### Accès à l'application

- **Application MVC** : [http://localhost](http://localhost)
- **phpMyAdmin** : [http://localhost:8080](http://localhost:8080)
  - Utilisateur : `mvc_user`
  - Mot de passe : `mvc_password`

## 📁 Structure du projet

Le projet suit une architecture MVC simple :

- **Modèles** (`/src/models/`) : Représentent les données et la logique métier
- **Vues** (`/src/views/`) : Gèrent l'affichage et l'interface utilisateur
- **Contrôleurs** (`/src/controllers/`) : Traitent les requêtes et orchestrent le flux
- **Configuration** (`/src/config/`) : Contient les fichiers de configuration

### Routage

Le routage suit le format : `http://localhost/controller/action/param1/param2`

- Si aucun contrôleur n'est spécifié, le contrôleur par défaut est `Home`
- Si aucune action n'est spécifiée, l'action par défaut est `index`

## 💻 Développement

### Modification des fichiers

Les fichiers sources se trouvent dans le répertoire `src/`. Vous pouvez les modifier directement sur votre machine hôte - les changements seront automatiquement reflétés dans le conteneur Docker grâce au volume configuré.

### Base de données

Un script d'initialisation (`database/init/001-init.sql`) est exécuté lors du premier démarrage pour créer une table `users` avec des données d'exemple.

Pour ajouter de nouvelles tables ou modifier le schéma :
1. Modifiez le fichier SQL dans `database/init/`
2. Redémarrez les conteneurs avec `docker-compose down && docker-compose up -d`
   - Ou utilisez phpMyAdmin pour les modifications directes

## 🧰 Gestion des conteneurs

### Commandes utiles

- **Démarrer l'environnement** : `docker-compose up -d`
- **Arrêter l'environnement** : `docker-compose down`
- **Voir les logs** : `docker-compose logs -f`
- **Reconstruire les images** : `docker-compose build --no-cache`
- **Ouvrir un terminal dans le conteneur PHP** : `docker-compose exec php bash`

## 🛠️ Personnalisation

### Ajouter des extensions PHP

1. Modifiez le `Dockerfile` pour ajouter de nouvelles extensions PHP
2. Reconstruisez l'image : `docker-compose build --no-cache`

### Modifier la configuration de la base de données

Changez les variables d'environnement dans le fichier `docker-compose.yml` sous les services `mariadb` et `php`.

## 📝 Notes supplémentaires

- Les fichiers `.htaccess` sont configurés pour permettre des URL propres et le routage MVC
- Le projet est configuré pour un environnement de développement. Pour la production, des modifications de sécurité seraient nécessaires.
- Tous les fichiers sont accessibles directement depuis votre éditeur de code préféré, sans avoir à se connecter aux conteneurs.

## 🚨 Résolution des problèmes courants

- **L'application n'est pas accessible** : Vérifiez que les conteneurs sont en cours d'exécution avec `docker ps`
- **Erreur de connexion à la base de données** : Assurez-vous que les identifiants correspondent dans `docker-compose.yml` et `src/config/Database.php`
- **Modifications de fichiers non prises en compte** : Certains serveurs peuvent nécessiter un redémarrage du conteneur : `docker-compose restart php`
