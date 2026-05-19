<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../config/lang.php';
require_once __DIR__ . '/../models/Publication.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = trim($_POST['title']   ?? '');
    $content = trim($_POST['content'] ?? '');

    if (empty($title) || empty($content)) {
        $error = t('create_post.error_required');
    } elseif (strlen($title) > 300) {
        $error = t('create_post.error_length');
    } else {
        $pubModel = new Publication();
        $id = $pubModel->create($_SESSION['user_id'], $title, $content);
        if (!empty($_FILES['images']['tmp_name'][0])) {
            $pubModel->saveImages($id, $_FILES['images']);
        }

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
            <h1 class="form-card__title"><?= t('create_post.title') ?></h1>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/pages/create-post.php" id="createPostForm" enctype="multipart/form-data" novalidate>
                <div class="form-group">
                    <label for="title"><?= t('create_post.label_title') ?></label>
                    <input type="text" id="title" name="title" maxlength="300"
                        value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
                        placeholder="<?= t('create_post.placeholder_title') ?>">
                    <span class="field-error" id="titleError"></span>
                </div>

                <div class="form-group">
                    <label for="content"><?= t('create_post.label_content') ?></label>
                    <textarea id="content" name="content" rows="10"
                        placeholder="<?= t('create_post.placeholder_content') ?>"><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                    <span class="field-error" id="contentError"></span>
                </div>

                <div class="form-group">
                    <label for="images"><?= t('create_post.label_images') ?></label>
                    <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
                </div>

                <div class="form-actions">
                    <a href="/index.php" class="btn-outline js-cancel-btn"><?= t('create_post.btn_cancel') ?></a>
                    <button type="submit" class="btn-primary"><?= t('create_post.btn_submit') ?></button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>