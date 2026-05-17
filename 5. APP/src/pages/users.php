<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$search    = trim($_GET['q'] ?? '');
$users     = $search ? $userModel->search($search, $_SESSION['user_id']) : [];

$pageTitle  = 'Buscar usuarios';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-user-plus"></i> Nuevo mensaje</h1>

            <form method="GET" action="/pages/users.php" id="userSearchForm">
                <div class="form-group">
                    <label for="q">Buscar usuario</label>
                    <div class="input-icon-right">
                        <input type="text" id="q" name="q"
                               value="<?= htmlspecialchars($search) ?>"
                               placeholder="Nombre de usuario...">
                        <button type="submit" class="toggle-password">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </form>

            <?php if ($search && empty($users)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-user-slash"></i>
                    <p>No se encontraron usuarios con ese nombre.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($users)): ?>
                <div class="conversation-list" style="margin-top:16px">
                    <?php foreach ($users as $u): ?>
                    <div class="conversation-item">
                        <div class="conv-avatar">
                            <?php if (!empty($u['avatar'])): ?>
                                <img src="<?= htmlspecialchars($u['avatar']) ?>" alt="">
                            <?php else: ?>
                                <i class="fa-solid fa-circle-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="conv-info">
                            <span class="conv-username"><?= htmlspecialchars($u['username']) ?></span>
                            <span class="conv-preview">Miembro desde <?= date('d/m/Y', strtotime($u['date_registered'])) ?></span>
                        </div>
                        <a href="/pages/conversation.php?user=<?= $u['id'] ?>" class="btn-primary">
                            <i class="fa-solid fa-paper-plane"></i> Mensaje
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>