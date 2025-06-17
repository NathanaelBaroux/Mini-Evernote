<?php
require_once 'db_connect.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Mini Evernote</title>
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
    <div class="card p-4 bg-white" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <i class="bi bi-journal-text" style="font-size: 2.5rem; color: #2575fc;"></i>
            <p class="text-muted">Bienvenue dans le Mini Evernote!</br> By Nathanael</p>
            <h3 class="mt-2">Connexion</h3>
            
        </div>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <small>Pas encore de compte ? <a href="register.php">Inscription</a></small>
        </div>
    </div>
</div>

</body>
</html>