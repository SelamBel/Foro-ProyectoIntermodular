<?php
session_start();

$pageTitle  = 'Política de privacidad';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-lock"></i> Política de privacidad</h1>

            <p style="margin-bottom:24px; color:#3c3c3c; line-height:1.7">
                Última actualización: <?= date('d/m/Y') ?>. En AntHive nos tomamos tu privacidad en serio. Esta política explica qué datos recogemos, cómo los usamos y qué derechos tienes sobre ellos.
            </p>

            <div class="help-section">
                <h2>1. Datos que recogemos</h2>
                <p>Al registrarte, recogemos tu nombre de usuario, dirección de email y contraseña (almacenada de forma cifrada). Si subes una foto de perfil, también se almacena en nuestros servidores. No recogemos datos de pago ni información sensible adicional.</p>
            </div>

            <div class="help-section">
                <h2>2. Cómo usamos tus datos</h2>
                <p>Tus datos se usan exclusivamente para el funcionamiento de la plataforma: identificarte como usuario, mostrar tu perfil y contenido, y gestionar tu sesión. No vendemos ni compartimos tus datos con terceros.</p>
            </div>

            <div class="help-section">
                <h2>3. Cookies y sesiones</h2>
                <p>AntHive usa cookies de sesión para mantenerte identificado mientras navegas. Estas cookies son estrictamente necesarias y desaparecen al cerrar el navegador o al cerrar sesión. No usamos cookies de seguimiento ni publicidad.</p>
            </div>

            <div class="help-section">
                <h2>4. Almacenamiento de datos</h2>
                <p>Tus datos se almacenan en servidores seguros. Las contraseñas se guardan siempre cifradas mediante bcrypt y nunca son accesibles en texto plano, ni siquiera por el equipo de AntHive.</p>
            </div>

            <div class="help-section">
                <h2>5. Contenido publicado</h2>
                <p>Las publicaciones y comentarios que crees son visibles para todos los usuarios de la plataforma. Si eliminas una publicación o comentario, se borrará de la base de datos de forma permanente.</p>
            </div>

            <div class="help-section">
                <h2>6. Tus derechos</h2>
                <p>Tienes derecho a acceder, rectificar y eliminar tus datos en cualquier momento. Puedes editar tu perfil desde la sección de perfil o solicitar la eliminación completa de tu cuenta escribiendo a <a href="mailto:privacidad@anthive.com">privacidad@anthive.com</a>.</p>
            </div>

            <div class="help-section">
                <h2>7. Cambios en esta política</h2>
                <p>Podemos actualizar esta política ocasionalmente. Los cambios significativos se comunicarán a los usuarios mediante un aviso en la plataforma. El uso continuado de AntHive tras los cambios implica la aceptación de la nueva política.</p>
            </div>

            <div class="help-section">
                <h2>Contacto</h2>
                <p>Para cualquier consulta sobre privacidad, contacta con nosotros en <a href="mailto:privacidad@anthive.com">privacidad@anthive.com</a>.</p>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>