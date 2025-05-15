<?php

class HomeController extends BaseController {
    public function index() {
        // Données pour la vue
        $data = [
            'title' => 'PHP MVC Docker',
            'message' => 'Un environnement de développement complet pour vos applications PHP'
        ];
        
        // Rendre la vue
        $this->render('home.php', $data);
    }
}