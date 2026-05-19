<?php
session_start();

require_once __DIR__ . '/models/Publication.php';

$order  = in_array($_GET['order'] ?? '', ['votes', 'newest', 'oldest']) ? $_GET['order'] : 'votes';
$page   = max(1, (int) ($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$pubModel = new Publication();
$posts    = $pubModel->getAll($order, $limit, $offset);
$total    = $pubModel->countAll();
$pages    = (int) ceil($total / $limit);

$extraCss = ['feed.css'];
$pageTitle  = 'Principal';
$activePage = 'home';
require_once __DIR__ . '/config/lang.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>

    <main class="site-main">

        <div class="sort-bar">
            <a href="?order=votes" class="sort-btn <?= $order === 'votes'  ? 'active' : '' ?>"><i class="fa-solid fa-arrow-up-wide-short"></i><?= t('home.sort_votes') ?></a>
            <a href="?order=newest" class="sort-btn <?= $order === 'newest' ? 'active' : '' ?>"><i class="fa-solid fa-clock"></i><?= t('home.sort_newest') ?></a>
            <a href="?order=oldest" class="sort-btn <?= $order === 'oldest' ? 'active' : '' ?>"><i class="fa-solid fa-hourglass-start"></i><?= t('home.sort_oldest') ?></a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/pages/create-post.php" class="btn-primary" style="margin-left:auto">
                    <i class="fa-solid fa-plus"></i> <?= t('home.new_post') ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-wind"></i>
                <p><?= t('home.empty_state') ?></p>
            </div>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php $images = $pubModel->getImages($post['id']); ?>
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
                        <a href="/pages/post.php?id=<?= $post['id'] ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h2>
                    <p class="post-body"><?= htmlspecialchars($post['content']) ?></p>

                    <?php if (!empty($images)): ?>
                        <div class="post-images">
                            <?php foreach ($images as $img): ?>
                                <a class="post-image-link" href="<?= htmlspecialchars($img['path']) ?>" data-fancybox="galeria">
                                    <img src="<?= htmlspecialchars($img['path']) ?>" alt="" class="post-image">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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
                            <i class="fa-solid fa-comment"></i>
                            <?= $post['comment_count'] ?> <?= t('home.comments_count') ?>
                        </a>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['id_user']): ?>
                            <a href="/pages/edit-post.php?id=<?= $post['id'] ?>" class="action-btn">
                                <i class="fa-solid fa-pen"></i> <?= t('home.edit_post') ?>
                            </a>
                            <button class="action-btn js-delete-post" data-id="<?= $post['id'] ?>">
                                <i class="fa-solid fa-trash"></i> <?= t('home.delete_post') ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?order=<?= $order ?>&page=<?= $page - 1 ?>" class="page-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="?order=<?= $order ?>&page=<?= $i ?>"
                        class="page-btn <?= $i === $page ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $pages): ?>
                    <a href="?order=<?= $order ?>&page=<?= $page + 1 ?>" class="page-btn">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>