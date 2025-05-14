<h1>Liste des utilisateurs</h1>

<a href="/users/create" class="btn btn-success">Ajouter un utilisateur</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($users)): ?>
            <tr>
                <td colspan="5">Aucun utilisateur trouvé</td>
            </tr>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->getId(); ?></td>
                    <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                    <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                    <td><?php echo $user->getCreatedAt(); ?></td>
                    <td class="actions">
                        <a href="/users/show/<?php echo $user->getId(); ?>" class="btn btn-primary">Voir</a>
                        <a href="/users/edit/<?php echo $user->getId(); ?>" class="btn btn-primary">Modifier</a>
                        <a href="/users/delete/<?php echo $user->getId(); ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                           Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>