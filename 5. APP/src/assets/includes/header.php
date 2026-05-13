<?php
$pageTitle = $pageTitle ?? 'AntHive';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — AntHive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

<header class="site-header">
    <a href="/index.php" class="header-logo">
        <img src="/assets/img/Logo Finished Base.svg" alt="AntHive logo"
             onerror="this.style.display='none'">
        <span>AntHive</span>
    </a>

    <div class="header-search">
        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <input type="text" placeholder="Buscar en AntHive" id="searchInput">
    </div>

    <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/pages/messages.php" class="icon-btn" title="Mensajes">
                <i class="fa-solid fa-envelope"></i>
            </a>
            <a href="/pages/notifications.php" class="icon-btn" title="Notificaciones">
                <i class="fa-solid fa-bell"></i>
            </a>
            <a href="/pages/profile.php" class="icon-btn" title="Mi perfil">
                <i class="fa-solid fa-circle-user"></i>
            </a>
        <?php else: ?>
            <a href="/pages/login.php" class="btn-outline">Iniciar sesión</a>
            <a href="/pages/register.php" class="btn-primary">Registrarse</a>
        <?php endif; ?>
    </div>
</header>