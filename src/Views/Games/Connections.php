<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connections | News App</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --surface: #ffffff;
            --background: #f8fafc;
            --text-main: #0f172a;
            --selected: #5b5b5b;
            --correct-1: #fbd400; /* Yellow */
            --correct-2: #a0c35a; /* Green */
            --correct-3: #b0c4ef; /* Blue */
            --correct-4: #ba81c5; /* Purple */
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            max-width: 600px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1rem;
        }

        h1 { margin: 0; font-size: 1.8rem; }

        .game-container {
            width: 100%;
            max-width: 600px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            background: #efefe6;
            border-radius: 8px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            user-select: none;
            text-align: center;
            padding: 5px;
            font-size: 0.9rem;
            transition: transform 0.1s, background-color 0.2s;
        }

        .card.selected {
            background-color: var(--selected);
            color: white;
        }

        .category-row {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            text-align: center;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .category-title { font-weight: 800; font-size: 1.1rem; text-transform: uppercase; }
        .category-words { font-weight: 400; margin-top: 5px; }

        .controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            padding: 12px 24px;
            border-radius: 24px;
            border: 1px solid #000;
            background: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        button:hover { background: #f0f0f0; }
        
        button.submit {
            background: black;
            color: white;
            border: 1px solid black;
        }

        button.submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .mistakes {
            text-align: center;
            margin-top: 20px;
            font-size: 1.1rem;
        }
        .dot {
            display: inline-block;
            width: 12px;
            height: 12px;
            background: #5b5b5b;
            border-radius: 50%;
            margin: 0 4px;
        }

        .hidden { display: none !important; }

        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }
        .modal {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            max-width: 90%;
            width: 300px;
        }
    </style>
</head>
<body>

    <div class="header">
        <a href="index.php?page=games" style="text-decoration:none; color:inherit; font-size: 1.5rem;">✕</a>
        <h1>Connections</h1>
        <div style="width: 24px;"></div>
    </div>

    <div class="game-container">
        
        <!-- Solved Categories Container -->
        <div id="solved-container"></div>

        <!-- Active Grid -->
        <div id="grid" class="grid"></div>

        <div class="mistakes">
            Mistakes remaining: <span id="mistakes-dots"></span>
        </div>

        <div class="controls">
            <button onclick="shuffleGrid()">Shuffle</button>
            <button onclick="deselectAll()">Deselect All</button>
            <button class="submit" id="submit-btn" onclick="submitGuess()" disabled>Submit</button>
        </div>
    </div>

    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <h2 id="modal-title"></h2>
            <p id="modal-msg"></p>
            <button class="submit" onclick="resetGame()" style="margin-top:1rem;">Play Again</button>
        </div>
    </div>

<script>
    // Game Data (Hardcoded for MVP)
    const GROUPS = [
        { id: 1, title: "UNITS OF LENGTH", words: ["FOOT", "MILE", "YARD", "INCH"], color: "#fbd400" },
        { id: 2, title: "PALINDROMES", words: ["LEVEL", "RADAR", "KAYAK", "CIVIC"], color: "#a0c35a" },
        { id: 3, title: "SENSES", words: ["SIGHT", "TOUCH", "TASTE", "SMELL"], color: "#b0c4ef" },
        { id: 4, title: "PLANETS", words: ["MARS", "VENUS", "EARTH", "SATURN"], color: "#ba81c5" }
    ];

    let items = []; // Current grid items
    let selected = [];
    let solvedGroupIds = [];
    let mistakes = 4;

    function init() {
        // Flatten text for grid
        GROUPS.forEach(g => {
            g.words.forEach(w => {
                 items.push({ word: w, groupId: g.id });
            });
        });
        shuffleArray(items);
        render();
        updateMistakes();
        loadProgress();
    }

    function render() {
        const grid = document.getElementById('grid');
        grid.innerHTML = '';
        
        items.forEach(item => {
            const el = document.createElement('div');
            el.className = `card ${selected.includes(item) ? 'selected' : ''}`;
            el.textContent = item.word;
            el.onclick = () => toggleSelect(item);
            grid.appendChild(el);
        });

        document.getElementById('submit-btn').disabled = selected.length !== 4;
    }

    function toggleSelect(item) {
        if (selected.includes(item)) {
            selected = selected.filter(i => i !== item);
        } else {
            if (selected.length < 4) selected.push(item);
        }
        render();
    }

    function deselectAll() {
        selected = [];
        render();
    }

    function shuffleGrid() {
        shuffleArray(items);
        render();
    }

    function submitGuess() {
        if (selected.length !== 4) return;

        // Check if all selected belong to same group
        const firstGroup = selected[0].groupId;
        const isMatch = selected.every(item => item.groupId === firstGroup);

        if (isMatch) {
            // Found a group!
            const group = GROUPS.find(g => g.id === firstGroup);
            markSolved(group);
        } else {
            // Wrong
            mistakes--;
            updateMistakes();
            
            // Check "One Away"
            // Get group counts in selection
            const counts = {};
            selected.forEach(i => counts[i.groupId] = (counts[i.groupId] || 0) + 1);
            const isOneAway = Object.values(counts).some(c => c === 3);
            
            if(isOneAway) alert("One away!");
            else alert("Incorrect!"); // Simple alert for MVP
            
            if (mistakes === 0) {
                showModal("Game Over", "Better luck next time!");
                saveProgress();
                return;
            }
        }
        
        saveProgress();
    }

    function markSolved(group) {
        solvedGroupIds.push(group.id);
        
        // Remove items from grid
        items = items.filter(i => i.groupId !== group.id);
        selected = [];
        
        // Render solved row
        const container = document.getElementById('solved-container');
        const row = document.createElement('div');
        row.className = 'category-row';
        row.style.background = group.color;
        row.innerHTML = `<div class="category-title">${group.title}</div><div class="category-words">${group.words.join(', ')}</div>`;
        container.appendChild(row);

        render();

        if (items.length === 0) {
            showModal("Victory!", "You found all connections!");
        }
    }

    function updateMistakes() {
        const container = document.getElementById('mistakes-dots');
        container.innerHTML = '';
        for(let i=0; i<mistakes; i++) {
            const dot = document.createElement('span');
            dot.className = 'dot';
            container.appendChild(dot);
        }
    }
    
    function showModal(title, msg) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-msg').textContent = msg;
        document.getElementById('modal-overlay').style.display = 'flex';
    }

    // --- Helpers ---
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    // --- persistence ---
    async function saveProgress() {
        const state = {
            solvedGroupIds: solvedGroupIds,
            mistakes: mistakes
        };
        
        await fetch('index.php?page=games&action=save', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ gameId: 'connections', state, score: solvedGroupIds.length })
        });
    }

    async function loadProgress() {
        const res = await fetch('index.php?page=games&action=load&gameId=connections');
        const json = await res.json();
        
        if (json.success && json.data && json.data.state) {
            const state = json.data.state; // JSON decoded by PHP already? Check Controller.
            // Controller returns data['state'] as object because we did json_decode there.
            
            if (state.solvedGroupIds && state.solvedGroupIds.length > 0) {
                state.solvedGroupIds.forEach(gid => {
                    // Only process if not already processed (in case of double calls)
                    if(!solvedGroupIds.includes(gid)) {
                        const group = GROUPS.find(g => g.id == gid);
                        if(group) {
                            markSolved(group); 
                        }
                    }
                });
            }
            if (typeof state.mistakes !== 'undefined') {
                mistakes = state.mistakes;
                updateMistakes();
            }
        }
    }

    function resetGame() {
        if(confirm("Start new game?")) {
            fetch('index.php?page=games&action=reset', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ gameId: 'connections' })
            }).then(() => location.reload());
        }
    }

    init();

</script>
</body>
</html>
