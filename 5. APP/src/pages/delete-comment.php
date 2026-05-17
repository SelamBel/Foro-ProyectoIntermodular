<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado.']);
    exit;
}

require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';

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

if ($_SESSION['role'] === 'moderator' && $_SESSION['user_id'] != $comment['id_user']) {
    $notifModel = new Notification();
    $notifModel->create(
        $comment['id_user'],
        'mod_delete_comment',
        'Un moderador ha eliminado tu comentario: ' . mb_strimwidth($comment['content'], 0, 50, '...'),
        null
    );
}

echo json_encode(['success' => true]);
