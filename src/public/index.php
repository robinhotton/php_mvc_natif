<?php
// public/index.php - Version avec gestion des routes d'auth

// Définir la constante de chemin de base
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Démarrer la session pour les messages flash et l'authentification
session_start();

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Charger les fichiers de configuration et les classes essentielles
    require_once ROOT . 'config/Database.php';
    
    // Charger les repositories
    require_once ROOT . 'repositories/UserRepositoryInterface.php';
    require_once ROOT . 'repositories/UserRepository.php';
    
    // Charger les modèles
    require_once ROOT . 'models/User.php';
    
    // Charger les contrôleurs
    require_once ROOT . 'controllers/BaseController.php';
    require_once ROOT . 'controllers/HomeController.php';
    require_once ROOT . 'controllers/UserController.php';
    
    // Créer les services et contrôleurs d'authentification si nécessaire
    $createAuthController = false;
    
    // Créer les instances nécessaires
    $database = new Database();
    $userRepository = new UserRepository($database);

    // Créer le service d'authentification (déplacé ici pour le rendre disponible pour tous les contrôleurs)
    require_once ROOT . 'services/AuthService.php';
    $authService = new AuthService($userRepository);

    // Route simple
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    $url = rtrim($url, '/');
    $url = explode('/', $url);

    // Contrôleur par défaut
    $controller = 'Home';
    if (!empty($url[0])) {
        $controller = ucfirst($url[0]);
        
        // Correction pour le cas de "users" -> "User"
        if ($controller === 'Users') {
            $controller = 'User';
        }
        
        // Correction pour les routes d'authentification
        if ($controller === 'Login' || $controller === 'Register' || $controller === 'Logout') {
            $createAuthController = true;
            $controller = 'Auth';
            
            // Définir l'action en fonction de la route
            if ($url[0] === 'login') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $action = 'login';
                } else {
                    $action = 'loginForm';
                }
            } else if ($url[0] === 'register') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $action = 'register';
                } else {
                    $action = 'registerForm';
                }
            } else if ($url[0] === 'logout') {
                $action = 'logout';
            }
        }
    }

    // Action par défaut (si non définie pour l'authentification)
    if (!isset($action)) {
        $action = 'index';
        if (!empty($url[1])) {
            $action = $url[1];
        }
    }

    // Paramètres
    $params = array_slice($url, 2);

    // Si c'est une route d'authentification, créer le AuthController
    if ($createAuthController) {
        // Vérifier si le fichier AuthController.php existe
        $authControllerFile = ROOT . 'controllers/AuthController.php';
        if (!file_exists($authControllerFile)) {
            echo "Le contrôleur d'authentification n'existe pas encore. Veuillez d'abord créer le fichier: " . $authControllerFile;
            exit;
        }
        
        // Charger le contrôleur d'authentification
        require_once $authControllerFile;
        
        // Vérifier si le fichier AuthService.php existe
        $authServiceFile = ROOT . 'services/AuthService.php';
        if (!file_exists($authServiceFile)) {
            // Créer le dossier services s'il n'existe pas
            if (!is_dir(ROOT . 'services')) {
                mkdir(ROOT . 'services', 0755, true);
            }
            
            echo "Le service d'authentification n'existe pas encore. Veuillez d'abord créer le fichier: " . $authServiceFile;
            exit;
        }
        
        // Charger le service d'authentification
        require_once $authServiceFile;
        
        // Créer le service d'authentification
        $authService = new AuthService($userRepository);
        
        // Créer le contrôleur d'authentification
        $controllerObj = new AuthController($authService);
        
        // Exécuter l'action
        if (method_exists($controllerObj, $action)) {
            call_user_func_array([$controllerObj, $action], $params);
        } else {
            echo "Action non trouvée dans le contrôleur d'authentification: " . $action;
        }
    } else {
        // Charger le contrôleur normal
        $controllerFile = ROOT . 'controllers/' . $controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            // Le fichier est déjà chargé plus haut, pas besoin de le recharger
            $controllerClass = $controller . 'Controller';
            
            // Créer les instances avec toutes les dépendances nécessaires
            if ($controllerClass === 'UserController') {
                $controllerObj = new $controllerClass($userRepository, $authService);
            } else if ($controllerClass === 'HomeController') {
                $controllerObj = new $controllerClass();
            } else {
                // Pour d'autres contrôleurs, vous devrez peut-être adapter cette partie
                // selon leurs constructeurs spécifiques
                $controllerObj = new $controllerClass();
            }
            
            if (method_exists($controllerObj, $action)) {
                call_user_func_array([$controllerObj, $action], $params);
            } else {
                echo "Action non trouvée: " . $action;
            }
        } else {
            echo "Contrôleur non trouvé: " . $controllerFile;
        }
    }
} catch (Exception $e) {
    // Afficher les erreurs en mode développement
    echo "<h1>Erreur de l'application</h1>";
    echo "<p>Message: " . $e->getMessage() . "</p>";
    echo "<p>Fichier: " . $e->getFile() . ", Ligne: " . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}