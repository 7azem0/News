<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Games Arcade | News App</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --accent: #818cf8;
            --background: #f1f5f9;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .header h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            color: var(--text-main);
            background: linear-gradient(135deg, #4f46e5, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header p {
            color: var(--text-muted);
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .game-card {
            background: var(--surface);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .game-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .card-image {
            height: 200px;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .card-desc {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0;
        }

        .btn-play {
            display: inline-block;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: var(--primary);
            color: white;
            border-radius: 6px;
            font-weight: 600;
        }
        
        .navbar-placeholder {
            /* Basic styling for when included without main layout */
            background: #fff;
            padding: 1rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="navbar-placeholder">
        <a href="index.php" style="text-decoration:none; color: #333; font-weight:bold;">← Back to News</a>
    </div>

    <div class="container">
        <div class="header">
            <h1>Daily Mini-Games</h1>
            <p>Challenge your mind with our daily puzzles. Login to save your streak!</p>
        </div>

        <div class="games-grid">
            <!-- Wordle Card -->
            <a href="index.php?page=games&action=play&game=wordle" class="game-card">
                <div class="card-image" style="background: #6aaa64; color: white;">
                    ABC
                </div>
                <div class="card-content">
                    <h3 class="card-title">Word Guess</h3>
                    <p class="card-desc">Guess the 5-letter hidden word in 6 tries. A new puzzle every day!</p>
                    <span class="btn-play">Play Now</span>
                </div>
            </a>

            <!-- Connections Card -->
            <a href="index.php?page=games&action=play&game=connections" class="game-card">
                <div class="card-image" style="background: #b59f3b; color: white;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:4px; font-size: 0.5em;">
                        <div style="background:white; width:20px; height:20px; border-radius:4px;"></div>
                        <div style="background:white; width:20px; height:20px; border-radius:4px;"></div>
                        <div style="background:white; width:20px; height:20px; border-radius:4px;"></div>
                        <div style="background:white; width:20px; height:20px; border-radius:4px;"></div>
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
                <div class="card-image" style="background: #f7da21; color: black;">
                    ⬡
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
