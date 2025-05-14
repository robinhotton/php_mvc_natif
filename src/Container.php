<?php

class Container
{
    private $services = [];
    private $instances = [];
    
    /**
     * Enregistre un service dans le container
     * 
     * @param string $name Nom du service
     * @param callable|string $factory Fabrique du service ou nom de classe
     * @param array $arguments Arguments pour le constructeur (optionnel)
     */
    public function register($name, $factory, array $arguments = [])
    {
        $this->services[$name] = [
            'factory' => $factory,
            'arguments' => $arguments
        ];
    }
    
    /**
     * Récupère un service du container
     * 
     * @param string $name Nom du service
     * @return mixed L'instance du service
     * @throws Exception Si le service n'existe pas
     */
    public function get($name)
    {
        // Si l'instance existe déjà, la retourner (singleton)
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }
        
        // Vérifier si le service a été enregistré
        if (!isset($this->services[$name])) {
            throw new Exception("Service '$name' not found in container");
        }
        
        $service = $this->services[$name];
        $factory = $service['factory'];
        $arguments = $service['arguments'];
        
        // Résoudre les dépendances récursivement
        $resolvedArguments = [];
        foreach ($arguments as $arg) {
            if (is_string($arg) && isset($this->services[$arg])) {
                $resolvedArguments[] = $this->get($arg);
            } else {
                $resolvedArguments[] = $arg;
            }
        }
        
        // Créer l'instance
        if (is_callable($factory)) {
            $instance = call_user_func_array($factory, $resolvedArguments);
        } else {
            // Si c'est juste un nom de classe, l'instancier
            if (!class_exists($factory)) {
                throw new Exception("Class '$factory' not found");
            }
            
            $reflector = new ReflectionClass($factory);
            $instance = empty($resolvedArguments) ? 
                $reflector->newInstance() : 
                $reflector->newInstanceArgs($resolvedArguments);
        }
        
        // Stocker l'instance pour les futures demandes
        $this->instances[$name] = $instance;
        
        return $instance;
    }
    
    /**
     * Vérifie si un service existe dans le container
     * 
     * @param string $name Nom du service
     * @return bool True si le service existe, false sinon
     */
    public function has($name)
    {
        return isset($this->services[$name]);
    }
}