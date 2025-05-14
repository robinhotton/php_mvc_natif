<?php

// Définition des routes de l'application
return function($router) {
    // Routes de la page d'accueil
    $router->get('', ['HomeController', 'index']);
    $router->get('home', ['HomeController', 'index']);
    
    // Routes pour les utilisateurs
    $router->get('users', ['UserController', 'index']);
    $router->get('users/create', ['UserController', 'create']);
    $router->post('users/store', ['UserController', 'store']);
    $router->get('users/show/:id', ['UserController', 'show']);
    $router->get('users/edit/:id', ['UserController', 'edit']);
    $router->post('users/update/:id', ['UserController', 'update']);
    $router->get('users/delete/:id', ['UserController', 'delete']);
    
    // Gestion des routes non trouvées (404)
    $router->notFound(function() {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>La page que vous avez demandée n'existe pas.</p>";
        echo "<p><a href='/'>Retour à l'accueil</a></p>";
    });
};