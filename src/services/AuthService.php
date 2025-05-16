<?php

class AuthService
{
    private $userRepository;
    
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Récupère le repository utilisateur
     * 
     * @return UserRepositoryInterface
     */
    public function getUserRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }
    
    /**
     * Tente de connecter un utilisateur.
     * 
     * @param string $username Nom d'utilisateur
     * @param string $password Mot de passe
     * @return User|null L'utilisateur connecté ou null en cas d'échec
     */
    public function login(string $username, string $password): ?User
    {       
        $user = $this->userRepository->findByUsername($username);
        
        if (!$user) {
            return null;
        }

        $passwordVerified = $user->verifyPassword($password);

        if (!$passwordVerified) {
            return null;
        }
        
        // Stocker l'utilisateur connecté en session
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['role'] = $user->getRole();
        
        return $user;
    }
    
    /**
     * Déconnecte l'utilisateur courant.
     */
    public function logout(): void
    {
        // Détruire les variables de session
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        
        // Destruction complète de la session
        session_destroy();
    }
    
    /**
     * Vérifie si un utilisateur est connecté.
     * 
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Récupère l'utilisateur actuellement connecté.
     * 
     * @return User|null L'utilisateur connecté ou null si aucun utilisateur n'est connecté
     */
    public function getCurrentUser(): ?User
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->userRepository->findById($_SESSION['user_id']);
    }
    
    /**
     * Vérifie si l'utilisateur courant a le rôle spécifié.
     * 
     * @param string $role Le rôle à vérifier
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        return $_SESSION['role'] === $role;
    }
    
     /**
     * Vérifie si l'utilisateur courant est administrateur.
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    
    /**
     * Redirection si l'utilisateur n'est pas connecté.
     * 
     * @param string $redirectUrl URL de redirection si non connecté
     * @return bool True si l'utilisateur est connecté, false sinon
     */
    public function requireLogin(string $redirectUrl = '/login'): bool
    {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header("Location: $redirectUrl");
            exit;
        }
        
        return true;
    }
    
    /**
     * Redirection si l'utilisateur n'a pas le rôle requis.
     * 
     * @param string $role Le rôle requis
     * @param string $redirectUrl URL de redirection si non autorisé
     * @return bool True si l'utilisateur a le rôle requis, false sinon
     */
    public function requireRole(string $role, string $redirectUrl = '/'): bool
    {
        // Vérifie d'abord si l'utilisateur est connecté
        $this->requireLogin($redirectUrl);
        
        if (!$this->hasRole($role)) {
            $_SESSION['error'] = "Vous n'avez pas les droits nécessaires pour accéder à cette page.";
            header("Location: $redirectUrl");
            exit;
        }
        
        return true;
    }
}