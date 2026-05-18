<?php
$activePage = $activePage ?? 'home';
?>
<aside class="site-sidebar">

    <div class="nav-section">
        <a href="/index.php" class="nav-item <?= $activePage === 'home' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-house"></i></span> Principal
        </a>
        <a href="/pages/popular.php" class="nav-item <?= $activePage === 'popular' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-fire"></i></span> Popular
        </a>
        <a href="/pages/explore.php" class="nav-item <?= $activePage === 'explore' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-compass"></i></span> Explorar
        </a>
        <a href="/pages/themes.php" class="nav-item <?= $activePage === 'themes' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-tags"></i></span> Temas
        </a>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'moderator'): ?>
        <div class="nav-section">
            <div class="nav-label">Moderación</div>
            <a href="/pages/modpage.php" class="nav-item <?= $activePage === 'modpage' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Mod Page
            </a>
            <a href="/pages/modusers.php" class="nav-item <?= $activePage === 'modusers' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-user-shield"></i></span> Mod Users
            </a>
        </div>
    <?php endif; ?>

    <div class="nav-section">
        <a href="/pages/help.php" class="nav-item <?= $activePage === 'help' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Ayuda
        </a>
        <a href="/pages/rules.php" class="nav-item <?= $activePage === 'rules' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span> Reglas de AntNet
        </a>
        <a href="/pages/privacy.php" class="nav-item <?= $activePage === 'privacy' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-lock"></i></span> Política de privacidad
        </a>
        <a href="/pages/terms.php" class="nav-item <?= $activePage === 'terms' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-file-contract"></i></span> Acuerdo de usuario
        </a>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="nav-section">
            <a href="/pages/profile.php" class="nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
                <span class="nav-icon"><i class="fa-solid fa-circle-user"></i></span> <?= htmlspecialchars($_SESSION['username']) ?>
            </a>
            <a id="logoutBtn" href="/pages/logout.php" class="nav-item nav-item--danger">
                <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span> Cerrar sesión
            </a>
        </div>
    <?php endif; ?>

    <div class="sidebar-footer">
        AntNet, Inc. &copy; <?= date('Y') ?>. Todos los derechos reservados.<br>
        <a href="/pages/privacy.php">Privacidad</a> &middot;
        <a href="/pages/terms.php">Términos</a>
    </div>

</aside>