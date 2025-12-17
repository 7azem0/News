<?php include __DIR__ . '/../Layout/Header.php'; ?>

<div class="container" style="margin-top: 2rem;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 4px double var(--border-dark); padding-bottom: 1rem; margin-bottom: 2rem;">
        <h1 class="serif-headline" style="font-size: 3rem; margin: 0;">Articles</h1>
        
        <!-- Language Select -->
         <form method="GET" action="index.php" style="margin: 0;">
            <input type="hidden" name="page" value="article">
            <select name="lang" id="lang-select" onchange="this.form.submit()" style="padding: 5px; font-family: var(--font-sans); border: 1px solid var(--border-light);">
                <?php foreach ($availableLangs as $code => $name): ?>
                    <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedLang === $code ? 'selected' : '' ?>>
                        <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Grid Layout -->
    <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <article style="border-bottom: 1px solid var(--border-light); padding-bottom: 1rem;">
                    <?php if (!empty($article['thumbnail'])): ?>
                        <a href="?page=article&id=<?= $article['id'] ?>">
                            <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Thumbnail" style="width: 100%; height: 200px; object-fit: cover; margin-bottom: 1rem; filter: contrast(0.9);">
                        </a>
                    <?php endif; ?>
                    
                    <a href="?page=article&id=<?= $article['id'] ?>" style="color: black; text-decoration: none;">
                        <h3 class="serif-headline" style="font-size: 1.4rem; line-height: 1.2; margin-bottom: 0.5rem; hover:text-decoration,underline">
                            <?= htmlspecialchars($article['title']) ?>
                        </h3>
                    </a>

                    <div style="font-family: var(--font-sans); font-size: 0.8rem; color: #888; margin-bottom: 0.5rem; text-transform: uppercase;">
                        <?= date('M j, Y', strtotime($article['publishedAt'] ?? 'now')) ?> 
                        <?php if(isset($article['category_name'])): ?>
                             • <span style="font-weight: bold;"><?= htmlspecialchars($article['category_name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <p style="font-family: var(--font-serif); color: #444; font-size: 1rem; line-height: 1.5;">
                        <!-- Truncate description -->
                        <?= htmlspecialchars(substr($article['description'] ?? '', 0, 120)) ?>...
                    </p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
             <p>No articles available.</p>
        <?php endif; ?>
    </div>

    <!-- Latest News Section (External) -->
    <div class="divider"></div>
    
    <h2 class="serif-headline" style="font-size: 2rem; margin-bottom: 1.5rem;">Latest World News</h2>
    
    <div class="news-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
        <?php if (!empty($news)): ?>
            <?php foreach ($news as $item): ?>
                <div class="news-card" style="background: #f9f9f9; padding: 1rem; border: 1px solid var(--border-light);">
                    <?php if (!empty($item['urlToImage']) || !empty($item['imageUrl'])): ?>
                        <img src="<?= htmlspecialchars($item['urlToImage'] ?? $item['imageUrl']) ?>" alt="News" style="width: 100%; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <?php endif; ?>
                    
                    <h3 class="serif-headline" style="font-size: 1.1rem; margin-bottom: 0.5rem;">
                        <a href="<?= htmlspecialchars($item['url'] ?? '#') ?>" target="_blank" style="color: black; text-decoration: none;"><?= htmlspecialchars($item['title']) ?></a>
                    </h3>
                    
                    <p style="font-family: var(--font-sans); font-size: 0.9rem; color: #555;">
                        <?= htmlspecialchars(substr($item['description'] ?? '', 0, 80)) ?>...
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No world news available.</p>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
