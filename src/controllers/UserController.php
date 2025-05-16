<?php

class UserController extends BaseController {
    private $userRepository;
    private $authService;
    
    public function __construct(UserRepositoryInterface $userRepository, AuthService $authService) {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }
    
    public function index() {
        // Vérifier si l'utilisateur est connecté
        $this->authService->requireLogin('/login');
        
        // Récupérer tous les utilisateurs
        $users = $this->userRepository->findAll();
        
        // Rendre la vue
        $this->render('users/index.php', [
            'title' => 'Liste des utilisateurs',
            'users' => $users
        ]);
    }
    
    public function show($id) {
        // Vérifier si l'utilisateur est connecté
        $this->authService->requireLogin('/login');
        
        // Récupérer un utilisateur par son id
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }

        // Rendre la vue
        $this->render('users/show.php', [
            'title' => 'Détail de l\'utilisateur',
            'user' => $user
        ]);
    }
    
    public function create() {
        // Vérifier si l'utilisateur est admin
        $this->authService->requireRole('admin', '/users');
        
        // Rendre la vue du formulaire
        $this->render('users/create.php', [
            'title' => 'Ajouter un utilisateur'
        ]);
    }
    
    public function store() {
        // Vérifier si l'utilisateur est admin
        $this->authService->requireRole('admin', '/users');
        
        // Création d'un nouvel utilisateur
        $user = new User();
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setRole($_POST['role'] ?? 'user');
        
        // Valider les données
        if (!$user->isValid()) {
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
    
    public function edit($id) {
        // Vérifier si l'utilisateur est connecté
        $this->authService->requireLogin('/login');
        
        // Récupérer l'utilisateur à modifier
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Vérifier si l'utilisateur a le droit de modifier ce profil
        $currentUser = $this->authService->getCurrentUser();
        if ($user->getId() !== $currentUser->getId() && !$this->authService->isAdmin()) {
            $_SESSION['error'] = "Vous n'avez pas les droits nécessaires pour modifier ce profil";
            header('Location: /users');
            exit;
        }
        
        // Rendre la vue
        $this->render('users/edit.php', [
            'title' => 'Modifier l\'utilisateur',
            'user' => $user,
            'isAdmin' => $this->authService->isAdmin()
        ]);
    }
    
    public function update($id) {
        // Vérifier si l'utilisateur est connecté
        $this->authService->requireLogin('/login');
        
        // Récupérer l'utilisateur à modifier
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Vérifier si l'utilisateur a le droit de modifier ce profil
        $currentUser = $this->authService->getCurrentUser();
        if ($user->getId() !== $currentUser->getId() && !$this->authService->isAdmin()) {
            $_SESSION['error'] = "Vous n'avez pas les droits nécessaires pour modifier ce profil";
            header('Location: /users');
            exit;
        }
        
        // Mettre à jour les données
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        
        // Si un nouveau mot de passe est fourni, le mettre à jour
        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }
        
        // Seul un admin peut changer le rôle
        if ($this->authService->isAdmin() && isset($_POST['role'])) {
            $user->setRole($_POST['role']);
        }
        
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
    
    public function delete($id) {
        // Vérifier si l'utilisateur est admin
        $this->authService->requireRole('admin', '/users');
        
        // Récupérer l'utilisateur à supprimer
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé";
            header('Location: /users');
            exit;
        }
        
        // Empêcher la suppression de son propre compte
        $currentUser = $this->authService->getCurrentUser();
        if ($user->getId() === $currentUser->getId()) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte";
            header('Location: /users');
            exit;
        }
        
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