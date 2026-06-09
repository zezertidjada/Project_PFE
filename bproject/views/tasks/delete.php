<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once "../../config/database.php";
require_once "../../config/csrf.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . bp_url('login.php'));
    exit();
}

// Admin + Chef seulement
if (!in_array($_SESSION['role_id'], [1, 2])) {
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

// Vérifier existence
$check = mysqli_prepare($conn, "SELECT id FROM tasks WHERE id = ?");
mysqli_stmt_bind_param($check, "i", $id);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);
if (mysqli_num_rows($res) === 0) {
    header("Location: index.php");
    exit();
}
mysqli_stmt_close($check);

// Supprimer
$stmt = mysqli_prepare($conn, "DELETE FROM tasks WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: index.php?success=Tâche+supprimée+avec+succès");
exit();