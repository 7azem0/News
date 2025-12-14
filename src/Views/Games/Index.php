<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games Arcade | News App</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <link rel="stylesheet" href="../../Assets/CSS/games.css">
</head>
<body>

    <div style="background: white; padding: 1rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-light); text-align: center;">
         <a href="index.php" style="text-decoration:none; color: var(--text-main); font-family: var(--font-sans); font-weight: 600; font-size: 0.9rem;">&larr; Back to News</a>
    </div>

    <div class="container">
        <div class="games-hub-header">
            <h1 class="serif-headline">Daily Mini-Games</h1>
            <p>Challenge your mind with our daily puzzles. Login to save your streak!</p>
        </div>

        <div class="games-grid">
            <!-- Wordle Card -->
            <a href="index.php?page=games&action=play&game=wordle" class="game-card">
                <div class="card-image" style="background: white; color: black;">
                    <span style="font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 3rem; letter-spacing: -2px;">ABC</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Word Guess</h3>
                    <p class="card-desc">Guess the 5-letter hidden word in 6 tries. A new puzzle every day!</p>
                    <span class="btn-play">Play Now</span>
                </div>
            </a>

            <!-- Connections Card -->
            <a href="index.php?page=games&action=play&game=connections" class="game-card">
                <div class="card-image" style="background: white; color: black;">
                    <!-- Simple grid icon simulated with CSS borders applied inline for simplicity of icon drawing, but cleaner than before -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:4px; font-size: 0.5em;">
                        <div style="border: 2px solid black; width:16px; height:16px; border-radius:2px;"></div>
                        <div style="background:black; width:20px; height:20px; border-radius:2px;"></div>
                        <div style="background:black; width:20px; height:20px; border-radius:2px;"></div>
                        <div style="border: 2px solid black; width:16px; height:16px; border-radius:2px;"></div>
                    </div>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Connections</h3>
                    <p class="card-desc">Group words that share a common thread. Find 4 groups of 4.</p>
                    <span class="btn-play">Play Now</span>
                </div>
            </a>

            <!-- Spelling Bee Card -->
            <a href="index.php?page=games&action=play&game=spellingbee" class="game-card">
                 <div class="card-image" style="background: white; color: black;">
                    <span style="font-size: 3rem;">⬡</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Spelling Bee</h3>
                    <p class="card-desc">How many words can you make with 7 letters?</p>
                    <span class="btn-play">Play Now</span>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
