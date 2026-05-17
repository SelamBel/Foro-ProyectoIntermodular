<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado.']);
    exit;
}

require_once __DIR__ . '/../models/Publication.php';
require_once __DIR__ . '/../models/Notification.php';

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

if ($_SESSION['role'] === 'moderator' && $_SESSION['user_id'] != $post['id_user']) {
    $notifModel = new Notification();
    $notifModel->create(
        $post['id_user'],
        'mod_delete_post',
        'Un moderador ha eliminado tu publicación: ' . mb_strimwidth($post['title'], 0, 50, '...'),
        null
    );
}

echo json_encode(['success' => true]);
