<?php
// $pageTitle debe estar definida en la página que incluye este archivo
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
        <span class="search-icon">&#128269;</span>
        <input type="text" placeholder="Buscar en AntHive" id="searchInput">
    </div>

    <div class="header-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/pages/messages.php" class="icon-btn" title="Mensajes">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v9a2 2 0 01-2 2H6l-4 3V4z"/>
                </svg>
            </a>
            <a href="/pages/notifications.php" class="icon-btn" title="Notificaciones">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a6 6 0 00-6 6v3l-1.5 2.5A1 1 0 003.5 15h13a1 1 0 00.86-1.5L16 11V8a6 6 0 00-6-6zM10 18a2 2 0 002-2H8a2 2 0 002 2z"/>
                </svg>
            </a>
            <a href="/pages/profile.php" class="icon-btn" title="Mi perfil">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </a>
        <?php else: ?>
            <a href="/pages/login.php" class="btn-outline">Iniciar sesión</a>
            <a href="/pages/register.php" class="btn-primary">Registrarse</a>
        <?php endif; ?>
    </div>
</header>