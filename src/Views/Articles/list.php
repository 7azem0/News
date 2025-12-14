<?php
define('LAYOUT_PATH', __DIR__ . '/../Layout/');
include LAYOUT_PATH . "Header.php";
?>

<section class="articles-list">
    <h2>Latest Articles</h2>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <?php if (!empty($article['thumbnail'])): ?>
                    <img
                        src="<?= htmlspecialchars($article['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                        width="120"
                        alt="<?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                <?php else: ?>
                    <img src="/path/to/placeholder.jpg" width="120" alt="No image available">
                <?php endif; ?>

                <h3><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <a href="?page=article&id=<?= (int)$article['id'] ?>">Read</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No articles available at the moment.</p>
    <?php endif; ?>
</section>

<?php include LAYOUT_PATH . "Footer.php"; ?>