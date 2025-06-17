<?php
session_start();
require_once 'db_connect.php';

// Redirige vers login si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les notes de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY updated_at DESC");
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Mini Evernote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">📝 Mini Evernote</a>
        <div class="d-flex align-items-center">
            <span class="text-white me-3">Bonjour, <?= htmlspecialchars($_SESSION['username']) ?> !</span>
            <a href="logout.php" class="btn btn-outline-light">Se déconnecter</a>
        </div>
    </div>
</nav>

<!-- CONTENU -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Mes Notes</h3>
        <a href="create_note.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nouvelle note
        </a>
    </div>

    <?php if (count($notes) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Créée le</th>
                        <th>Modifiée le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notes as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['title']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($note['created_at'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($note['updated_at'])) ?></td>
                            <td class="text-end">
                                <a href="edit_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </a>
                                <a href="delete_note.php?id=<?= $note['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette note ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i> Vous n’avez encore aucune note. Cliquez sur "Nouvelle note" pour commencer !
        </div>
    <?php endif; ?>
</div>

</body>
</html>