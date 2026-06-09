<?php
session_start();
require_once "../../config/database.php";
require_once "../../config/csrf.php";

// Sécurité : connexion + Admin seulement
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . bp_url('login.php'));
    exit();
}

if ($_SESSION['role_id'] != 1) {
    header('Location: ' . bp_url('dashboard.php'));
    exit();
}

// Doit être une requête POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Vérification CSRF
verifyCSRFToken($_POST['csrf_token'] ?? '');

// ID obligatoire
if (empty($_POST['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_POST['id']);

// Vérifier si ce client a des projets liés
$check = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM projects WHERE client_id = ?");
mysqli_stmt_bind_param($check, "i", $id);
mysqli_stmt_execute($check);
$check_result = mysqli_stmt_get_result($check);
$count = mysqli_fetch_assoc($check_result)['total'];
mysqli_stmt_close($check);

if ($count > 0) {
    header("Location: index.php?error=Ce+client+a+$count+projet(s)+lié(s).+Supprimez-les+d'abord.");
    exit();
}

// Suppression sécurisée
$stmt = mysqli_prepare($conn, "DELETE FROM clients WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

header("Location: index.php?success=Client+supprimé+avec+succès");
exit();
