<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifie que l'ID de la note est présent dans l'URL
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

// Supprimer la note
$stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $user_id]);

header('Location: dashboard.php');
exit;
?>