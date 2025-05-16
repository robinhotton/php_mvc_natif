<h1>Modifier un utilisateur</h1>

<form action="/users/update/<?php echo $user->getId(); ?>" method="post">
    <div class="form-group">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->getUsername()); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="password">Nouveau mot de passe (laisser vide pour conserver l'actuel):</label>
        <input type="password" id="password" name="password">
    </div>
    
    <?php if (isset($isAdmin) && $isAdmin): ?>
    <div class="form-group">
        <label for="role">Rôle:</label>
        <select id="role" name="role">
            <option value="user" <?php echo $user->getRole() === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
            <option value="admin" <?php echo $user->getRole() === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
        </select>
    </div>
    <?php endif; ?>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="/users" class="btn btn-default">Annuler</a>
        <?php if (isset($isAdmin) && $isAdmin && $user->getId() !== $_SESSION['user_id']): ?>
        <a href="/users/delete/<?php echo $user->getId(); ?>" 
           class="btn btn-danger" 
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
           Supprimer
        </a>
        <?php endif; ?>
    </div>
</form>