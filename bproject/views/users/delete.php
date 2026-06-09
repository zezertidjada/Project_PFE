<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once "../../config/database.php";
require_once "../../config/csrf.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . bp_url('login.php'));
    exit();
}

// Admin seulement
if ($_SESSION['role_id'] != 1) {
    header('Location: ' . bp_url('dashboard.php'));
    exit();
}

// POST uniquement
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

verifyCSRFToken($_POST['csrf_token'] ?? '');

if (empty($_POST['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_POST['id']);

// Empêcher de se supprimer soi-même
if ($id === (int)$_SESSION['user_id']) {
    header("Location: index.php?error=Vous+ne+pouvez+pas+supprimer+votre+propre+compte");
    exit();
}

// Vérifier existence
$result = mysqli_query($conn, "SELECT id FROM users WHERE id = $id");
if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

// Supprimer
if (mysqli_query($conn, "DELETE FROM users WHERE id = $id")) {
    header("Location: index.php?success=Utilisateur+supprimé+avec+succès");
} else {
    header("Location: index.php?error=Erreur+lors+de+la+suppression");
}
exit();