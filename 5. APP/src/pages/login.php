<?php
session_start();

// Si ya está logado, redirigir al home
if (isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit;
}

require_once __DIR__ . '/../models/User.php';

$error = '';
$prefillEmail = htmlspecialchars($_GET['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Por favor, rellena todos los campos.';
    } else {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            $roles = $userModel->getRoles($user['id']);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username']    = $user['username'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = in_array('moderator', $roles) ? 'moderator' : 'user';
            $_SESSION['avatar'] = $user['avatar'] ?? null;

            header('Location: /index.php');
            exit;
        } else {
            $error = 'Email o contraseña incorrectos.';
        }
    }
}

$pageTitle  = 'Iniciar sesión';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h1 class="auth-title">Iniciar sesión</h1>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/pages/login.php" id="loginForm" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                    value="<?= htmlspecialchars($_POST['email'] ?? $_GET['email'] ?? '') ?>"
                    placeholder="tu@email.com" autocomplete="email">
                <span class="field-error" id="emailError"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-icon-right">
                    <input type="password" id="password" name="password"
                        placeholder="Tu contraseña" autocomplete="current-password">
                    <button type="button" class="toggle-password" tabindex="-1">
                        <i class="fa-solid fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                <span class="field-error" id="passwordError"></span>
            </div>

            <button type="submit" class="btn-primary btn-full">Entrar</button>
        </form>

        <p class="auth-footer">
            ¿No tienes cuenta?
            <a href="/pages/register.php">Regístrate</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>