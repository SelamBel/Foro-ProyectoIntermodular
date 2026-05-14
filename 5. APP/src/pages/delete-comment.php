<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado.']);
    exit;
}

require_once __DIR__ . '/../models/Comment.php';

$id           = (int) ($_POST['id'] ?? 0);
$commentModel = new Comment();
$comment      = $commentModel->findById($id);

if (!$comment) {
    echo json_encode(['error' => 'Comentario no encontrado.']);
    exit;
}

if ($_SESSION['user_id'] != $comment['id_user'] && $_SESSION['role'] !== 'moderator') {
    echo json_encode(['error' => 'Sin permisos.']);
    exit;
}

$commentModel->delete($id);
echo json_encode(['success' => true]);
