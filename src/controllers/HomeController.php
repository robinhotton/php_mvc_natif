<?php
class HomeController {
    public function index() {
        // Exemple simple
        $title = 'Accueil';
        $message = 'Bienvenue sur votre application MVC avec Docker!';
        
        // Charger la vue
        require_once ROOT . 'views/layout.php';
        require_once ROOT . 'views/home.php';
    }
}
