<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username     = trim($_POST['username']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = 'Por favor, rellena todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $userModel = new User();

        if ($userModel->emailExists($email)) {
            $error = 'Este email ya está registrado.';
        } else {
            $userModel->create($email, $username, $password);
            $success = '¡Cuenta creada! Ya puedes iniciar sesión.';
        }
    }
}

$pageTitle  = 'Registrarse';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Crear cuenta</h1>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pages/register.php" id="registerForm" novalidate>
            <div class="form-group">
                <label for="username">Nombre</label>
                <input type="text" id="username" name="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    placeholder="Tu nombre de usuario" autocomplete="given-username">
                <span class="field-error" id="nameError"></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    placeholder="tu@email.com" autocomplete="email">
                <span class="field-error" id="emailError"></span>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon-right">
                    <input type="password" id="password" name="password"
                        placeholder="Mínimo 8 caracteres" autocomplete="new-password">
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <span class="field-error" id="passwordError"></span>
            </div>

            <div class="form-group">
                <label for="confirm">Repetir contraseña</label>
                <div class="input-icon-right">
                    <input type="password" id="confirm" name="confirm"
                        placeholder="Repite la contraseña" autocomplete="new-password">
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <span class="field-error" id="confirmError"></span>
            </div>

            <button type="submit" class="btn-primary btn-full">Crear cuenta</button>
        </form>

        <p class="auth-footer">
            ¿Ya tienes cuenta?
            <a href="/pages/login.php">Inicia sesión</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>