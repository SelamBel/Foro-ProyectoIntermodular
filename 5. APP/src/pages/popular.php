<?php
session_start();

require_once __DIR__ . '/../models/Publication.php';

$pubModel = new Publication();
$posts    = $pubModel->getPopular();

if (empty($posts)) {
    $posts = $pubModel->getPopular(20, 30);
}

$pageTitle  = 'Popular';
$activePage = 'popular';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">

        <div class="sort-bar">
            <span class="sort-btn active"><i class="fa-solid fa-fire"></i> Popular esta semana</span>
            <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/pages/create-post.php" class="btn-primary" style="margin-left:auto">
                <i class="fa-solid fa-plus"></i> Nueva publicación
            </a>
            <?php endif; ?>
        </div>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-wind"></i>
                <p>No hay publicaciones populares todavía.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <article class="post">
                <div class="post-inner">
                    <div class="post-meta">
                        <?php if (!empty($post['avatar'])): ?>
                            <img src="<?= htmlspecialchars($post['avatar']) ?>" class="meta-avatar" alt="">
                        <?php else: ?>
                            <i class="fa-solid fa-circle-user meta-avatar-icon"></i>
                        <?php endif; ?>
                        <span class="author"><?= htmlspecialchars($post['username']) ?></span>
                        &middot;
                        <span class="post-date" data-date="<?= $post['date_creation'] ?>"><?= $post['date_creation'] ?></span>
                    </div>
                    <h2 class="post-title">
                        <a href="/pages/post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                    </h2>
                    <p class="post-body"><?= htmlspecialchars($post['content']) ?></p>
                    <div class="post-actions">
                        <div class="vote-group">
                            <button class="vote-btn up <?= isset($_SESSION['user_id']) ? 'js-vote' : 'js-vote-guest' ?>"
                                    data-id="<?= $post['id'] ?>" data-type="1">
                                <i class="fa-solid fa-arrow-up"></i>
                                <span class="upvote-count"><?= $post['upvotes'] ?></span>
                            </button>
                            <button class="vote-btn down <?= isset($_SESSION['user_id']) ? 'js-vote' : 'js-vote-guest' ?>"
                                    data-id="<?= $post['id'] ?>" data-type="0">
                                <i class="fa-solid fa-arrow-down"></i>
                                <span class="downvote-count"><?= $post['downvotes'] ?></span>
                            </button>
                        </div>
                        <a href="/pages/post.php?id=<?= $post['id'] ?>" class="action-btn">
                            <i class="fa-solid fa-comment"></i> <?= $post['comment_count'] ?> comentarios
                        </a>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['id_user']): ?>
                        <a href="/pages/edit-post.php?id=<?= $post['id'] ?>" class="action-btn">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <button class="action-btn js-delete-post" data-id="<?= $post['id'] ?>">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        <?php endif; ?>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>