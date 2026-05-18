<?php
session_start();

$pageTitle  = 'Ayuda';
$activePage = 'help';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-circle-question"></i> Ayuda</h1>

            <div class="help-section">
                <h2>¿Qué es AntNet?</h2>
                <p>AntNet es un foro temático inspirado en la mentalidad de colmena. Aquí puedes crear publicaciones, comentar, responder a otros usuarios y votar el contenido que más te guste.</p>
            </div>

            <div class="help-section">
                <h2>¿Cómo me registro?</h2>
                <p>Haz clic en <strong>Registrarse</strong> en la barra superior, rellena el formulario con tu nombre de usuario, email y contraseña, y confirma. Serás redirigido al login con tus datos ya introducidos.</p>
            </div>

            <div class="help-section">
                <h2>¿Cómo creo una publicación?</h2>
                <p>Inicia sesión y haz clic en <strong>Nueva publicación</strong> en la página principal. Escribe un título y el contenido, y pulsa <strong>Publicar</strong>.</p>
            </div>

            <div class="help-section">
                <h2>¿Cómo funciona el sistema de votos?</h2>
                <p>Cada publicación tiene dos botones de voto: <i class="fa-solid fa-arrow-up"></i> positivo y <i class="fa-solid fa-arrow-down"></i> negativo. Puedes votar una vez por publicación. Si vuelves a hacer clic en el mismo botón, el voto se deshace. Solo puedes votar si tienes sesión iniciada.</p>
            </div>

            <div class="help-section">
                <h2>¿Cómo comento o respondo?</h2>
                <p>Entra en una publicación y usa el formulario de la parte inferior para comentar. Para responder a un comentario específico, haz clic en <strong>Responder</strong> bajo ese comentario.</p>
            </div>

            <div class="help-section">
                <h2>¿Puedo editar o eliminar mis publicaciones?</h2>
                <p>Sí. En tus propias publicaciones y comentarios verás los botones <i class="fa-solid fa-pen"></i> y <i class="fa-solid fa-trash"></i>. Los moderadores pueden editar o eliminar cualquier contenido.</p>
            </div>

            <div class="help-section">
                <h2>¿Cómo cambio mi perfil?</h2>
                <p>Haz clic en tu nombre en el sidebar o en el icono de usuario del header para acceder a tu perfil. Desde allí puedes cambiar tu nombre de usuario y tu foto de perfil.</p>
            </div>

            <div class="help-section">
                <h2>¿Quiénes son los moderadores?</h2>
                <p>Los moderadores son usuarios de confianza con capacidad para gestionar el contenido del foro y mantener un ambiente respetuoso. Si crees que algún contenido incumple las normas, puedes reportarlo desde el propio post.</p>
            </div>

            <div class="help-section">
                <h2>Contacto</h2>
                <p>Si tienes algún problema técnico o quieres ponerte en contacto conmigo, escribe a <a href="conversation.php?user=1
                ">ayuda@antnet.com</a> o a <a href="https://github.com/SelamBel"> <i class="fa-brands fa-github"></i> SelamBel</a>.</p>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>