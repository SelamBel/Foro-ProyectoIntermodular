<?php
session_start();

header('Content-Type: application/json');
require_once __DIR__ . '/config/lang.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' =>  t('delete_comment.error_unauthenticated') ]);
    exit;
}

require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';

$id           = (int) ($_POST['id'] ?? 0);
$commentModel = new Comment();
$comment      = $commentModel->findById($id);

if (!$comment) {
    echo json_encode(['error' => t('delete_comment.error_not_found')]);
    exit;
}

if ($_SESSION['user_id'] != $comment['id_user'] && $_SESSION['role'] !== 'moderator') {
    echo json_encode(['error' => t('delete_comment.error_unauthorized')]);
    exit;
}

$commentModel->delete($id);

if ($_SESSION['role'] === 'moderator' && $_SESSION['user_id'] != $comment['id_user']) {
    $notifModel = new Notification();
    $notifModel->create(
        $comment['id_user'],
        'mod_delete_comment',
         t('delete_comment.mod_notification')  . mb_strimwidth($comment['content'], 0, 50, '...'),
        null
    );
}

echo json_encode(['success' => true]);
