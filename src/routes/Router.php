<?php

class Router
{
    private $routes = [];
    private $notFoundCallback;
    
    public function get($route, $callback)
    {
        $this->addRoute('GET', $route, $callback);
    }
    
    public function post($route, $callback)
    {
        $this->addRoute('POST', $route, $callback);
    }
    
    private function addRoute($method, $route, $callback)
    {
        $route = trim($route, '/');
        $this->routes[$method][$route] = $callback;
    }
    
    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }
    
    public function run()
    {
        // Récupérer la méthode HTTP
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Récupérer l'URI et enlever les query params
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = trim($requestUri, '/');
        
        // Debug - Afficher la route et les routes disponibles
        /*
        echo "<pre>Route demandée: " . $route . "\n\n";
        echo "Routes disponibles:\n";
        print_r($this->routes[$method]);
        echo "</pre>";
        */
        
        // Si la route n'existe pas directement
        $callback = isset($this->routes[$method][$route]) ? $this->routes[$method][$route] : null;
        $params = [];
        
        // Si aucune route directe n'a été trouvée, cherchons une route avec des paramètres
        if (!$callback) {
            foreach ($this->routes[$method] as $pattern => $routeCallback) {
                // Convertir les paramètres nommés en regex
                $regexPattern = preg_replace('#:[a-zA-Z0-9]+#', '([^/]+)', $pattern);
                
                // Voir si le pattern correspond à l'URL demandée
                if (preg_match('#^' . $regexPattern . '$#', $route, $matches)) {
                    $callback = $routeCallback;
                    
                    // Extraire les paramètres (ignorer le premier match qui est l'URL complète)
                    $params = array_slice($matches, 1);
                    break;
                }
            }
        }
        
        // Si une route correspondante a été trouvée, exécuter la callback
        if ($callback) {
            if (is_callable($callback)) {
                call_user_func_array($callback, $params);
            } elseif (is_array($callback) && count($callback) === 2) {
                // Format : ['UserController', 'index']
                $controllerName = $callback[0];
                $methodName = $callback[1];
                
                // Instancier le contrôleur et appeler la méthode
                $controller = $this->resolveController($controllerName);
                call_user_func_array([$controller, $methodName], $params);
            }
            return;
        }
        
        // Aucune route correspondante n'a été trouvée
        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
    
    private function resolveController($controllerName)
    {
        // S'assurer que le contrôleur est chargé
        $controllerFile = ROOT . 'controllers/' . $controllerName . '.php';
        if (!class_exists($controllerName) && file_exists($controllerFile)) {
            require_once $controllerFile;
        }
        
        // Charger les modèles et repositories pour UserController
        if ($controllerName === 'UserController') {
            if (!class_exists('User')) {
                require_once ROOT . 'models/User.php';
            }
            if (!interface_exists('UserRepositoryInterface')) {
                require_once ROOT . 'repositories/UserRepositoryInterface.php';
            }
            if (!class_exists('UserRepository')) {
                require_once ROOT . 'repositories/UserRepository.php';
            }
            
            // Créer les instances
            $database = new Database();
            $userRepository = new UserRepository($database);
            return new UserController($userRepository);
        } else if ($controllerName === 'HomeController') {
            return new HomeController();
        } else {
            // Pour les autres contrôleurs
            return new $controllerName();
        }
    }
}