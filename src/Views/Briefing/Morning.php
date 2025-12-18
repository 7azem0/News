<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Morning - Digital Newsstand</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@400;600;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        body { background-color: #fff; }
        .serif-headline { font-family: 'Playfair Display', serif; }
        .sans-text { font-family: 'Inter', sans-serif; }
        
        /* Full-width header utility */
        .full-width-header {
            width: 100%;
            border-top: 4px double #000;
            border-bottom: 2px solid #000;
            background: #fff;
            padding: 1.5rem 0;
            margin-bottom: 4rem;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 2rem;
            padding: 0 2rem;
        }

        .return-home {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: #000;
            color: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            font-family: var(--font-sans);
            font-size: 0.8rem;
            font-weight: bold;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .return-home:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <a href="index.php?page=Home" class="return-home">&larr; BACK TO HOME</a>

    <!-- Standalone Editorial Header -->
    <header class="full-width-header">
        <div class="header-content">
            <!-- Column 1: Date & Branding -->
            <div style="font-family: var(--font-sans); font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #333;">
                <?= date('l, F j, Y') ?> <br>
                <span style="color: #666; font-weight: 400;">THE DAILY GUIDE</span>
            </div>

            <!-- Column 2: Title (Centered) -->
            <div style="text-align: center;">
                <h1 class="serif-headline" style="font-size: 4rem; line-height: 0.8; margin: 0; font-weight: 900; letter-spacing: -2px; color: #000;">The Morning</h1>
            </div>

            <!-- Column 3: Tagline (Right) -->
            <div style="text-align: right; font-family: var(--font-serif); font-size: 1.1rem; color: #444; font-style: italic; line-height: 1.2;">
                Your guide to what's happening <br> and why it matters.
            </div>
        </div>
    </header>

    <main class="container" style="max-width: 900px; margin: 0 auto; padding-bottom: 5rem;">
        
        <!-- Top Story -->
        <?php if (!empty($featured)): ?>
            <?php $top = $featured[0]; ?>
            <section class="top-story" style="margin-bottom: 4rem; cursor: pointer;" onclick="window.location.href='?page=article&id=<?= $top['id'] ?>'">
                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem; align-items: start;">
                     <div>
                        <h2 class="serif-headline" style="font-size: 2.8rem; margin: 0 0 1rem 0; line-height: 1.1;">
                            <?= htmlspecialchars($top['title']) ?>
                        </h2>
                        <p style="font-family: var(--font-serif); font-size: 1.25rem; line-height: 1.6; color: #333;">
                            <?= htmlspecialchars(substr(strip_tags($top['description']), 0, 300)) ?>...
                        </p>
                        <div style="font-family: var(--font-sans); font-size: 0.9rem; font-weight: bold; margin-top: 1.5rem; color: #000;">
                            BY <?= strtoupper(htmlspecialchars($top['author'])) ?> &bull; <span style="text-decoration: underline;">Read Full Story</span>
                        </div>
                    </div>
                    <div>
                        <?php if (!empty($top['thumbnail'])): ?>
                            <img src="<?= htmlspecialchars($top['thumbnail']) ?>" style="width: 100%; border-radius: 2px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Global Roundup -->
        <section class="global-roundup" style="margin-top: 6rem; border-top: 1px solid #eee; padding-top: 3rem;">
            <h2 class="sans-text" style="font-size: 0.85rem; border-bottom: 2.5px solid #000; padding-bottom: 10px; margin-bottom: 3rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 900;">Global Roundup</h2>
            
            <div style="display: flex; flex-direction: column; gap: 3rem;">
                <?php foreach ($globalHeadlines as $news): ?>
                    <div style="padding-bottom: 2rem; border-bottom: 1px solid #f0f0f0;">
                        <a href="<?= htmlspecialchars($news['url']) ?>" target="_blank" style="text-decoration: none; color: inherit; display: grid; grid-template-columns: 1fr auto; gap: 2rem; align-items: center;">
                            <div>
                                <h4 class="serif-headline" style="font-size: 1.6rem; margin: 0 0 0.75rem 0; color: #000; line-height: 1.2;">
                                    <?= htmlspecialchars($news['title']) ?>
                                </h4>
                                <p style="font-family: var(--font-serif); color: #555; font-size: 1rem; margin: 0; line-height: 1.5;">
                                    <?= htmlspecialchars(substr($news['description'] ?? '', 0, 180)) ?>...
                                </p>
                            </div>
                            <?php if (!empty($news['urlToImage'])): ?>
                                <img src="<?= htmlspecialchars($news['urlToImage']) ?>" style="width: 120px; height: 120px; object-fit: cover; border-radius: 4px;">
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <footer style="margin-top: 8rem; text-align: center; border-top: 3px double #000; padding-top: 3rem;">
            <p style="font-family: var(--font-serif); font-size: 1.2rem; color: #666; font-style: italic;">
                "The news is the first rough draft of history."
            </p>
            <p style="font-family: var(--font-sans); font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-top: 1rem;">
                Check back tomorrow for a new guide.
            </p>
        </footer>
    </main>

</body>
</html>
