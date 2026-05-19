<?php
$pageTitle = $pageTitle ?? 'AntNet';
$unreadNotifs = 0;
$unreadMessages = 0;
if (isset($_SESSION['user_id'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Notification.php';
    $unreadNotifs = (new Notification())->countUnread($_SESSION['user_id']);

    require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Message.php';
    $unreadMessages = (new Message())->countUnread($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - AntNet</title>
    <script>
        (function() {
            if (localStorage.getItem('antnet_dark') === 'true') {
                document.documentElement.classList.add('dark');
            }
            const color = localStorage.getItem('antnet_color');
            if (color) {
                document.documentElement.style.setProperty('--primary', color);
            }
        })();
    </script>

    <link rel="icon" type="image/svg+xml" href="/assets/img/logos/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php foreach ($extraCss ?? [] as $css): ?>
        <link rel="stylesheet" href="/assets/css/<?= $css ?>">
    <?php endforeach; ?>
</head>

<body>

    <header class="site-header">
        <a href="/index.php" class="header-logo">
            <img src="/assets/img/logos/logo plain weight.svg" alt="AntNet logo"
                onerror="this.style.display='none'">
            <span>AntNet</span>
        </a>

        <form method="GET" action="/pages/search.php" class="header-search">
            <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input type="text" name="q" placeholder="Buscar en AntHive"
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </form>

        <div class="header-actions">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/pages/messages.php" class="icon-btn" title="Mensajes" style="position:relative">
                    <i class="fa-solid fa-envelope"></i>
                    <?php if ($unreadMessages > 0): ?>
                        <span class="notif-badge"><?= $unreadMessages ?></span>
                    <?php endif; ?>
                </a>
                <a href="/pages/notifications.php" class="icon-btn" title="Notificaciones" style="position:relative">
                    <i class="fa-solid fa-bell"></i>
                    <?php if ($unreadNotifs > 0): ?>
                        <span class="notif-badge"><?= $unreadNotifs ?></span>
                    <?php endif; ?>
                </a>
                <a href="/pages/profile.php" class="icon-btn" title="Mi perfil">
                    <?php if (!empty($_SESSION['avatar'])): ?>
                        <img src="<?= htmlspecialchars($_SESSION['avatar']) ?>" class="meta-avatar" alt="" style="width:24px; height:24px; border-radius:50%; object-fit:cover;">
                    <?php else: ?>
                        <i class="fa-solid fa-circle-user"></i>
                    <?php endif; ?>
                </a>
            <?php else: ?>
                <a href="/pages/login.php" class="btn-outline">Iniciar sesión</a>
                <a href="/pages/register.php" class="btn-primary">Registrarse</a>
            <?php endif; ?>
        </div>

        <div id="customModal" class="modal-overlay" style="display: none;">
            <div class="modal-card">
                <button class="modal-close" id="modalClose"><i class="fa-solid fa-xmark"></i></button>
                <div class="modal-content">
                    <h2 id="modalTitle" class="form-card__title">Título del Modal</h2>
                    <p id="modalText">Este es el texto descriptivo del modal.</p>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-outline" id="modalReject">Rechazar</button>
                    <button type="button" class="btn-primary" id="modalAccept">Aceptar</button>
                </div>
            </div>
        </div>
    </header>