<?php
if (session_status() == PHP_SESSION_NONE) { session_start(); }
require_once "../../config/database.php";
require_once "../../config/csrf.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode invalide']);
    exit();
}

$data       = json_decode(file_get_contents('php://input'), true) ?? [];
$task_id    = intval($data['task_id']    ?? 0);
$new_status = trim($data['status']       ?? '');
$csrf_token = $data['csrf_token']        ?? '';

if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
    exit();
}

$allowed = ['À faire', 'En cours', 'Terminé', 'Bloquée'];
if (!in_array($new_status, $allowed) || $task_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit();
}

if ((int)$_SESSION['role_id'] === 4) {
    echo json_encode(['success' => false, 'message' => 'Permission refusée']);
    exit();
}

$stmt = mysqli_prepare($conn, "UPDATE tasks SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $new_status, $task_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur BDD : ' . mysqli_error($conn)]);
}
exit();