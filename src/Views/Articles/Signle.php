<?php include "Views/Layout/Header.php"; ?>

<section class="article-view">
    <header class="article-header">
        <h2><?= htmlspecialchars($displayArticle['title'] ?? $article['title'], ENT_QUOTES, 'UTF-8') ?></h2>

        <?php if (isset($availableLangs, $selectedLang)): ?>
            <form method="GET" action="" class="article-lang-form">
                <input type="hidden" name="page" value="article">
                <input type="hidden" name="id" value="<?= (int)$article['id'] ?>">
                <label for="lang-select">Language:</label>
                <select id="lang-select" name="lang" onchange="this.form.submit()">
                    <?php foreach ($availableLangs as $code => $name): ?>
                        <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedLang === $code ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>
    </header>

    <?php if (!empty($article['author'])): ?>
        <p><strong>Author:</strong> <?= htmlspecialchars($article['author'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <article class="article-content">
        <p><?= nl2br(htmlspecialchars($displayArticle['content'] ?? $article['content'], ENT_QUOTES, 'UTF-8')) ?></p>
    </article>

    <p><a href="?page=article">&larr; Back to list</a></p>
</section>

<?php include "Views/Layout/Footer.php"; ?>
