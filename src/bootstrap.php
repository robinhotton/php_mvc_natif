<?php

// Créer un container de dépendances
$container = new Container();

// Enregistrer les services
$container->register('Database', function() {
    return new Database();
});

$container->register('UserRepositoryInterface', function($database) {
    return new UserRepository($database);
}, ['Database']);

$container->register('HomeController', function() {
    return new HomeController();
});

$container->register('UserController', function($userRepository) {
    return new UserController($userRepository);
}, ['UserRepositoryInterface']);

// Exposer les repositories comme variables globales pour la compatibilité avec le router
$userRepository = $container->get('UserRepositoryInterface');

return $container;