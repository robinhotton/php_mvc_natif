<?php

// Définir la constante ROOT
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Démarrer la session pour les messages flash
session_start();

// Ajouter après le chargement de la base de données
require_once ROOT . 'config/Database.php';

// Charger User
require_once ROOT . 'models/User.php';
require_once ROOT . 'repositories/UserRepositoryInterface.php';
require_once ROOT . 'repositories/UserRepository.php';
require_once ROOT . 'controllers/UserController.php';

// Créer les instances nécessaires
$database = new Database();
$userRepository = new UserRepository($database);

// Routage
$url = isset($_GET['url']) ? $_GET['url'] : '';
$url = rtrim($url, '/');
$url = explode('/', $url);

// Contrôleur par défaut
$controller = 'Home';
if (!empty($url[0])) {
    $controller = ucfirst($url[0]);
}

// Action par défaut
$action = 'index';
if (!empty($url[1])) {
    $action = $url[1];
}

// Paramètres
$params = array_slice($url, 2);

// Charger le contrôleur approprié
switch ($controller) {
    case 'Users':
        $controllerObj = new UserController($userRepository);
        break;
    // Ajouter d'autres contrôleurs ici avec leurs dépendances
    default:
        $controllerFile = ROOT . 'controllers/' . $controller . 'Controller.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = $controller . 'Controller';
            $controllerObj = new $controllerClass();
        } else {
            echo "Contrôleur non trouvé";
            exit;
        }
}

// Appeler l'action avec les paramètres
if (method_exists($controllerObj, $action)) {
    call_user_func_array([$controllerObj, $action], $params);
} else {
    echo "Action non trouvée";
}