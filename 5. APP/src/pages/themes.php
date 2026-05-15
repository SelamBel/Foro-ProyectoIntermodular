<?php
session_start();

$pageTitle  = 'Temas';
$activePage = 'themes';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-palette"></i> Temas</h1>

            <div class="help-section">
                <h2>Modo</h2>
                <div class="theme-options">
                    <button class="theme-btn" id="lightBtn">
                        <i class="fa-solid fa-sun"></i> Claro
                    </button>
                    <button class="theme-btn" id="darkBtn">
                        <i class="fa-solid fa-moon"></i> Oscuro
                    </button>
                </div>
            </div>

            <div class="help-section">
                <h2>Color principal</h2>
                <div class="color-options">
                    <button class="color-swatch active" data-color="#e20000" style="background:#e20000" title="Rojo"></button>
                    <button class="color-swatch" data-color="#0079d3" style="background:#0079d3" title="Azul"></button>
                    <button class="color-swatch" data-color="#ff6314" style="background:#ff6314" title="Naranja"></button>
                    <button class="color-swatch" data-color="#46d160" style="background:#46d160" title="Verde"></button>
                    <button class="color-swatch" data-color="#9c27b0" style="background:#9c27b0" title="Morado"></button>
                    <button class="color-swatch" data-color="#ff4081" style="background:#ff4081" title="Rosa"></button>
                    <div class="color-custom">
                        <label for="customColor">Color personalizado</label>
                        <input type="color" id="customColor" value="#e20000">
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>