<?php include "Views/Layout/Header.php"; ?>

<section class="articles-list">
    <h2>Latest Articles</h2>

<?php foreach ($articles as $a): ?>
    <div class="article-card">
        <?php if (!empty($a['thumbnail'])): ?>
            <img
                src="<?= htmlspecialchars($a['thumbnail'], ENT_QUOTES, 'UTF-8') ?>"
                width="120"
                alt="Thumbnail for <?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?>"
            >
        <?php endif; ?>
        <h3><?= htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') ?></h3>
        <a href="?page=article&id=<?= (int)$a['id'] ?>">Read</a>
    </div>
<?php endforeach; ?>
</section>

<?php include "Views/Layout/Footer.php"; ?>
