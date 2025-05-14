<?php
// Définir la constante de chemin de base
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Charger les fichiers de configuration et les classes
require_once ROOT . 'config/Database.php';

// Route simple
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

// Charger le contrôleur
$controllerFile = ROOT . 'controllers/' . $controller . 'Controller.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controller . 'Controller';
    $controllerObj = new $controllerClass();
    
    if (method_exists($controllerObj, $action)) {
        call_user_func_array([$controllerObj, $action], $params);
    } else {
        echo "Action non trouvée";
    }
} else {
    echo "Contrôleur non trouvé";
}