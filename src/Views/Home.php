<?php include(__DIR__ . '/layout/Header.php'); ?>
<?php
require_once __DIR__ . '/../Models/Article.php';

$articleModel = new Article();
$latestArticles = [];
try {
    $latestArticles = $articleModel->getLatest(6); 
} catch (Exception $e) { }

$mainStory = $latestArticles[0] ?? null;
$sideStories = array_slice($latestArticles, 1, 3);
$bottomStories = array_slice($latestArticles, 4, 2);
?>

<div class="container" style="margin-top: 2rem;">
    
    <!-- Date/Volume Bar (Optional) -->
    <div class="double-divider"></div>

    <div class="newspaper-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        
        <!-- Main Column -->
        <section class="main-column">
            <?php if ($mainStory): ?>
                <article class="lead-story" style="margin-bottom: 2rem;">
                    <a href="?page=article&id=<?= $mainStory['id'] ?>" style="color: black;">
                        <h2 class="serif-headline" style="font-size: 2.5rem; line-height: 1.1;"><?= htmlspecialchars($mainStory['title']) ?></h2>
                    </a>
                    
                    <div style="font-family: var(--font-sans); color: #666; font-size: 0.9rem; margin-bottom: 1rem;">
                        By Digital Newsstand Staff | <?= date('M j, Y', strtotime($mainStory['publishedAt'] ?? 'now')) ?>
                    </div>

                    <?php if (!empty($mainStory['thumbnail'])): ?>
                        <div style="margin-bottom: 1rem;">
                            <img src="<?= htmlspecialchars($mainStory['thumbnail']) ?>" alt="Cover" style="width: 100%; height: auto; display: block;">
                            <div style="font-size: 0.8rem; color: #888; margin-top: 5px;">
                                <?= htmlspecialchars($mainStory['title']) ?> (Image Source)
                            </div>
                        </div>
                    <?php endif; ?>

                    <p style="font-size: 1.1rem; text-align: justify;">
                        <?= htmlspecialchars(substr($mainStory['description'] ?? '', 0, 200)) ?>...
                        <a href="?page=article&id=<?= $mainStory['id'] ?>" style="font-weight: bold;">Read more</a>
                    </p>
                </article>
            <?php else: ?>
                <p>No featured stories today.</p>
            <?php endif; ?>
        </section>

        <!-- Side Column (Latest/Opinion) -->
        <aside class="side-column" style="border-left: 1px solid var(--border-light); padding-left: 1.5rem;">
            <h3 class="sans-text" style="font-weight: 700; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 1rem;">Latest Headlines</h3>
            
            <?php foreach ($sideStories as $story): ?>
                <article style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem;">
                    <a href="?page=article&id=<?= $story['id'] ?>" style="color: black;">
                        <h4 class="serif-headline" style="font-size: 1.2rem;"><?= htmlspecialchars($story['title']) ?></h4>
                    </a>
                    <p style="font-size: 0.9rem; color: #555; margin-bottom: 0;">
                        <?= htmlspecialchars(substr($story['description'] ?? '', 0, 80)) ?>...
                    </p>
                </article>
            <?php endforeach; ?>

            <!-- Games Widget -->
            <div class="games-widget" style="background: #f1f1f1; padding: 1.5rem; text-align: center; border: 1px solid #ddd; margin-top: 2rem;">
                <h4 class="sans-text" style="font-weight: 700; font-size: 1.2rem; margin-top: 0;">Games Arcade</h4>
                <p style="font-size: 0.9rem; margin-bottom: 1rem;">Play Wordle, Connections, and Spelling Bee.</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 5px; margin-bottom: 1rem;">
                    <!-- Mini icons -->
                    <div style="background: white; border: 1px solid #ccc; padding: 5px; font-weight: bold;">W</div>
                    <div style="background: white; border: 1px solid #ccc; padding: 5px; font-weight: bold;">C</div>
                    <div style="background: white; border: 1px solid #ccc; padding: 5px; font-weight: bold;">S</div>
                </div>

                <a href="?page=games" class="btn-primary" style="font-size: 0.8rem;">Play Now</a>
            </div>
        </aside>

    </div>

    <?php if (!empty($bottomStories)): ?>
        <div class="divider"></div>
        <div class="bottom-stories" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 3rem;">
            <?php foreach ($bottomStories as $story): ?>
                <article>
                    <a href="?page=article&id=<?= $story['id'] ?>" style="color: black;">
                        <h3 class="serif-headline"><?= htmlspecialchars($story['title']) ?></h3>
                    </a>
                    <p><?= htmlspecialchars($story['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php include(__DIR__ . '/layout/Footer.php'); ?>
