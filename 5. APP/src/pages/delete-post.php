<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado.']);
    exit;
}

require_once __DIR__ . '/../models/Publication.php';

$id     = (int) ($_POST['id'] ?? 0);
$pubModel = new Publication();
$post   = $pubModel->getById($id);

if (!$post) {
    echo json_encode(['error' => 'Publicación no encontrada.']);
    exit;
}

if ($_SESSION['user_id'] != $post['id_user'] && $_SESSION['role'] !== 'moderator') {
    echo json_encode(['error' => 'Sin permisos.']);
    exit;
}

$pubModel->delete($id);
echo json_encode(['success' => true]);