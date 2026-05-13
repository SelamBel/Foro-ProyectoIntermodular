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
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'moderator'): ?>
    <div class="nav-section">
        <div class="nav-label">Moderación</div>
        <a href="/pages/modmail.php" class="nav-item <?= $activePage === 'modmail' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Mod mail
        </a>
        <a href="/pages/moderators.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-user-shield"></i></span> Moderadores
        </a>
    </div>
    <?php endif; ?>

    <div class="nav-section">
        <a href="/pages/best-english.php" class="nav-item <?= $activePage === 'best-english' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-earth-americas"></i></span> Lo mejor en Inglés
        </a>
        <a href="/pages/best-lang.php" class="nav-item <?= $activePage === 'best-lang' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-message"></i></span> Lo mejor en tu idioma
        </a>
        <a href="/pages/topics.php" class="nav-item <?= $activePage === 'topics' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-tags"></i></span> Temas
        </a>
    </div>

    <div class="nav-section">
        <a href="/pages/advertise.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-bullhorn"></i></span> Anunciarse
        </a>
        <a href="/pages/help.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-circle-question"></i></span> Ayuda
        </a>
        <a href="/pages/rules.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-clipboard-list"></i></span> Reglas de AntHive
        </a>
        <a href="/pages/privacy.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-lock"></i></span> Política de privacidad
        </a>
        <a href="/pages/terms.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-file-contract"></i></span> Acuerdo de usuario
        </a>
        <a href="/pages/accessibility.php" class="nav-item">
            <span class="nav-icon"><i class="fa-solid fa-universal-access"></i></span> Accesibilidad
        </a>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="nav-section">
        <a href="/pages/profile.php" class="nav-item <?= $activePage === 'profile' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fa-solid fa-circle-user"></i></span> <?= htmlspecialchars($_SESSION['name']) ?>
        </a>
        <a href="/pages/logout.php" class="nav-item nav-item--danger">
            <span class="nav-icon"><i class="fa-solid fa-right-from-bracket"></i></span> Cerrar sesión
        </a>
    </div>
    <?php endif; ?>

    <div class="sidebar-footer">
        AntHive, Inc. &copy; <?= date('Y') ?>. Todos los derechos reservados.<br>
        <a href="/pages/privacy.php">Privacidad</a> &middot;
        <a href="/pages/terms.php">Términos</a>
    </div>

</aside>