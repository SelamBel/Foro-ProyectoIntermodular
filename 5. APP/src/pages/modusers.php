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
        $roleId   = (int) ($_POST['role_id'] ?? 1);

        if (strlen($username) < 2) {
            $error = 'El nombre de usuario debe tener al menos 2 caracteres.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif ($userModel->usernameExists($username, $targetId)) {
            $error = 'Ese nombre de usuario ya está en uso.';
        } else {
            $userModel->updateByMod($targetId, $username, $email, $roleId);
            $success = 'Usuario actualizado correctamente.';
        }
    } elseif ($action === 'delete' && $targetId && $targetId !== $_SESSION['user_id']) {
        $userModel->delete($targetId);
        $success = 'Usuario eliminado.';
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
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['roles_names'] ?: 'sin rol') ?></td>
                        <td><?= date('d/m/Y', strtotime($u['date_registered'])) ?></td>
                        <td>
                            <button class="action-btn js-edit-user"
                                    data-id="<?= $u['id'] ?>"
                                    data-username="<?= htmlspecialchars($u['username']) ?>"
                                    data-email="<?= htmlspecialchars($u['email']) ?>"
                                    data-role="<?= $u['id_role'] ?? 1 ?>">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                            <button class="action-btn js-delete-user" data-id="<?= $u['id'] ?>" data-username="<?= htmlspecialchars($u['username']) ?>">
                                <i class="fa-solid fa-trash"></i>
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
                        <label for="editRole">Rol</label>
                        <select id="editRole" name="role_id">
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn-outline" id="cancelEdit js-cancel-btn">Cancelar</button>
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>