<h1>Liste des utilisateurs</h1>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="/users/create" class="btn btn-success">Ajouter un utilisateur</a>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Date de création</th>
            <th>Date de mise à jour</th>
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
                    <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                    <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                    <td><?php echo $user->getCreatedAt(); ?></td>
                    <td><?php echo $user->getUpdatedAt(); ?></td>
                    <td class="actions">
                        <a href="/users/show/<?php echo $user->getId(); ?>" class="btn btn-primary">Voir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>