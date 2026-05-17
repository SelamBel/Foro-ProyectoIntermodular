<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'No autenticado.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido.']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Notification.php';

$publicationId = (int) ($_POST['id']   ?? 0);
$type          = (int) ($_POST['type'] ?? -1);
$userId        = (int) $_SESSION['user_id'];

if (!$publicationId || !in_array($type, [0, 1])) {
    echo json_encode(['error' => 'Datos inválidos.']);
    exit;
}

$db = getDB();

$stmt = $db->prepare('SELECT id, type FROM vote WHERE id_user = ? AND id_publication = ?');
$stmt->execute([$userId, $publicationId]);
$existing = $stmt->fetch();

if ($existing) {
    if ((int) $existing['type'] === $type) {
        $db->prepare('DELETE FROM vote WHERE id = ?')->execute([$existing['id']]);
    } else {
        $db->prepare('UPDATE vote SET type = ? WHERE id = ?')->execute([$type, $existing['id']]);
    }
} else {
    $db->prepare('INSERT INTO vote (id_user, id_publication, type) VALUES (?, ?, ?)')->execute([$userId, $publicationId, $type]);
}

$stmt = $db->prepare("
    SELECT
        COALESCE(SUM(CASE WHEN type = 1 THEN 1 ELSE 0 END), 0) AS upvotes,
        COALESCE(SUM(CASE WHEN type = 0 THEN 1 ELSE 0 END), 0) AS downvotes
    FROM vote
    WHERE id_publication = ?
");
$stmt->execute([$publicationId]);
$counts = $stmt->fetch();

echo json_encode([
    'upvotes'   => (int) $counts['upvotes'],
    'downvotes' => (int) $counts['downvotes'],
]);

$owner = $db->prepare('SELECT id_user FROM publication WHERE id = ?');
$owner->execute([$publicationId]);
$ownerId = (int) $owner->fetchColumn();

if ($type === 1 && $userId !== $ownerId && !$existing) {
    $notifModel = new Notification();
    $notifModel->create(
        $ownerId,
        'like_post',
        '@' . $_SESSION['username'] . ' ha votado positivamente tu publicación.',
        '/pages/post.php?id=' . $publicationId
    );
}
