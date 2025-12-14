<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="news-main">
    <section class="hero-section">
        <?php if (!empty($articles) && isset($articles[0])): ?>
            <article class="hero-article">
                <?php if (!empty($articles[0]['urlToImage']) || !empty($articles[0]['imageUrl'])): ?>
                    <img
                        src="<?= htmlspecialchars($articles[0]['urlToImage'] ?? $articles[0]['imageUrl'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="<?= htmlspecialchars($articles[0]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        class="hero-image"
                    >
                <?php endif; ?>
                <div class="hero-content">
                    <h1 class="hero-title">
                        <a href="<?= htmlspecialchars($articles[0]['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                            <?= htmlspecialchars($articles[0]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    </h1>
                    <p class="hero-description"><?= htmlspecialchars($articles[0]['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <small class="hero-date">Published at: <?= htmlspecialchars($articles[0]['publishedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </article>
        <?php endif; ?>
    </section>

    <section class="featured-section">
        <h2 class="section-title">Featured Stories</h2>
        <div class="featured-grid">
            <?php if (!empty($articles)): ?>
                <?php for ($i = 1; $i < min(5, count($articles)); $i++): ?>
                    <article class="featured-card">
                        <?php if (!empty($articles[$i]['urlToImage']) || !empty($articles[$i]['imageUrl'])): ?>
                            <img
                                src="<?= htmlspecialchars($articles[$i]['urlToImage'] ?? $articles[$i]['imageUrl'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($articles[$i]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                class="featured-image"
                            >
                        <?php endif; ?>
                        <div class="featured-content">
                            <h3 class="featured-title">
                                <a href="<?= htmlspecialchars($articles[$i]['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                    <?= htmlspecialchars($articles[$i]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h3>
                            <p class="featured-description"><?= htmlspecialchars($articles[$i]['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <small class="featured-date">Published at: <?= htmlspecialchars($articles[$i]['publishedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                    </article>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="latest-section">
        <h2 class="section-title">Latest News</h2>
        <div class="latest-grid">
            <?php if (!empty($articles)): ?>
                <?php for ($i = 5; $i < count($articles); $i++): ?>
                    <article class="latest-card">
                        <?php if (!empty($articles[$i]['urlToImage']) || !empty($articles[$i]['imageUrl'])): ?>
                            <img
                                src="<?= htmlspecialchars($articles[$i]['urlToImage'] ?? $articles[$i]['imageUrl'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($articles[$i]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                class="latest-image"
                            >
                        <?php endif; ?>
                        <div class="latest-content">
                            <h4 class="latest-title">
                                <a href="<?= htmlspecialchars($articles[$i]['url'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                                    <?= htmlspecialchars($articles[$i]['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h4>
                            <p class="latest-description"><?= htmlspecialchars($articles[$i]['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                            <small class="latest-date">Published at: <?= htmlspecialchars($articles[$i]['publishedAt'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                        </div>
                    </article>
                <?php endfor; ?>
            <?php else: ?>
                <p>No news available at the moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
