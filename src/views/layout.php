<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Application MVC'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <a href="/" class="brand">
                <i class="fas fa-code"></i> PHP MVC
            </a>
            <a href="/" <?php echo (empty($_GET['url']) || $_GET['url'] == 'home') ? 'class="active"' : ''; ?>>Accueil</a>
            <a href="/users" <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'users') === 0) ? 'class="active"' : ''; ?>>Utilisateurs</a>
        </div>
       
        <!-- Partie droite de la navbar pour l'authentification -->
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/users/show/<?php echo $_SESSION['user_id']; ?>" class="user-link">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <span class="badge badge-admin">Admin</span>
                    <?php endif; ?>
                </a>
                <a href="/logout">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            <?php else: ?>
                <a href="/login" <?php echo (isset($_GET['url']) && $_GET['url'] == 'login') ? 'class="active"' : ''; ?>>
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
                <a href="/register" <?php echo (isset($_GET['url']) && $_GET['url'] == 'register') ? 'class="active"' : ''; ?>>
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success">
                <?php echo $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
       
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php
        echo $content;
        ?>
    </div>
    <!-- Footer reste inchangé -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3>À propos</h3>
                <p>Application MVC PHP développée avec Docker, permettant une gestion simple et efficace des données avec le pattern Repository.</p>
            </div>
            <div class="footer-section links">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/users">Utilisateurs</a></li>
                    <li><a href="https://github.com/robinhotton/php_mvc_natif" target="_blank">GitHub</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contact</h3>
                <p><i class="fa fa-envelope"></i> support@tecken.fr</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/diginamic"><i class="fab fa-facebook"></i></a>
                    <a href="https://x.com/Diginamic"><i class="fab fa-twitter"></i></a>
                    <a href="https://fr.linkedin.com/school/diginamic/"><i class="fab fa-linkedin"></i></a>
                    <a href="https://github.com/robinhotton/php_mvc_natif"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?php echo date('Y'); ?> PHP natif MVC Docker | Développé par Robin HOTTON pour Diginamic | Tous droits réservés
        </div>
    </footer>
</body>
</html>