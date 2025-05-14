<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Application MVC'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="/" <?php echo (empty($_GET['url']) || $_GET['url'] == 'home') ? 'class="active"' : ''; ?>>Accueil</a>
        <a href="/users" <?php echo (isset($_GET['url']) && strpos($_GET['url'], 'users') === 0) ? 'class="active"' : ''; ?>>Utilisateurs</a>
        <!-- Ajoutez d'autres liens de navigation ici -->
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
        // CORRECTION : Afficher directement la variable $content au lieu de l'inclure
        echo $content;
        ?>
    </div>
</body>
</html>