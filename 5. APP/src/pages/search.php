<?php
session_start();

require_once __DIR__ . '/../models/Publication.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/User.php';

$q = trim($_GET['q'] ?? '');

$posts    = [];
$comments = [];
$users    = [];

if (strlen($q) >= 2) {
    $pubModel     = new Publication();
    $commentModel = new Comment();
    $userModel    = new User();

    $posts    = $pubModel->getAllFiltered('newest', $q);
    $comments = $commentModel->search($q);
    $users    = $userModel->search($q, $_SESSION['user_id'] ?? 0);
}

$pageTitle  = $q ? 'Búsqueda: ' . htmlspecialchars($q) : 'Buscar';
$activePage = '';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <div class="form-card">
            <h1 class="form-card__title"><i class="fa-solid fa-magnifying-glass"></i> Búsqueda</h1>

            <form method="GET" action="/pages/search.php" style="margin-bottom:20px">
                <div class="form-group">
                    <div class="input-icon-right">
                        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Buscar publicaciones, comentarios, usuarios...">
                        <button type="submit" class="toggle-password"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </form>

            <?php if (strlen($q) < 2): ?>
                <p style="color:var(--muted)">Escribe al menos 2 caracteres para buscar.</p>
            <?php else: ?>

                <div class="search-section">
                    <h2 class="comments-title"><i class="fa-solid fa-newspaper"></i> Publicaciones (<?= count($posts) ?>)</h2>
                    <?php if (empty($posts)): ?>
                        <p style="color:var(--muted); font-size:13px">Sin resultados.</p>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <article class="post">
                                <div class="post-inner">
                                    <div class="post-meta">
                                        <span class="author"><?= htmlspecialchars($post['username']) ?></span>
                                        &middot;
                                        <span class="post-date" data-date="<?= $post['date_creation'] ?>"><?= $post['date_creation'] ?></span>
                                    </div>
                                    <h2 class="post-title">
                                        <a href="/pages/post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                                    </h2>
                                    <p class="post-body"><?= htmlspecialchars($post['content']) ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="search-section">
                    <h2 class="comments-title"><i class="fa-solid fa-comments"></i> Comentarios (<?= count($comments) ?>)</h2>
                    <?php if (empty($comments)): ?>
                        <p style="color:var(--muted); font-size:13px">Sin resultados.</p>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <div class="post">
                                <div class="post-inner">
                                    <div class="post-meta">
                                        <span class="author"><?= htmlspecialchars($c['username']) ?></span>
                                        &middot;
                                        <span class="post-date" data-date="<?= $c['date_creation'] ?>"><?= $c['date_creation'] ?></span>
                                        &middot;
                                        <a href="/pages/post.php?id=<?= $c['id_publication'] ?>">Ver publicación</a>
                                    </div>
                                    <p class="comment-body"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="search-section">
                    <h2 class="comments-title"><i class="fa-solid fa-users"></i> Usuarios (<?= count($users) ?>)</h2>
                    <?php if (empty($users)): ?>
                        <p style="color:var(--muted); font-size:13px">Sin resultados.</p>
                    <?php else: ?>
                        <div class="conversation-list">
                            <?php foreach ($users as $u): ?>
                                <div class="conversation-item">
                                    <div class="conv-avatar">
                                        <?php if (!empty($u['avatar'])): ?>
                                            <img src="<?= htmlspecialchars($u['avatar']) ?>" alt="">
                                        <?php else: ?>
                                            <i class="fa-solid fa-circle-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="conv-info">
                                        <span class="conv-username"><?= htmlspecialchars($u['username']) ?></span>
                                        <span class="conv-preview">Miembro desde <?= date('d/m/Y', strtotime($u['date_registered'])) ?></span>
                                    </div>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <a href="/pages/conversation.php?user=<?= $u['id'] ?>" class="btn-outline">
                                            <i class="fa-solid fa-paper-plane"></i> Mensaje
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>