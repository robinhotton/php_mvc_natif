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
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="/users" class="btn btn-default">Annuler</a>
    </div>
</form>