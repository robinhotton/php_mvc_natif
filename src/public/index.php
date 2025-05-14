<?php

// Définir la constante de chemin de base
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Démarrer la session pour les messages flash
session_start();

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// IMPORTANT: Charger explicitement la classe Database AVANT de l'utiliser
require_once ROOT . 'config/Database.php';

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
}

// Action par défaut
$action = 'index';
if (!empty($url[1])) {
    $action = $url[1];
}

// Paramètres
$params = array_slice($url, 2);

// Charger le contrôleur
$controllerFile = ROOT . 'controllers/' . $controller . 'Controller.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controller . 'Controller';
    
    // Créer le repository pour le contrôleur UserController
    if ($controllerClass === 'UserController') {
        // Charger les modèles et repositories nécessaires
        require_once ROOT . 'models/User.php';
        require_once ROOT . 'repositories/UserRepositoryInterface.php';
        require_once ROOT . 'repositories/UserRepository.php';
        
        // Créer les instances - La classe Database est chargée plus haut
        $database = new Database();
        $userRepository = new UserRepository($database);
        $controllerObj = new $controllerClass($userRepository);
    } else {
        // Pour les autres contrôleurs
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