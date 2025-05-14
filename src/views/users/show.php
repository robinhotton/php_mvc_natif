<h1>Détail de l'utilisateur</h1>

<div class="user-details">
    <div class="user-detail">
        <span class="label">ID:</span>
        <span><?php echo $user->getId(); ?></span>
    </div>
    <div class="user-detail">
        <span class="label">Nom d'utilisateur:</span>
        <span><?php echo htmlspecialchars($user->getUsername()); ?></span>
    </div>
    <div class="user-detail">
        <span class="label">Email:</span>
        <span><?php echo htmlspecialchars($user->getEmail()); ?></span>
    </div>
    <div class="user-detail">
        <span class="label">Date de création:</span>
        <span><?php echo $user->getCreatedAt(); ?></span>
    </div>
</div>

<div class="actions">
    <a href="/users" class="btn btn-default">Retour à la liste</a>
    <a href="/users/edit/<?php echo $user->getId(); ?>" class="btn btn-primary">Modifier</a>
    <a href="/users/delete/<?php echo $user->getId(); ?>" 
       class="btn btn-danger" 
       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
       Supprimer
    </a>
</div>