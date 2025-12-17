<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="container" style="max-width: 800px; padding-top: 2rem;">

    <article class="single-article">
        <!-- Header -->
        <div class="article-header-container" style="margin-bottom: 2rem; text-align: center;">
             <h1 class="serif-headline" style="font-size: 2rem; line-height: 1.25; margin-bottom: 1rem;">
                <?= htmlspecialchars($displayArticle['title'] ?? $article['title'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <div style="font-family: var(--font-sans); color: #666; font-size: 0.9rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1.5rem; margin-bottom: 2rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                <span>By <strong><?= htmlspecialchars($article['author'] ?? 'Staff Writer') ?></strong></span>
                <span>•</span>
                <span><?= date('F j, Y', strtotime($article['publishedAt'] ?? 'now')) ?></span>
                
                <?php if (empty($displayArticle['is_blocked']) && isset($availableLangs, $selectedLang)): ?>
                    <form method="GET" action="" style="margin:0; margin-left: 1rem;">
                        <input type="hidden" name="page" value="article">
                        <input type="hidden" name="id" value="<?= (int)$article['id'] ?>">
                        <select name="lang" onchange="this.form.submit()" style="padding: 2px; font-size: 0.8rem;">
                            <?php foreach ($availableLangs as $code => $name): ?>
                                <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedLang === $code ? 'selected' : '' ?>>
                                     Translate: <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Image -->
        <?php if (empty($displayArticle['is_blocked']) && !empty($article['thumbnail'])): ?>
            <figure style="margin-bottom: 2rem;">
                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Article Image" style="width: 100%; height: auto; display: block;">
                <figcaption style="font-family: var(--font-sans); font-size: 0.8rem; color: #888; margin-top: 0.5rem;">
                   <?= htmlspecialchars($article['title']) ?>
                </figcaption>
            </figure>
        <?php elseif (!empty($displayArticle['is_blocked'])): ?>
            <div style="background: #f1f1f1; height: 300px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border-radius: 8px; border: 1px dashed #ccc;">
                <p style="color: #666;">Premium Article Content Blocked</p>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="article-body" style="font-family: var(--font-serif); font-size: 1.25rem; line-height: 1.8; color: #333;">
            <?php if (!empty($displayArticle['is_blocked'])): ?>
                <?= $displayArticle['content'] ?>
            <?php else: ?>
                <?= nl2br(htmlspecialchars($displayArticle['content'] ?? $article['content'], ENT_QUOTES, 'UTF-8')) ?>
            <?php endif; ?>
        </div>

    </article>

    <div class="divider"></div>

    <div style="text-align: center; margin: 3rem 0;">
        <a href="?page=article" class="btn-primary" style="background: white; color: black; border: 1px solid #ccc;">&larr; Back to All Articles</a>
    </div>

</main>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
