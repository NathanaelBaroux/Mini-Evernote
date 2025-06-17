<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$errors = [];

// Vérifie que l’ID de la note est présent dans l’URL
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$note_id = $_GET['id'];

// Vérifie que la note appartient bien à l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $user_id]);
$note = $stmt->fetch();

if (!$note) {
    die("Note introuvable ou accès refusé.");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title)) {
        $errors[] = "Le titre est obligatoire.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE notes SET title = ?, content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$title, $content, $note_id, $user_id]);
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la note - Mini Evernote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card p-4 bg-white" style="width: 100%; max-width: 600px;">
        <div class="mb-4 text-center">
            <h3><i class="bi bi-pencil-fill"></i> Modifier la note</h3>
            <p class="text-muted">Mettez à jour votre note</p>
        </div>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e) echo "<p class='mb-0'>$e</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" name="title" id="title" class="form-control" required
                       value="<?= htmlspecialchars($note['title']) ?>">
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea name="content" id="content" rows="6" class="form-control" required><?= htmlspecialchars($note['content']) ?></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check2-square"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>