<?php
// $activePage debe estar definida en la página que incluye este archivo
$activePage = $activePage ?? 'home';
?>
<aside class="site-sidebar">

    <div class="nav-section">
        <a href="/index.php" class="nav-item <?= $activePage === 'home' ? 'active' : '' ?>">
            <span class="nav-icon">&#127968;</span> Principal
        </a>
        <a href="/pages/popular.php" class="nav-item <?= $activePage === 'popular' ? 'active' : '' ?>">
            <span class="nav-icon">&#128293;</span> Popular
        </a>
        <a href="/pages/explore.php" class="nav-item <?= $activePage === 'explore' ? 'active' : '' ?>">
            <span class="nav-icon">&#128269;</span> Explorar
        </a>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'moderator'): ?>
    <div class="nav-section">
        <div class="nav-label">Moderación</div>
        <a href="/pages/modmail.php" class="nav-item <?= $activePage === 'modmail' ? 'active' : '' ?>">
            <span class="nav-icon">&#128140;</span> Mod mail
        </a>
        <a href="/pages/moderators.php" class="nav-item">
            <span class="nav-icon">&#128100;</span> Moderadores
        </a>
    </div>
    <?php endif; ?>

    <div class="nav-section">
        <a href="/pages/best-english.php" class="nav-item <?= $activePage === 'best-english' ? 'active' : '' ?>">
            <span class="nav-icon">&#127758;</span> Lo mejor en Inglés
        </a>
        <a href="/pages/best-lang.php" class="nav-item <?= $activePage === 'best-lang' ? 'active' : '' ?>">
            <span class="nav-icon">&#128172;</span> Lo mejor en tu idioma
        </a>
        <a href="/pages/topics.php" class="nav-item <?= $activePage === 'topics' ? 'active' : '' ?>">
            <span class="nav-icon">&#127381;</span> Temas
        </a>
    </div>

    <div class="nav-section">
        <a href="/pages/advertise.php" class="nav-item">
            <span class="nav-icon">&#128227;</span> Anunciarse
        </a>
        <a href="/pages/help.php" class="nav-item">
            <span class="nav-icon">&#10067;</span> Ayuda
        </a>
        <a href="/pages/rules.php" class="nav-item">
            <span class="nav-icon">&#128203;</span> Reglas de AntHive
        </a>
        <a href="/pages/privacy.php" class="nav-item">
            <span class="nav-icon">&#128274;</span> Política de privacidad
        </a>
        <a href="/pages/terms.php" class="nav-item">
            <span class="nav-icon">&#128196;</span> Acuerdo de usuario
        </a>
        <a href="/pages/accessibility.php" class="nav-item">
            <span class="nav-icon">&#9855;</span> Accesibilidad
        </a>
    </div>

    <div class="sidebar-footer">
        AntHive, Inc. &copy; <?= date('Y') ?>. Todos los derechos reservados.<br>
        <a href="/pages/privacy.php">Privacidad</a> &middot;
        <a href="/pages/terms.php">Términos</a>
    </div>

</aside>