<?php

class BaseController {
    protected function render($viewPath, $data = []) {
        // Extraire les données pour qu'elles soient disponibles dans la vue
        extract($data);
        
        // Capturer le contenu de la vue
        ob_start();
        include ROOT . 'views/' . $viewPath;
        $content = ob_get_clean();
        
        // Rendre le layout avec le contenu de la vue
        require_once ROOT . 'views/layout.php';
    }
}