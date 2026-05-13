<?php
session_start();

$pageTitle  = 'Principal';
$activePage = 'home';

require_once __DIR__ . '/assets/includes/header.php';
?>

<div class="layout">

    <?php require_once __DIR__ . '/assets/includes/sidebar.php'; ?>

    <main class="site-main">

        <div class="sort-bar">
            <button class="sort-btn" id="sortBtn">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6 9a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zM9 13a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                Más votados
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>

        <!-- Posts de prueba (hardcoded — se sustituirán por datos de BD en fase 5) -->
        <?php
        $demoPosts = [
            [
                'author'  => 'u/hormiga_reina',
                'time'    => 'hace 3 horas',
                'title'   => 'Título de la publicación',
                'body'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Sed rhoncus, tortor sed eleifend tristique.',
                'up'      => 18,
                'down'    => 2,
            ],
            [
                'author'  => 'u/obrera_07',
                'time'    => 'hace 5 horas',
                'title'   => 'Título de la publicación',
                'body'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id. Sed rhoncus, tortor sed eleifend tristique, tortor mauris molestie elit, et lacinia ipsum quam nec dui. Quisque nec mauris sit amet elit iaculis pretium sit amet quis magna.',
                'up'      => 18,
                'down'    => 2,
            ],
            [
                'author'  => 'u/soldado_alpha',
                'time'    => 'hace 1 día',
                'title'   => 'Título de la publicación',
                'body'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.',
                'up'      => 18,
                'down'    => 2,
            ],
        ];
        ?>

        <?php foreach ($demoPosts as $post): ?>
        <article class="post">
            <div class="post-inner">
                <div class="post-meta">
                    <span class="author"><?= htmlspecialchars($post['author']) ?></span>
                    &middot;
                    <?= htmlspecialchars($post['time']) ?>
                </div>
                <h2 class="post-title"><?= htmlspecialchars($post['title']) ?></h2>
                <p class="post-body"><?= htmlspecialchars($post['body']) ?></p>
                <div class="post-actions">
                    <div class="vote-group">
                        <button class="vote-btn up" title="Voto positivo">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3l7 7h-4v7H7v-7H3l7-7z" clip-rule="evenodd"/>
                            </svg>
                            <?= (int) $post['up'] ?>
                        </button>
                        <button class="vote-btn down" title="Voto negativo">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 17l-7-7h4V3h6v7h4l-7 7z" clip-rule="evenodd"/>
                            </svg>
                            <?= (int) $post['down'] ?>
                        </button>
                    </div>
                    <button class="action-btn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.842 8.842 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"/>
                        </svg>
                        Comentar
                    </button>
                    <button class="action-btn">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/>
                        </svg>
                        Compartir
                    </button>
                    <button class="more-btn" title="Más opciones">&#8230;</button>
                </div>
            </div>
        </article>
        <?php endforeach; ?>

    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>