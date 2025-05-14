<?php

class UserController 
{
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function index()
    {
        // Récupérer tous les utilisateurs
        $users = $this->userRepository->findAll();
        
        // Charger la vue
        require_once ROOT . 'views/users/index.php';
    }
    
    public function show($id)
    {
        // Récupérer un utilisateur par son id
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Charger la vue
        require_once ROOT . 'views/users/show.php';
    }
    
    public function create()
    {
        // Afficher le formulaire de création
        require_once ROOT . 'views/users/create.php';
    }
    
    public function store()
    {
        // Création d'un nouvel utilisateur
        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        
        // Valider les données
        if (!$user->isValid()) {
            // Gérer l'erreur de validation
            $_SESSION['error'] = "Données utilisateur invalides";
            header('Location: /users/create');
            exit;
        }
        
        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->userRepository->findByEmail($user->getEmail());
        if ($existingUser) {
            $_SESSION['error'] = "Cet email est déjà utilisé";
            header('Location: /users/create');
            exit;
        }
        
        // Enregistrer l'utilisateur
        if ($this->userRepository->save($user)) {
            $_SESSION['success'] = "Utilisateur créé avec succès";
            header('Location: /users');
        } else {
            $_SESSION['error'] = "Erreur lors de la création de l'utilisateur";
            header('Location: /users/create');
        }
        exit;
    }
    
    public function edit($id)
    {
        // Récupérer l'utilisateur à modifier
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Charger la vue
        require_once ROOT . 'views/users/edit.php';
    }
    
    public function update($id)
    {
        // Récupérer l'utilisateur à modifier
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Mettre à jour les données
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        
        // Valider les données
        if (!$user->isValid()) {
            $_SESSION['error'] = "Données utilisateur invalides";
            header('Location: /users/edit/' . $id);
            exit;
        }
        
        // Vérifier si l'email existe déjà pour un autre utilisateur
        $existingUser = $this->userRepository->findByEmail($user->getEmail());
        if ($existingUser && $existingUser->getId() !== $user->getId()) {
            $_SESSION['error'] = "Cet email est déjà utilisé par un autre utilisateur";
            header('Location: /users/edit/' . $id);
            exit;
        }
        
        // Enregistrer les modifications
        if ($this->userRepository->save($user)) {
            $_SESSION['success'] = "Utilisateur mis à jour avec succès";
            header('Location: /users');
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour de l'utilisateur";
            header('Location: /users/edit/' . $id);
        }
        exit;
    }
    
    public function delete($id)
    {
        // Supprimer l'utilisateur
        if ($this->userRepository->delete($id)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
        }
        
        header('Location: /users');
        exit;
    }
}