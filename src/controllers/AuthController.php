<?php

class AuthController extends BaseController
{
    private $authService;
    private $userRepository;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->userRepository = $authService->getUserRepository();
    }
    
    /**
     * Affiche le formulaire de connexion.
     */
    public function loginForm()
    {
        // Rediriger si déjà connecté
        if ($this->authService->isLoggedIn()) {
            header('Location: /');
            exit;
        }
        
        $this->render('auth/login.php', [
            'title' => 'Connexion'
        ]);
    }
    
    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function login()
    {
        // Rediriger si déjà connecté
        if ($this->authService->isLoggedIn()) {
            header('Location: /');
            exit;
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Tentative de connexion
        $user = $this->authService->login($username, $password);
        
        if ($user) {
            // Redirection avec message de succès
            $_SESSION['success'] = "Connexion réussie. Bienvenue, {$user->getUsername()} !";
            
            // Rediriger vers la page demandée avant la connexion, ou la page d'accueil
            $redirect = $_SESSION['redirect_after_login'] ?? '/';
            unset($_SESSION['redirect_after_login']);
            
            header("Location: $redirect");
            exit;
        }
        
        // Échec de la connexion
        $_SESSION['error'] = "Identifiants incorrects. Veuillez réessayer.";
        $this->render('auth/login.php', [
            'title' => 'Connexion',
            'username' => $username
        ]);
    }
    
    /**
     * Déconnecte l'utilisateur.
     */
    public function logout()
    {
        $this->authService->logout();
        
        $_SESSION['success'] = "Vous avez été déconnecté avec succès.";
        header('Location: /');
        exit;
    }
    
    /**
     * Affiche le formulaire d'inscription.
     */
    public function registerForm()
    {
        // Rediriger si déjà connecté
        if ($this->authService->isLoggedIn()) {
            header('Location: /');
            exit;
        }
        
        $this->render('auth/register.php', [
            'title' => 'Inscription'
        ]);
    }
    
    /**
     * Traite la soumission du formulaire d'inscription.
     */
    public function register()
    {
        // Rediriger si déjà connecté
        if ($this->authService->isLoggedIn()) {
            header('Location: /');
            exit;
        }
        
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Vérification des mots de passe
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            $this->render('auth/register.php', [
                'title' => 'Inscription',
                'username' => $username,
                'email' => $email
            ]);
            return;
        }
        
        // Vérification de l'unicité du nom d'utilisateur
        if ($this->userRepository->findByUsername($username)) {
            $_SESSION['error'] = "Ce nom d'utilisateur existe déjà.";
            $this->render('auth/register.php', [
                'title' => 'Inscription',
                'email' => $email
            ]);
            return;
        }
        
        // Vérification de l'unicité de l'email
        if ($this->userRepository->findByEmail($email)) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            $this->render('auth/register.php', [
                'title' => 'Inscription',
                'username' => $username
            ]);
            return;
        }
        
        // Création de l'utilisateur
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password); // Le hashage est géré dans le setter
        $user->setRole('user'); // Rôle par défaut
        
        // Vérification de la validité
        if (!$user->isValid()) {
            $_SESSION['error'] = "Les données fournies ne sont pas valides.";
            $this->render('auth/register.php', [
                'title' => 'Inscription',
                'username' => $username,
                'email' => $email
            ]);
            return;
        }
        
        // Enregistrement de l'utilisateur
        if ($this->userRepository->save($user)) {
            $_SESSION['success'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            header('Location: /login');
            exit;
        }
        
        // Erreur lors de l'enregistrement
        $_SESSION['error'] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
        $this->render('auth/register.php', [
            'title' => 'Inscription',
            'username' => $username,
            'email' => $email
        ]);
    }
}
