<?php

class HomeController extends BaseController {
    public function index() {
        // Données pour la vue
        $data = [
            'title' => 'Accueil',
            'message' => 'Bienvenue sur votre application MVC avec Docker!'
        ];
        
        // Rendre la vue
        $this->render('home.php', $data);
    }
}