<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../models/Notification.php';

$notifModel = new Notification();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_all') {
    $notifModel->deleteAll($_SESSION['user_id']);
    header('Location: /pages/notifications.php');
    exit;
}

$notifModel->markAllRead($_SESSION['user_id']);
$notifications = $notifModel->getByUser($_SESSION['user_id']);

$icons = [
    'comment_on_post'    => 'fa-comment',
    'like_post'          => 'fa-arrow-up',
    'mod_delete_post'    => 'fa-trash',
    'mod_delete_comment' => 'fa-trash',
    'post_milestone'     => 'fa-fire',
];

$extraCss = ['chat.css'];
$pageTitle  = 'Notificaciones';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px">
                <h1 class="form-card__title" style="margin-bottom:0"><i class="fa-solid fa-bell"></i> Notificaciones</h1>
                <?php if (!empty($notifications)): ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="delete_all">
                        <button type="submit" class="btn-outline">
                            <i class="fa-solid fa-trash"></i> Borrar todas
                        </button>
                    </form>
                <?php endif; ?>
            </div>

            <?php if (empty($notifications)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-bell-slash"></i>
                    <p>No tienes notificaciones.</p>
                </div>
            <?php else: ?>
                <div class="notif-list">
                    <?php foreach ($notifications as $n): ?>
                        <div class="notif-item <?= !$n['is_read'] ? 'unread' : '' ?>">
                            <div class="notif-icon">
                                <i class="fa-solid <?= $icons[$n['type']] ?? 'fa-bell' ?>"></i>
                            </div>
                            <div class="notif-body">
                                <p><?= htmlspecialchars($n['message']) ?></p>
                                <span class="post-date" data-date="<?= $n['date_creation'] ?>"><?= $n['date_creation'] ?></span>
                            </div>
                            <?php if ($n['url']): ?>
                                <a href="<?= htmlspecialchars($n['url']) ?>" class="btn-outline" style="flex-shrink:0">Ver</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>