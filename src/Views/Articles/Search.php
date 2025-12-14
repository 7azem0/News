<?php include __DIR__ . '/../Layout/Header.php'; ?>

<section class="search-page">
    <h2>Search Articles</h2>

    <form method="GET" action="index.php" class="search-page-form">
        <input type="hidden" name="page" value="search">
        <input
            type="text"
            name="q"
            placeholder="Search by title or content"
            value="<?= htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php elseif ($query === '' || $query === null): ?>
        <p>Enter a keyword above to search articles.</p>
    <?php else: ?>
        <?php if (!empty($articles)): ?>
            <h3>Articles</h3>
            <?php foreach ($articles as $a): ?>
                <div class="article-card">
                    <?php if (!empty($a['thumbnail'])): ?>
                        <img
                            src="<?= htmlspecialchars($a['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                            width="120"
                            alt="Thumbnail for <?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?>"
                        >
                    <?php else: ?>
                        <img src="/path/to/placeholder.jpg" width="120" alt="No image available">
                    <?php endif; ?>

                    <h4><?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?></h4>
                    <a href="index.php?page=article&id=<?= (int)$a['id'] ?>">Read</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($news)): ?>
            <h3>News</h3>
            <?php foreach ($news as $n): ?>
                <div class="article-card">
                    <?php if (!empty($n['urlToImage'])): ?>
                        <img
                            src="<?= htmlspecialchars($n['urlToImage'], ENT_QUOTES, 'UTF-8') ?>"
                            width="120"
                            alt="Thumbnail for <?= htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8') ?>"
                        >
                    <?php else: ?>
                        <img src="/path/to/placeholder.jpg" width="120" alt="No image available">
                    <?php endif; ?>

                    <h4><a href="<?= htmlspecialchars($n['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank"><?= htmlspecialchars($n['title'], ENT_QUOTES, 'UTF-8') ?></a></h4>
                    <p><?= htmlspecialchars($n['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <small>Published at: <?= htmlspecialchars($n['publishedAt'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (empty($articles) && empty($news)): ?>
            <p>No results found for "<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>".</p>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
