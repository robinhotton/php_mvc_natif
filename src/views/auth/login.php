<div class="auth-container">
    <h1>Connexion</h1>
    
    <form action="/login" method="post" class="auth-form">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
        
        <div class="auth-links">
            <p>Vous n'avez pas de compte ? <a href="/register">S'inscrire</a></p>
        </div>
    </form>
</div>