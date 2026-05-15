<?php
session_start();

$pageTitle  = 'Acuerdo de usuario';
$activePage = 'terms';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-file-contract"></i> Acuerdo de usuario</h1>

            <p style="margin-bottom:24px; color:#3c3c3c; line-height:1.7">
                Última actualización: <?= date('d/m/Y') ?>. Al registrarte y usar AntHive, aceptas los términos descritos en este acuerdo. Léelo detenidamente antes de usar la plataforma.
            </p>

            <div class="help-section">
                <h2>1. Aceptación de los términos</h2>
                <p>Al crear una cuenta en AntHive, aceptas este acuerdo en su totalidad. Si no estás de acuerdo con alguna parte, no debes usar la plataforma.</p>
            </div>

            <div class="help-section">
                <h2>2. Elegibilidad</h2>
                <p>Para registrarte en AntHive debes tener al menos 14 años. Al crear una cuenta confirmas que cumples este requisito.</p>
            </div>

            <div class="help-section">
                <h2>3. Tu cuenta</h2>
                <p>Eres responsable de mantener la confidencialidad de tus credenciales y de toda la actividad que se realice desde tu cuenta. Notifícanos inmediatamente si sospechas de un acceso no autorizado.</p>
            </div>

            <div class="help-section">
                <h2>4. Contenido del usuario</h2>
                <p>Eres el único responsable del contenido que publicas. Al publicar en AntHive, nos concedes una licencia no exclusiva para mostrar ese contenido dentro de la plataforma. No reclamamos la propiedad de tu contenido.</p>
            </div>

            <div class="help-section">
                <h2>5. Conducta prohibida</h2>
                <p>Queda prohibido usar AntHive para actividades ilegales, difamar a terceros, suplantar identidades, distribuir malware o intentar acceder de forma no autorizada a los sistemas de la plataforma.</p>
            </div>

            <div class="help-section">
                <h2>6. Moderación y suspensión</h2>
                <p>Nos reservamos el derecho de eliminar contenido o suspender cuentas que incumplan este acuerdo o las reglas de la comunidad, sin previo aviso y a nuestra entera discreción.</p>
            </div>

            <div class="help-section">
                <h2>7. Limitación de responsabilidad</h2>
                <p>AntHive se proporciona tal cual, sin garantías de disponibilidad continua. No somos responsables de daños derivados del uso o la imposibilidad de uso de la plataforma.</p>
            </div>

            <div class="help-section">
                <h2>8. Modificaciones</h2>
                <p>Podemos modificar este acuerdo en cualquier momento. Los cambios entrarán en vigor tras su publicación. El uso continuado de la plataforma implica la aceptación de los nuevos términos.</p>
            </div>

            <div class="help-section">
                <h2>Contacto</h2>
                <p>Para cualquier consulta sobre este acuerdo, escríbenos a <a href="mailto:legal@anthive.com">legal@anthive.com</a>.</p>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>