<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/User.php';

$otherId = (int) ($_GET['user'] ?? 0);
if (!$otherId || $otherId === $_SESSION['user_id']) {
    header('Location: /pages/messages.php');
    exit;
}

$messageModel = new Message();
$userModel    = new User();
$other        = $userModel->findById($otherId);

if (!$other) {
    header('Location: /pages/messages.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content'] ?? '');
    if (!empty($content)) {
        $messageModel->send($_SESSION['user_id'], $otherId, $content);
    }
    header('Location: /pages/conversation.php?user=' . $otherId);
    exit;
}

$messageModel->markReadFrom($otherId, $_SESSION['user_id']);
$messages = $messageModel->getConversationWith($_SESSION['user_id'], $otherId);

$pageTitle  = 'Conversación con ' . htmlspecialchars($other['username']);
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card chat-card">
            <div class="chat-header">
                <a href="/pages/messages.php" class="action-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="conv-avatar">
                    <?php if (!empty($other['avatar'])): ?>
                        <img src="<?= htmlspecialchars($other['avatar']) ?>" alt="">
                    <?php else: ?>
                        <i class="fa-solid fa-circle-user"></i>
                    <?php endif; ?>
                </div>
                <span class="chat-username"><?= htmlspecialchars($other['username']) ?></span>
            </div>

            <div class="chat-messages" id="chatMessages">
                <?php if (empty($messages)): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-comment"></i>
                        <p>Empieza la conversación.</p>
                    </div>
                <?php endif; ?>

                <?php foreach ($messages as $msg): ?>
                <?php $isMine = $msg['id_sender'] === $_SESSION['user_id']; ?>
                <div class="chat-bubble <?= $isMine ? 'mine' : 'theirs' ?>">
                    <p><?= nl2br(htmlspecialchars($msg['content'])) ?></p>
                    <span class="bubble-time post-date" data-date="<?= $msg['date_creation'] ?>"><?= $msg['date_creation'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <form method="POST" class="chat-form" id="chatForm">
                <textarea name="content" id="chatInput" rows="2"
                          placeholder="Escribe un mensaje..."></textarea>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>