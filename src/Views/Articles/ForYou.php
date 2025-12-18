<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="container" style="padding-top: 2rem;">
    <!-- Recommendations Section -->
    <section class="foryou-section" style="margin-bottom: 4rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 0.5rem; margin-bottom: 2rem;">
            <h2 class="serif-headline" style="font-size: 1.8rem; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Recommended For You</h2>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <span style="font-family: var(--font-sans); color: #666; font-size: 0.9rem;">
                    <a href="?page=Login" style="color: #000; font-weight: bold;">Log in</a> to get personalized picks
                </span>
            <?php endif; ?>
        </div>

        <div class="article-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 3rem;">
            <?php if (!empty($recommendations)): ?>
                <?php foreach ($recommendations as $article): ?>
                    <div class="article-card" style="display: flex; flex-direction: column; cursor: pointer; transition: opacity 0.2s;" onclick="window.location.href='?page=article&id=<?= $article['id'] ?>'">
                        <div class="thumbnail-wrapper" style="width: 100%; aspect-ratio: 16/9; background: #f5f5f5; border-radius: 4px; overflow: hidden; margin-bottom: 1rem;">
                            <?php if (!empty($article['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999; font-family: var(--font-sans);">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3 class="serif-headline" style="font-size: 1.25rem; line-height: 1.3; margin: 0 0 0.75rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($article['title']) ?>
                            </h3>
                            <p style="font-family: var(--font-sans); color: #444; font-size: 0.95rem; line-height: 1.5; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars(strip_tags($article['content'] ?? '')) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-family: var(--font-sans); color: #666;">No recommendations available yet.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Trendy Section -->
    <section class="trendy-section" style="margin-bottom: 4rem; background: #fafafa; padding: 2rem; border-radius: 8px;">
        <div style="display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 0.5rem; margin-bottom: 2rem;">
            <h2 class="serif-headline" style="font-size: 1.8rem; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Trendy Right Now</h2>
            <span style="margin-left: 1rem; color: #e74c3c; font-size: 1.2rem;">🔥</span>
        </div>

        <div class="article-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 3rem;">
            <?php if (!empty($trendyArticles)): ?>
                <?php foreach ($trendyArticles as $article): ?>
                    <div class="article-card trendy-card" style="display: flex; flex-direction: column; cursor: pointer; position: relative;" onclick="window.location.href='?page=article&id=<?= $article['id'] ?>'">
                        <div class="trend-badge" style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 4px 10px; border-radius: 20px; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; z-index: 10;">
                            <?= (int)$article['like_count'] ?> LIKES
                        </div>
                        <div class="thumbnail-wrapper" style="width: 100%; aspect-ratio: 16/9; background: #f5f5f5; border-radius: 4px; overflow: hidden; margin-bottom: 1rem;">
                            <?php if (!empty($article['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #999; font-family: var(--font-sans);">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-content">
                            <h3 class="serif-headline" style="font-size: 1.25rem; line-height: 1.3; margin: 0 0 0.75rem 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($article['title']) ?>
                            </h3>
                            <p style="font-family: var(--font-sans); color: #444; font-size: 0.95rem; line-height: 1.5; margin: 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars(strip_tags($article['content'] ?? '')) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="font-family: var(--font-sans); color: #666;">No trendy articles found yet.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
    .article-card:hover { border-color: #333; }
    .article-card:hover .serif-headline { color: #555; }
    .trendy-card:hover { transform: translateY(-5px); transition: transform 0.3s ease; }
</style>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
