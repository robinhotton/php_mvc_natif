<!-- src/views/users/show.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail de l'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        .user-details {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .user-detail {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .user-detail:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 5px;
        }
        .btn-primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }
        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }
        .btn-default {
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
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
    </div>
</body>
</html>