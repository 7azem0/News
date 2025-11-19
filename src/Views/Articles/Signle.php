<?php include "views/layout/header.php"; ?>

<h2><?= $article['title'] ?></h2>
<p><b>Author:</b> <?= $article['author'] ?></p>
<p><?= nl2br($article['content']) ?></p>

<a href="?page=article">Back to list</a>

<?php include "views/layout/footer.php"; ?>
