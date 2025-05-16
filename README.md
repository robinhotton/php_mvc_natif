# PHP MVC Docker Environment

Application PHP MVC native avec gestion des utilisateurs, authentification et pattern Repository, conteneurisée avec Docker.

## Prérequis

- **Docker** et **Docker Compose** installés sur votre machine (**docker desktop** pour windows)
- **Git** (optionnel, pour cloner le dépôt)

## 🚀 Installation rapide

```bash
git clone https://github.com/robinhotton/php_mvc_natif
cd php_mvc_natif
docker-compose up -d
```

📱 **Accès**: 
- Application: [http://localhost](http://localhost)
- phpMyAdmin: [http://localhost:8080](http://localhost:8080) (user: `mvc_user`, pass: `mvc_password`)

## 🔧 Stack technique

| Technologie      | Version/Description                   |
|------------------|---------------------------------------|
| PHP              | 8.2 avec PDO, MySQLi                  |
| Base de données  | MariaDB 10.7                         |
| Serveur          | Apache avec mod_rewrite               |
| Architecture     | MVC avec Pattern Repository           |
| Authentification | Système complet (login/register)      |
| Front-end        | HTML5, CSS3, Font Awesome             |
| Outils           | Docker, hot-reload, phpMyAdmin        |

## 📋 Fonctionnalités

- **Gestion des utilisateurs**: CRUD complet avec contrôle d'accès
- **Authentification**: Inscription, connexion/déconnexion sécurisées
- **Contrôle d'accès**: Gestion des rôles (admin/user)
- **UI**: Design moderne (responsive arrivera pas la suite)
- **Messages flash**: Feedback utilisateur sur une action action
- **Protection des données**: Hashage des mots de passe

## 📁 Structure du projet

```
php_mvc_natif/
├── database/
│   └── init/                    # Scripts d'initialisation SQL
├── src/
│   ├── config/                  # Configuration (BDD)
│   ├── controllers/             # Contrôleurs (Home, User, Auth)
│   ├── models/                  # Modèles de données
│   ├── public/                  # Point d'entrée et assets
│   │   ├── assets/
│   │   │   └── css/
│   │   └── index.php            # Front controller
│   ├── repositories/            # Accès aux données
│   ├── services/                # Services (Auth)
│   └── views/                   # Templates et vues
├── docker-compose.yml           # Configuration Docker
└── Dockerfile                   # Image PHP personnalisée
```

## 💡 Utilisation

### Comptes utilisateurs

| Login     | Mot de passe | Rôle      |
|-----------|--------------|-----------|
| admin     | password     | admin     |
| user      | password     | user      |
| rhotton   | password     | user      |
| ldold     | password     | user      |

### Contrôle d'accès

- **Liste des utilisateurs**: Accessible uniquement aux utilisateurs connectés
- **Détail utilisateur**: Visible par tous les utilisateurs connectés 
- **Modification**: Uniquement pour son propre profil (user) ou tous les profils (admin)
- **Suppression**: Réservée aux administrateurs

## 📝 Licence

MIT - Robin HOTTON pour Diginamic
