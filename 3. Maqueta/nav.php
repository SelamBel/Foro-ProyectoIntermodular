<nav id="navbar">
        <a href="index.php" class="logo-link">
            <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#fff;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#ed1838;stop-opacity:1" />
                    </linearGradient>
                </defs>
                <polygon points="50,10 20,50 50,90 80,50" fill="none" stroke="url(#logoGrad)" stroke-width="3" />
                <circle cx="50" cy="50" r="5" fill="url(#logoGrad)" />
            </svg>
            
            <span class="logo-text">DEV SEL</span>
        </a>
        <ul class="nav-links">
            <li><a href="index.php" class="nav-link <?php echo $paginaActual == 'index.php' ? 'active' : ''; ?>">Inicio</a></li>
            <li><a href="proyectos.php" class="nav-link <?php echo $paginaActual == 'proyectos.php' && $categoriaSeleccionada == null ? 'active' : ''; ?>">Proyectos</a></li>
            <li><a class="nav-link <?php echo $categoriaSeleccionada !== null ? 'active' : ''; ?>">Categorias</a>
                <ul>
                    <?php
                    foreach ($categorias as $categoria) {
                        echo "<li><a href='proyectos.php?cat=" . $categoria . "' class='nav-link " . ($categoriaSeleccionada == $categoria ? 'active' : '') . "'>" . htmlspecialchars($categoria) . "</a></li>";
                    }
                    ?>
                </ul>
            </li>
            <li><a href="contacto.php" class="nav-link <?php echo $paginaActual == 'contacto.php' ? 'active' : ''; ?>">Contacto</a></li>
        </ul>
</nav>