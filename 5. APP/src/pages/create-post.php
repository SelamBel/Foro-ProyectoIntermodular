<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../models/Publication.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']   ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = 'El título y el contenido son obligatorios.';
    } elseif (strlen($title) > 300) {
        $error = 'El título no puede superar los 300 caracteres.';
    } else {
        $pubModel = new Publication();
        $id = $pubModel->create($_SESSION['user_id'], $title, $content);
        header('Location: /pages/post.php?id=' . $id);
        exit;
    }
}

$pageTitle  = 'Nueva publicación';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title">Nueva publicación</h1>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/pages/create-post.php" id="createPostForm" novalidate>
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" id="title" name="title" maxlength="300"
                        value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                        placeholder="¿De qué trata tu publicación?">
                    <span class="field-error" id="titleError"></span>
                </div>

                <div class="form-group">
                    <label for="content">Contenido</label>
                    <textarea id="content" name="content" rows="10"
                        placeholder="Escribe aquí tu publicación..."><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                    <span class="field-error" id="contentError"></span>
                </div>

                <div class="form-actions">
                    <a href="/index.php" class="btn-outline js-cancel-btn">Cancelar</a>
                    <button type="submit" class="btn-primary">Publicar</button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>