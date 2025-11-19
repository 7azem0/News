<?php include "views/layout/header.php"; ?>

<h2>Latest Articles</h2>

<?php foreach ($articles as $a): ?>
    <div class="article-card">
        <img src="<?= $a['thumbnail'] ?>" width="120">
        <h3><?= $a['title'] ?></h3>
        <a href="?page=article&id=<?= $a['id'] ?>">Read</a>
    </div>
<?php endforeach; ?>

<?php include "views/layout/footer.php"; ?>
