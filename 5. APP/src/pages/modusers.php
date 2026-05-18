<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'moderator') {
    header('Location: /index.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$userModel = new User();
$error     = '';
$success   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action   = $_POST['action']  ?? '';
    $targetId = (int) ($_POST['id'] ?? 0);

    if ($action === 'edit' && $targetId) {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $roleIds  = $_POST['role_ids']      ?? [];

        if (strlen($username) < 2) {
            $error = 'El nombre de usuario debe tener al menos 2 caracteres.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif ($userModel->usernameExists($username, $targetId)) {
            $error = 'Ese nombre de usuario ya está en uso.';
        } else {
            $userModel->updateByMod($targetId, $username, $email, $roleIds);
            $success = 'Usuario actualizado correctamente.';
        }
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email']    ?? '');
        $roleIds  = $_POST['role_ids']      ??  [];

        if (strlen($username) < 2) {
            $error = 'El nombre de usuario debe tener al menos 2 caracteres.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif ($userModel->usernameExists($username, $targetId)) {
            $error = 'Ese nombre de usuario ya está en uso.';
        } else {
            $userModel->updateByMod($targetId, $username, $email, $roleIds);
            $success = 'Usuario actualizado correctamente.';
        }
    } elseif ($action === 'delete' && $targetId && $targetId !== $_SESSION['user_id']) {
        $userModel->delete($targetId);
        $success = 'Usuario eliminado.';
    } elseif ($action === 'remove_avatar' && $targetId) {
        $userModel->removeAvatar($targetId);
        $success = 'Avatar eliminado correctamente.';
    }
}

$users = $userModel->getAll();
$roles = $userModel->getRoleList();

$pageTitle  = 'Moderadores';
$activePage = 'modusers';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <h1 class="form-card__title" style="margin-bottom:16px">
            <i class="fa-solid fa-users-gear"></i> Gestión de usuarios
        </h1>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom:16px">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-bottom:16px">
                <i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <div class="mod-table-wrapper">
            <table class="mod-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Avatar</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Registrado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr id="row-<?= $u['id'] ?>">
                            <td><?= $u['id'] ?></td>
                            <td>
                                <?php if (!empty($u['avatar'])): ?>
                                    <img src="<?= htmlspecialchars($u['avatar']) ?>" class="meta-avatar" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-circle-user meta-avatar-icon" style="font-size:32px; color:var(--text-muted, #ccc);"></i>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($u['username']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars($u['roles_names'] ?: 'sin rol') ?></td>
                            <td><?= date('d/m/Y', strtotime($u['date_registered'])) ?></td>
                            <td>
                                <?php if (strpos($u['roles_names'] ?? '', 'moderator') === false || strpos($u['roles_names'] ?? '', 'sel') !== false): ?>
                                    <button class="action-btn js-edit-user"
                                        data-id="<?= $u['id'] ?>"
                                        data-username="<?= htmlspecialchars($u['username']) ?>"
                                        data-email="<?= htmlspecialchars($u['email']) ?>"
                                        data-roles="<?= htmlspecialchars($u['roles_ids'] ?? '') ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="action-btn js-delete-user" data-id="<?= $u['id'] ?>" data-username="<?= htmlspecialchars($u['username']) ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    <button class="action-btn js-remove-avatar"
                                        data-id="<?= $u['id'] ?>"
                                        title="Eliminar avatar">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mod-edit-panel" id="editPanel" style="display:none">
            <div class="form-card" style="margin-top:16px">
                <h2 class="form-card__title">Editar usuario</h2>
                <form method="POST" id="editUserForm" novalidate>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editId">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editUsername">Nombre de usuario</label>
                            <input type="text" id="editUsername" name="username">
                            <span class="field-error" id="editUsernameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" name="email">
                            <span class="field-error" id="editEmailError"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editRole">Roles</label>
                        <select id="editRole" name="role_ids[]" multiple size="<?= count($roles) ?>">
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-outline js-cancel-btn" id="cancelEdit">Cancelar</button>
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>