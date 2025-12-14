<?php include __DIR__ . '/../Layout/Header.php'; ?>

<section class="articles-list">
    <h1>News Today</h1>

    <?php if (!empty($articles)): ?>
        <?php foreach ($articles as $article): ?>
            <div class="article-card">
                <?php if (!empty($article['urlToImage']) || !empty($article['imageUrl'])): ?>
                    <img
                        src="<?= htmlspecialchars($article['urlToImage'] ?? $article['imageUrl'], ENT_QUOTES, 'UTF-8') ?>"
                        width="200"
                        alt="<?= htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                <?php endif; ?>
                <h2>
                    <a href="<?= htmlspecialchars($article['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                        <?= htmlspecialchars($article['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </h2>
                <p><?= htmlspecialchars($article['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <small>Published at: <?= htmlspecialchars($article['publishedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No news available at the moment.</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>