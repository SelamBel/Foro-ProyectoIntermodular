<?php
session_start();

require_once __DIR__ . '/../models/Publication.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';


$id = (int) ($_GET['id'] ?? 0);
if (!$id) {
    header('Location: /index.php');
    exit;
}

$pubModel     = new Publication();
$commentModel = new Comment();
$post         = $pubModel->getById($id);
$notifModel = new Notification();

if (!$post) {
    header('Location: /index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $content  = trim($_POST['content']   ?? '');
    $parentId = !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

    if (empty($content)) {
        $error = 'El comentario no puede estar vacío.';
    } else {
        $commentModel->create($id, $_SESSION['user_id'], $content, $parentId);

        if ($_SESSION['user_id'] != $post['id_user']) {
            $notifModel->create(
                $post['id_user'],
                'comment_on_post',
                '@' . $_SESSION['username'] . ' ha comentado en tu publicación: ' . mb_strimwidth($post['title'], 0, 50, '...'),
                '/pages/post.php?id=' . $id
            );
        }

        header('Location: /pages/post.php?id=' . $id);
        exit;
    }
}

$comments = $commentModel->getByPublication($id);

function buildTree(array $comments): array
{
    $tree = [];
    $map  = [];
    foreach ($comments as $c) {
        $c['children'] = [];
        $map[$c['id']] = $c;
    }
    foreach ($map as $c) {
        if ($c['id_comment_parent']) {
            $map[$c['id_comment_parent']]['children'][] = &$map[$c['id']];
        } else {
            $tree[] = &$map[$c['id']];
        }
    }
    return $tree;
}

function renderComments(array $comments, int $depth = 0): void
{
    foreach ($comments as $c): ?>
        <div class="comment <?= $depth > 0 ? 'comment--nested' : '' ?>">
            <div class="comment-meta">
                <?php if (!empty($c['avatar'])): ?>
                    <img src="<?= htmlspecialchars($c['avatar']) ?>" class="meta-avatar" alt="">
                <?php else: ?>
                    <i class="fa-solid fa-circle-user meta-avatar-icon"></i>
                <?php endif; ?>
                <span class="author"><?= htmlspecialchars($c['username']) ?></span>
                &middot;
                <span class="post-date" data-date="<?= $c['date_creation'] ?>"><?= $c['date_creation'] ?></span>
            </div>
            <p class="comment-body"><?= nl2br(htmlspecialchars($c['content'])) ?></p>
            <div class="comment-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="action-btn js-reply-toggle" data-id="<?= $c['id'] ?>">
                        <i class="fa-solid fa-reply"></i> Responder
                    </button>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $c['id_user'] || $_SESSION['role'] === 'moderator')): ?>
                    <button class="action-btn js-delete-comment" data-id="<?= $c['id'] ?>" data-post="<?= $c['id_publication'] ?>">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="reply-form" id="reply-<?= $c['id'] ?>" style="display:none">
                    <form method="POST">
                        <input type="hidden" name="parent_id" value="<?= $c['id'] ?>">
                        <div class="form-group">
                            <textarea name="content" rows="3" placeholder="Escribe tu respuesta..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-outline js-reply-cancel js-cancel-btn" data-id="<?= $c['id'] ?>">Cancelar</button>
                            <button type="submit" class="btn-primary">Responder</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <?php if (!empty($c['children'])): ?>
                <div class="comment-children">
                    <?php renderComments($c['children'], $depth + 1); ?>
                </div>
            <?php endif; ?>
        </div>
<?php endforeach;
}

$tree      = buildTree($comments);
$pageTitle = htmlspecialchars($post['title']);
$activePage = 'post';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="site-main">

        <article class="post post--detail">
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
                <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
                <p class="post-body post-body--full"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
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
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['id_user']): ?>
                        <a href="/pages/edit-post.php?id=<?= $post['id'] ?>" class="action-btn">
                            <i class="fa-solid fa-pen"></i> Editar
                        </a>
                        <button class="action-btn js-delete-post" data-id="<?= $post['id'] ?>">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                    <a href="/index.php" class="action-btn">
                        <i class="fa-solid fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </article>

        <div class="comments-section">
            <h2 class="comments-title">
                <i class="fa-solid fa-comments"></i>
                <?= $post['comment_count'] ?> comentarios
            </h2>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" id="commentForm" novalidate>
                        <div class="form-group">
                            <textarea id="commentContent" name="content" rows="4"
                                placeholder="Escribe un comentario..."></textarea>
                            <span class="field-error" id="commentError"></span>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Comentar</button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <p class="login-prompt">
                    <a href="/pages/login.php">Inicia sesión</a> para comentar.
                </p>
            <?php endif; ?>

            <div class="comments-list">
                <?php renderComments($tree); ?>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>