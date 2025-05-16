<h1>Ajouter un utilisateur</h1>

<form action="/users/store" method="post">
    <div class="form-group">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <div class="form-group">
        <label for="role">Rôle:</label>
        <select id="role" name="role">
            <option value="user" selected>Utilisateur</option>
            <option value="admin">Administrateur</option>
        </select>
    </div>
    
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/users" class="btn btn-default">Annuler</a>
    </div>
</form>