# PHP MVC Docker Environment

Un environnement de développement Docker complet pour créer une application PHP MVC native avec MariaDB, sans nécessiter d'installation locale de PHP et avec hot-reload activé.

## 📋 Présentation

Ce projet fournit une structure prête à l'emploi pour développer une application PHP suivant le pattern MVC (Modèle-Vue-Contrôleur). L'ensemble de l'environnement est containerisé avec Docker, ce qui vous permet de :

- Développer sans installer PHP, Apache ou MariaDB localement
- Bénéficier du hot-reload pour voir vos modifications sans redémarrer les conteneurs
- Avoir un environnement de développement uniforme et reproductible
- Démarrer rapidement avec une structure MVC basique mais fonctionnelle
- Bénéficier d'une base de données MariaDB préconfigurée
- Utiliser le pattern Repository pour une meilleure séparation des responsabilités

## 🔧 Technologies utilisées

- **PHP 8.2** avec extensions PDO et MySQLi
- **Apache** avec mod_rewrite pour un routage propre
- **MariaDB 10.7** comme base de données
- **phpMyAdmin** pour la gestion de la base de données
- Architecture **MVC** légère et native
- **Pattern Repository** pour l'accès aux données
- **Hot-reload** pour le développement sans interruption

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

2. La structure des fichiers est organisée comme suit :
   ```
   votre-projet/
   ├── Dockerfile
   ├── docker-compose.yml
   ├── apache-custom.conf      # Configuration Apache pour le hot-reload
   ├── README.md
   ├── database/
   │   └── init/
   │       └── 001-init.sql    # Script d'initialisation de la base de données
   └── src/
       ├── config/
       │   └── Database.php    # Configuration de la base de données
       ├── controllers/
       │   ├── BaseController.php  # Contrôleur de base avec système de template
       │   ├── HomeController.php  # Contrôleur pour la page d'accueil
       │   └── UserController.php  # Contrôleur pour la gestion des utilisateurs
       ├── models/
       │   └── User.php        # Modèle d'utilisateur
       ├── repositories/
       │   ├── UserRepositoryInterface.php  # Interface du repository
       │   └── MysqlUserRepository.php      # Implémentation MySQL du repository
       ├── routes/
       │   ├── Router.php      # Système de routage
       │   └── routes.php      # Définition des routes
       ├── views/
       │   ├── layout.php      # Layout principal
       │   ├── home.php        # Vue pour la page d'accueil
       │   └── users/          # Vues pour la gestion des utilisateurs
       │       ├── index.php
       │       ├── show.php
       │       ├── create.php
       │       └── edit.php
       ├── public/
       │   ├── assets/
       │   │   ├── css/
       │   │   │   └── style.css  # Styles CSS centralisés
       │   │   ├── js/
       │   │   └── img/
       │   ├── index.php       # Point d'entrée de l'application
       │   └── .htaccess       # Configuration des URLs propres
       └── .htaccess           # Redirection vers public/
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

## 📁 Architecture du projet

Le projet suit une architecture MVC améliorée avec le pattern Repository :

- **Modèles** (`/src/models/`) : Représentent les données et la logique métier
- **Vues** (`/src/views/`) : Gèrent l'affichage et l'interface utilisateur
- **Contrôleurs** (`/src/controllers/`) : Traitent les requêtes et orchestrent le flux
- **Repositories** (`/src/repositories/`) : Gèrent l'accès aux données
- **Routes** (`/src/routes/`) : Système de routage centralisé
- **Configuration** (`/src/config/`) : Contient les fichiers de configuration
- **Assets** (`/src/public/assets/`) : Ressources statiques (CSS, JS, images)

### Système de routage

Le routage est centralisé dans le fichier `src/routes/routes.php` et suit le format :
```php
$router->get('users', ['UserController', 'index']);
$router->get('users/show/:id', ['UserController', 'show']);
```

Les paramètres dynamiques sont spécifiés avec `:` (exemple : `:id`).

## 💻 Développement avec hot-reload

### Modification des fichiers

Les fichiers sources se trouvent dans le répertoire `src/`. Vous pouvez les modifier directement sur votre machine hôte et les changements seront automatiquement reflétés sans avoir à redémarrer les conteneurs.

- **PHP** : Modifications prises en compte immédiatement
- **CSS/JS** : Rechargement automatique grâce à la configuration Apache
- **Vues** : Mises à jour instantanément lors du rafraîchissement du navigateur

### Base de données

Un script d'initialisation (`database/init/001-init.sql`) est exécuté lors du premier démarrage pour créer une table `users` avec des données d'exemple.

Pour ajouter de nouvelles tables ou modifier le schéma :
1. Modifiez le fichier SQL dans `database/init/`
2. Redémarrez les conteneurs avec `docker-compose down && docker-compose up -d`
   - Ou utilisez phpMyAdmin pour les modifications directes

## 🧰 Gestion des conteneurs

- **Démarrer l'environnement** : `docker-compose up -d`
- **Démarrer avec logs** : `docker-compose up`
- **Arrêter l'environnement** : `docker-compose down`
- **Voir les logs** : `docker-compose logs -f`
- **Reconstruire les images** : `docker-compose build --no-cache`
- **Redémarrer un service** : `docker-compose restart php`
- **Ouvrir un terminal dans le conteneur PHP** : `docker-compose exec php bash`

## 🛠️ Personnalisation

### Ajouter des extensions PHP

1. Modifiez le `Dockerfile` pour ajouter de nouvelles extensions PHP
2. Reconstruisez l'image : `docker-compose build --no-cache`

### Modifier la configuration de la base de données

Changez les variables d'environnement dans le fichier `docker-compose.yml` sous les services `mariadb` et `php`.

### Ajouter des routes

Modifiez le fichier `src/routes/routes.php` pour ajouter de nouvelles routes.