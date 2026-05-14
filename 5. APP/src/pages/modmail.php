<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'moderator') {
    header('Location: /index.php');
    exit;
}

require_once __DIR__ . '/../models/Publication.php';
require_once __DIR__ . '/../models/Comment.php';

$pubModel     = new Publication();
$commentModel = new Comment();

$tab  = in_array($_GET['tab'] ?? '', ['comments']) ? 'comments' : 'posts';
$page = max(1, (int) ($_GET['page'] ?? 1));

$limit  = 20;
$offset = ($page - 1) * $limit;

if ($tab === 'posts') {
    $items = $pubModel->getAll('newest', $limit, $offset);
    $total = $pubModel->countAll();
} else {
    $items = $commentModel->getAll($limit, $offset);
    $total = $commentModel->countAll();
}

$pages = (int) ceil($total / $limit);

$pageTitle  = 'Moderación';
$activePage = 'modmail';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">
        <h1 class="form-card__title" style="margin-bottom:16px">
            <i class="fa-solid fa-shield-halved"></i> Panel de moderación
        </h1>

        <div class="sort-bar" style="margin-bottom:16px">
            <a href="?tab=posts" class="sort-btn <?= $tab === 'posts'    ? 'active' : '' ?>"><i class="fa-solid fa-newspaper"></i> Publicaciones</a>
            <a href="?tab=comments" class="sort-btn <?= $tab === 'comments' ? 'active' : '' ?>"><i class="fa-solid fa-comments"></i> Comentarios</a>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-wind"></i>
                <p>No hay contenido.</p>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'posts'): ?>
            <?php foreach ($items as $post): ?>
                <article class="post">
                    <div class="post-inner">
                        <div class="post-meta">
                            <span class="author">u/<?= htmlspecialchars($post['name'] . ' ' . $post['surname']) ?></span>
                            &middot;
                            <span class="post-date" data-date="<?= $post['date_creation'] ?>"><?= $post['date_creation'] ?></span>
                        </div>
                        <h2 class="post-title">
                            <a href="/pages/post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                        </h2>
                        <p class="post-body"><?= htmlspecialchars($post['content']) ?></p>
                        <div class="post-actions">
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

        <?php else: ?>
            <?php foreach ($items as $comment): ?>
                <div class="post">
                    <div class="post-inner">
                        <div class="post-meta">
                            <span class="author">u/<?= htmlspecialchars($comment['name'] . ' ' . $comment['surname']) ?></span>
                            &middot;
                            <span class="post-date" data-date="<?= $comment['date_creation'] ?>"><?= $comment['date_creation'] ?></span>
                            &middot;
                            <a href="/pages/post.php?id=<?= $comment['id_publication'] ?>">Ver publicación</a>
                        </div>
                        <p class="comment-body"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        <div class="post-actions">
                            <button class="action-btn js-delete-comment"
                                data-id="<?= $comment['id'] ?>"
                                data-post="<?= $comment['id_publication'] ?>">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?tab=<?= $tab ?>&page=<?= $page - 1 ?>" class="page-btn"><i class="fa-solid fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="?tab=<?= $tab ?>&page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $pages): ?>
                    <a href="?tab=<?= $tab ?>&page=<?= $page + 1 ?>" class="page-btn"><i class="fa-solid fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>