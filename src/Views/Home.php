<?php include(__DIR__ . '/layout/Header.php'); ?>
<?php
require_once __DIR__ . '/../Models/Article.php';

$articleModel = new Article();
$latestArticles = [];
try {
    $latestArticles = $articleModel->getLatest(4); 
} catch (Exception $e) { }

$mainStory = $latestArticles[0] ?? null;
$sideStories = array_slice($latestArticles, 1, 3);

?>
<div class="container" style="margin-top: 2rem;">
    <div class="newspaper-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        
        <!-- Main Column -->
        <section class="main-column">
            <!-- The Morning Banner -->
            <section class="morning-banner" style="background: #fff; color: #000; border: 2px solid #000; padding: 1.5rem; border-radius: 4px; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: transform 0.2s;" onclick="window.location.href='?page=morning'">
                <div>
                    <h2 class="serif-headline" style="font-size: 1.8rem; margin: 0; color: #000;">The Morning</h2>
                    <p style="font-family: var(--font-serif); font-size: 1rem; color: #444; margin: 0.3rem 0 0 0; font-style: italic;">Your daily guide to the news.</p>
                </div>
                <div style="font-family: var(--font-sans); font-weight: 800; font-size: 0.8rem; border: 1.5px solid #000; padding: 0.4rem 1rem; letter-spacing: 1px;">
                    READ THE GUIDE &rarr;
                </div>
            </section>

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
            
            <!-- Live Briefing Ticker -->
            <div class="live-ticker" style="background: #fff5f5; border: 1px solid #feb2b2; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; cursor: pointer;" onclick="window.location.href='?page=live'">
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #dc2626; font-weight: 800; font-size: 0.75rem; letter-spacing: 1px; margin-bottom: 0.5rem;">
                    <span style="width: 8px; height: 8px; background: #dc2626; border-radius: 50%; animation: pulse 1s infinite;"></span> LIVE UPDATES
                </div>
                <h4 class="serif-headline" style="font-size: 1.1rem; margin: 0; line-height: 1.2;">Tracking breaking news and developments in real-time &rarr;</h4>
            </div>

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

    <style>
        .morning-banner:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>



</div>
<?php include(__DIR__ . '/layout/Footer.php'); ?>
