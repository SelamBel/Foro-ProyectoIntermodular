<?php
session_start();

$pageTitle  = 'Reglas de AntHive';
$activePage = 'rules';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-clipboard-list"></i> Reglas de la comunidad</h1>

            <p style="margin-bottom:24px; color:#3c3c3c; line-height:1.7">
                AntHive es una comunidad construida sobre el respeto mutuo y la colaboración. Estas reglas aplican a todas las publicaciones, comentarios y mensajes dentro de la plataforma.
            </p>

            <div class="help-section">
                <h2>1. Respeto ante todo</h2>
                <p>Trata a los demás usuarios con respeto. No se tolerarán insultos, ataques personales, acoso ni ningún tipo de comportamiento hostil hacia otros miembros de la comunidad.</p>
            </div>

            <div class="help-section">
                <h2>2. Contenido relevante</h2>
                <p>Publica contenido relacionado con la temática del foro. Las publicaciones fuera de lugar o sin relación con la comunidad podrán ser eliminadas por los moderadores.</p>
            </div>

            <div class="help-section">
                <h2>3. No spam</h2>
                <p>Está prohibido publicar contenido repetitivo, publicidad no solicitada, enlaces de afiliados o cualquier forma de spam. Las cuentas que incumplan esta norma serán suspendidas.</p>
            </div>

            <div class="help-section">
                <h2>4. Información veraz</h2>
                <p>No difundas información falsa o engañosa. Si no estás seguro de algo, indícalo claramente. La desinformación perjudica a toda la comunidad.</p>
            </div>

            <div class="help-section">
                <h2>5. Privacidad</h2>
                <p>No compartas datos personales de otras personas sin su consentimiento. Esto incluye fotografías, direcciones, números de teléfono u otra información identificable.</p>
            </div>

            <div class="help-section">
                <h2>6. Contenido apropiado</h2>
                <p>Queda prohibido publicar contenido violento, explícito o ilegal. AntHive es una plataforma apta para todos los públicos y debe mantenerse así.</p>
            </div>

            <div class="help-section">
                <h2>7. Uso correcto de los votos</h2>
                <p>Los votos deben reflejar la calidad del contenido, no usarse para penalizar a usuarios con los que no estás de acuerdo. El abuso del sistema de votos puede derivar en restricciones de cuenta.</p>
            </div>

            <div class="help-section">
                <h2>8. Respeta a los moderadores</h2>
                <p>Las decisiones de moderación se toman para mantener la comunidad sana. Si no estás de acuerdo con una decisión, puedes comunicarte con el equipo de forma respetuosa a través del Mod mail.</p>
            </div>

            <div class="help-section">
                <h2>9. El value es clave</h2>
                <p>Aquí valoramos a la gente que sabe exprimir el value de cada situación. Si no sabes, no hagas overextend, y pirate.</p>
            </div>

            <div class="help-section">
                <h2>Consecuencias</h2>
                <p>El incumplimiento de estas normas puede resultar en la eliminación del contenido, restricciones temporales o la suspensión permanente de la cuenta, según la gravedad de la infracción.</p>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>