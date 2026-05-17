<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /pages/login.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Publication.php';

$userModel = new User();
$pubModel  = new Publication();

$user  = $userModel->findById($_SESSION['user_id']);
$posts = $pubModel->getByUser($_SESSION['user_id']);

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'password') {
    $current = $_POST['current_password'] ?? '';
    $new     = trim($_POST['new_password']     ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    if (!$userModel->verifyPassword($current, $user['password'])) {
        $error = 'La contraseña actual no es correcta.';
    } elseif (strlen($new) < 8) {
        $error = 'La nueva contraseña debe tener al menos 8 caracteres.';
    } elseif ($new !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $userModel->updatePassword($_SESSION['user_id'], $new);
        $success = 'Contraseña actualizada correctamente.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'profile') {
    $username = trim($_POST['username'] ?? '');

    if (strlen($username) < 3) {
        $error = 'Nombre de usuario debe tener al menos 3 caracteres.';
    } elseif ($userModel->usernameExists($username, $_SESSION['user_id'])) {
        $error = 'Este nombre de usuario ya está en uso.';
    } else {
        $avatarPath = $user['avatar'] ?? null;

        if (!empty($_FILES['avatar']['name'])) {
            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Error al subir el archivo.';
            } elseif (!is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                $error = 'Archivo de subida no válido.';
            } else {
                $allowed   = ['image/jpeg', 'image/png', 'image/webp'];
                $mimeType  = mime_content_type($_FILES['avatar']['tmp_name']);

                if (!in_array($mimeType, $allowed)) {
                    $error = 'Solo se permiten imágenes JPG, PNG o WEBP.';
                } else {
                    $ext        = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $filename   = time() . '.' . $ext;
                    $uploadPath = __DIR__ . '/../assets/img/avatars/' . $filename;

                    if (!is_dir(dirname($uploadPath))) {
                        mkdir(dirname($uploadPath), 0775, true);
                    }

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                        $avatarPath = '/assets/img/avatars/' . $filename;
                    } else {
                        $error = 'Error al guardar el archivo.';
                    }
                }
            }
        }

        if (!$error) {
            $userModel->update($_SESSION['user_id'], $username,  $avatarPath);
            $_SESSION['username'] = $username;
            $user = $userModel->findById($_SESSION['user_id']);
            $success = 'Perfil actualizado correctamente.';
        }
    }
}

$pageTitle  = 'Mi perfil';
$activePage = 'profile';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar">
                    <?php else: ?>
                        <i class="fa-solid fa-circle-user"></i>
                    <?php endif; ?>
                </div>
                <div class="profile-info">
                    <h1><?= htmlspecialchars($user['username']) ?></h1>
                    <span class="profile-role"><?= htmlspecialchars($_SESSION['role']) ?></span>
                    <span class="profile-since">Miembro desde <?= date('d/m/Y', strtotime($user['date_registered'])) ?></span>
                </div>
            </div>
            <div class="form-card">
                <h2 class="form-card__title">Editar perfil</h2>
                <form method="POST" enctype="multipart/form-data" id="profileForm" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <input type="hidden" name="action" value="profile">
                            <label for="username">Nombre</label>
                            <input type="text" id="username" name="username"
                                value="<?= htmlspecialchars($user['username']) ?>">
                            <span class="field-error" id="nameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="avatar">Foto de perfil</label>
                            <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="form-card" style="margin-top:16px">
                <h2 class="form-card__title">Cambiar contraseña</h2>

                <form method="POST" id="passwordForm" novalidate>
                    <input type="hidden" name="action" value="password">
                    <div class="form-group">
                        <label for="current_password">Contraseña actual</label>
                        <div class="input-icon-right">
                            <input type="password" id="current_password" name="current_password" placeholder="Tu contraseña actual">
                            <button type="button" class="toggle-password" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <span class="field-error" id="currentPasswordError"></span>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Nueva contraseña</label>
                        <div class="input-icon-right">
                            <input type="password" id="new_password" name="new_password" placeholder="Mínimo 8 caracteres">
                            <button type="button" class="toggle-password" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <span class="field-error" id="newPasswordError"></span>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Repetir nueva contraseña</label>
                        <div class="input-icon-right">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Repite la nueva contraseña">
                            <button type="button" class="toggle-password" tabindex="-1"><i class="fa-solid fa-eye"></i></button>
                        </div>
                        <span class="field-error" id="confirmPasswordError"></span>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Cambiar contraseña</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-posts">
            <h2 class="comments-title">
                <i class="fa-solid fa-newspaper"></i> Mis publicaciones
            </h2>

            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-wind"></i>
                    <p>Todavía no has publicado nada.</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <div class="post-inner">
                            <div class="post-meta">
                                <span class="post-date" data-date="<?= $post['date_creation'] ?>"><?= $post['date_creation'] ?></span>
                            </div>
                            <h2 class="post-title">
                                <a href="/pages/post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h2>
                            <p class="post-body"><?= htmlspecialchars($post['content']) ?></p>
                            <div class="post-actions">
                                <div class="vote-group">
                                    <button class="vote-btn up js-vote" data-id="<?= $post['id'] ?>" data-type="1">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        <span class="upvote-count"><?= $post['upvotes'] ?></span>
                                    </button>
                                    <button class="vote-btn down js-vote" data-id="<?= $post['id'] ?>" data-type="0">
                                        <i class="fa-solid fa-arrow-down"></i>
                                        <span class="downvote-count"><?= $post['downvotes'] ?></span>
                                    </button>
                                </div>
                                <a href="/pages/post.php?id=<?= $post['id'] ?>" class="action-btn">
                                    <i class="fa-solid fa-comment"></i> <?= $post['comment_count'] ?> comentarios
                                </a>
                                <a href="/pages/edit-post.php?id=<?= $post['id'] ?>" class="action-btn">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </a>
                                <button class="action-btn js-delete-post" data-id="<?= $post['id'] ?>">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>