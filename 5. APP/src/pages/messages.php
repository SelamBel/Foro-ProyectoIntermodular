<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../config/lang.php';
require_once __DIR__ . '/../models/Message.php';

$messageModel   = new Message();
$conversations  = $messageModel->getConversations($_SESSION['user_id']);

$extraCss = ['chat.css'];
$pageTitle  = 'Mensajes';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <div class="form-title-button">
                <h1 class="form-card__title"><i class="fa-solid fa-envelope"></i> <?= t('messages.title') ?></h1>
                <a href="/pages/users.php" class="btn-primary" style="margin-left:auto">
                    <i class="fa-solid fa-user-plus"></i> <?= t('messages.new_message') ?>
                </a>
            </div>
            <?php if (empty($conversations)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-envelope-open"></i>
                    <p><?= t('messages.empty_state') ?></p>
                </div>
            <?php else: ?>
                <div class="conversation-list">
                    <?php foreach ($conversations as $conv): ?>
                        <a href="/pages/conversation.php?user=<?= $conv['id_sender'] == $_SESSION['user_id'] ? $conv['id_receiver'] : $conv['id_sender'] ?>"
                            class="conversation-item <?= $conv['unread_count'] > 0 ? 'unread' : '' ?>">
                            <div class="conv-avatar">
                                <?php if (!empty($conv['avatar'])): ?>
                                    <img src="<?= htmlspecialchars($conv['avatar']) ?>" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="conv-info">
                                <span class="conv-username"><?= htmlspecialchars($conv['username']) ?></span>
                                <span class="conv-preview"><?= htmlspecialchars(mb_strimwidth($conv['content'], 0, 60, '...')) ?></span>
                            </div>
                            <div class="conv-meta">
                                <span class="post-date" data-date="<?= $conv['date_creation'] ?>"><?= $conv['date_creation'] ?></span>
                                <?php if ($conv['unread_count'] > 0): ?>
                                    <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>