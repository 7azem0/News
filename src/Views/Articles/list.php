<?php
define('LAYOUT_PATH', __DIR__ . '/../Layout/');
include LAYOUT_PATH . "Header.php";
?>

<div class="article-view">
    <article class="article-header">
        <h2>Articles and News</h2>
        <div class="article-lang-form">
            <label for="lang-select">Translate to:</label>
            <form method="GET" action="index.php" style="display: inline;">
                <input type="hidden" name="page" value="article">
                <select name="lang" id="lang-select" onchange="this.form.submit()">
                    <?php foreach ($availableLangs as $code => $name): ?>
                        <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedLang === $code ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </article>
</div>

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

<section class="news-list">
    <h2>Latest News</h2>

    <?php if (!empty($news)): ?>
        <?php foreach ($news as $item): ?>
            <div class="article-card">
                <?php if (!empty($item['urlToImage']) || !empty($item['imageUrl'])): ?>
                    <img
                        src="<?= htmlspecialchars($item['urlToImage'] ?? $item['imageUrl'], ENT_QUOTES, 'UTF-8') ?>"
                        width="120"
                        alt="<?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    >
                <?php else: ?>
                    <img src="/path/to/placeholder.jpg" width="120" alt="No image available">
                <?php endif; ?>

                <h3><a href="<?= htmlspecialchars($item['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank"><?= htmlspecialchars($item['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></a></h3>
                <p><?= htmlspecialchars($item['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <small>Published at: <?= htmlspecialchars($item['publishedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No news available at the moment.</p>
    <?php endif; ?>
</section>

<?php include LAYOUT_PATH . "Footer.php"; ?>
