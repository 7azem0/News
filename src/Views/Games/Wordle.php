<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Guess | News App</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --correct: #6aaa64;
            --present: #c9b458;
            --absent: #787c7e;
            --surface: #ffffff;
            --border: #d3d6da;
        }

        body {
            font-family: 'Inter', sans-serif;
            text-align: center;
            background-color: #f8fafc;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }

        header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem;
            width: 100%;
            margin-bottom: 1rem;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        #game-board {
            display: grid;
            grid-template-rows: repeat(6, 1fr);
            grid-gap: 5px;
            padding: 10px;
            box-sizing: border-box;
            width: 350px;
            height: 420px;
            margin-bottom: 20px;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 5px;
        }

        .tile {
            width: 100%;
            height: 100%;
            border: 2px solid var(--border);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            font-weight: bold;
            text-transform: uppercase;
            background: white;
            user-select: none;
        }

        .tile[data-state="correct"] { background-color: var(--correct); color: white; border-color: var(--correct); }
        .tile[data-state="present"] { background-color: var(--present); color: white; border-color: var(--present); }
        .tile[data-state="absent"]  { background-color: var(--absent);  color: white; border-color: var(--absent); }

        #keyboard {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            max-width: 500px;
            padding: 0 10px;
        }

        .key-row {
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .key {
            padding: 15px 0;
            flex: 1;
            border-radius: 4px;
            background-color: #d3d6da;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            user-select: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .key[data-state="correct"] { background-color: var(--correct); color: white; }
        .key[data-state="present"] { background-color: var(--present); color: white; }
        .key[data-state="absent"]  { background-color: var(--absent);  color: white; }

        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            z-index: 100;
            text-align: center;
            min-width: 300px;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 50;
        }

        button.action-btn {
            background: var(--correct);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <header>
        <a href="index.php?page=games" style="text-decoration:none; color:inherit;">✕</a>
        <h1>Word Guess</h1>
        <div style="width: 20px;"></div>
    </header>

    <div id="game-board"></div>
    <div id="keyboard"></div>

    <div class="overlay" id="overlay"></div>
    <div class="modal" id="result-modal">
        <h2 id="modal-title"></h2>
        <p id="modal-msg"></p>
        <button class="action-btn" onclick="shareResult()">Share Result 📋</button>
        <button class="action-btn" onclick="resetGame()" style="background-color: #2563eb; margin-left: 10px;">Play Again 🔄</button>
    </div>

<script>
    const SOLUTION = "WORLD"; // Hardcoded for demo/MVP
    const MAX_TRIES = 6;
    const WORD_LENGTH = 5;

    let currentRow = 0;
    let currentTile = 0;
    let isGameOver = false;
    let guesses = Array(MAX_TRIES).fill(null).map(() => Array(WORD_LENGTH).fill(''));

    function resetGame() {
        if(confirm("Start a new game?")) {
            // Clear local
            currentRow = 0;
            currentTile = 0;
            isGameOver = false;
            guesses = Array(MAX_TRIES).fill(null).map(() => Array(WORD_LENGTH).fill(''));
            
            // Clear UI
            document.querySelectorAll('.tile').forEach(t => {
                t.textContent = '';
                t.removeAttribute('data-state');
            });
            document.querySelectorAll('.key').forEach(k => {
                if(k.textContent.length === 1) k.removeAttribute('data-state');
            });
            document.getElementById('result-modal').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';

            // Clear Server
            fetch('index.php?page=games&action=reset', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ gameId: 'wordle' })
            });
        }
    }

    // Init Board
    const board = document.getElementById('game-board');
    for(let r=0; r<MAX_TRIES; r++) {
        const row = document.createElement('div');
        row.className = 'row';
        for(let c=0; c<WORD_LENGTH; c++) {
            const tile = document.createElement('div');
            tile.className = 'tile';
            tile.id = `tile-${r}-${c}`;
            row.appendChild(tile);
        }
        board.appendChild(row);
    }

    // Init Keyboard
    const keys = [
        "QWERTYUIOP",
        "ASDFGHJKL",
        "ZXCVBNM"
    ];
    const keyboard = document.getElementById('keyboard');
    keys.forEach(rowKey => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'key-row';
        
        // Add Enter/Back on last row
        if(rowKey.startsWith('Z')) {
            addKey(rowDiv, 'ENTER', 'ENTER');
        }

        for(let char of rowKey) {
            addKey(rowDiv, char, char);
        }
        
        if(rowKey.startsWith('Z')) {
            addKey(rowDiv, '⌫', 'BACKSPACE');
        }
        keyboard.appendChild(rowDiv);
    });

    function addKey(container, label, keyVal) {
        const key = document.createElement('div');
        key.textContent = label;
        key.className = 'key';
        key.dataset.key = keyVal;
        key.onclick = () => handleInput(keyVal);
        container.appendChild(key);
    }

    // Logic
    document.addEventListener('keydown', (e) => handleInput(e.key.toUpperCase()));

    function handleInput(key) {
        if(isGameOver) return;

        if (key === 'BACKSPACE') {
            if(currentTile > 0) {
                currentTile--;
                guesses[currentRow][currentTile] = '';
                updateTile(currentRow, currentTile, '');
            }
            return;
        }

        if (key === 'ENTER') {
            if(currentTile === WORD_LENGTH) {
                checkRow();
            }
            return;
        }

        if (currentTile < WORD_LENGTH && /^[A-Z]$/.test(key)) {
            guesses[currentRow][currentTile] = key;
            updateTile(currentRow, currentTile, key);
            currentTile++;
        }
    }

    function updateTile(r, c, val) {
        document.getElementById(`tile-${r}-${c}`).textContent = val;
    }

    function checkRow() {
        const guess = guesses[currentRow].join('');
        const rowTiles = document.querySelectorAll(`#game-board .row`)[currentRow].children;
        
        // Color logic
        let solutionChars = SOLUTION.split('');
        let guessChars = guess.split('');

        // First pass: Corrects
        guessChars.forEach((char, i) => {
            if(char === solutionChars[i]) {
                rowTiles[i].dataset.state = 'correct';
                updateKeyColor(char, 'correct');
                solutionChars[i] = null;
                guessChars[i] = null;
            }
        });

        // Second pass: Present/Absent
        guessChars.forEach((char, i) => {
            if(char === null) return; // already handled
            
            const index = solutionChars.indexOf(char);
            if(index > -1) {
                rowTiles[i].dataset.state = 'present';
                updateKeyColor(char, 'present');
                solutionChars[index] = null;
            } else {
                rowTiles[i].dataset.state = 'absent';
                updateKeyColor(char, 'absent');
            }
        });

        // Save progress
        saveProgress(currentRow + 1);

        if (guess === SOLUTION) {
            isGameOver = true;
            showModal('You Won!', 'Great Job!');
            saveProgress(currentRow + 1, true); // true = game finished
        } else {
            if (currentRow >= MAX_TRIES - 1) {
                isGameOver = true;
                showModal('Game Over', `The word was ${SOLUTION}`);
            } else {
                currentRow++;
                currentTile = 0;
            }
        }
    }

    function updateKeyColor(char, state) {
        const key = document.querySelector(`.key[data-key="${char}"]`);
        // Don't downgrade status (correct > present > absent)
        const priorities = { 'correct': 3, 'present': 2, 'absent': 1, undefined: 0 };
        const currentPriority = priorities[key.dataset.state];
        const newPriority = priorities[state];

        if (newPriority > currentPriority) {
            key.dataset.state = state;
        }
    }

    function showModal(title, msg) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-msg').textContent = msg;
        document.getElementById('result-modal').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function shareResult() {
        let result = `News App Wordle ${currentRow+1}/6\n\n`;
        // Build emoji grid
        for(let i=0; i<=currentRow; i++) {
            const rowTiles = document.querySelectorAll(`#game-board .row`)[i].children;
            for(let tile of rowTiles) {
                const state = tile.dataset.state;
                if(state === 'correct') result += '🟩';
                else if(state === 'present') result += '🟨';
                else result += '⬛';
            }
            result += '\n';
        }
        
        navigator.clipboard.writeText(result).then(() => {
            alert('Copied to clipboard!');
        });
    }

    // --- Backend Integration ---

    // Load progress on start
    window.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch('index.php?page=games&action=load&gameId=wordle');
            const json = await res.json();
            
            if(json.success && json.data && json.data.state) {
                restoreState(json.data.state);
            }
        } catch(e) {
            console.error(e);
        }
    });

    function restoreState(state) {
        // state = { guesses: [...], currentRow: N, isGameOver: bool }
        if(!state) return;
        
        currentRow = state.currentRow;
        isGameOver = state.isGameOver;
        guesses = state.guesses;

        // Re-render board
        for(let r=0; r<MAX_TRIES; r++) {
            // Only color completed rows
            if(r < currentRow) {
                // Re-run color logic mostly for visuals
                let solChars = SOLUTION.split('');
                let rowGuess = guesses[r].join('');
                let gChars = rowGuess.split('');
                const rowTiles = document.querySelectorAll(`#game-board .row`)[r].children;

                // Pass 1
                gChars.forEach((c, i) => {
                    updateTile(r, i, c);
                    if(c === solChars[i]) {
                        rowTiles[i].dataset.state = 'correct';
                         updateKeyColor(c, 'correct');
                        solChars[i] = null; gChars[i] = null;
                    }
                });
                // Pass 2
                gChars.forEach((c, i) => {
                    if(!c) return;
                    if(solChars.includes(c)) {
                        rowTiles[i].dataset.state = 'present';
                        updateKeyColor(c, 'present');
                        solChars[solChars.indexOf(c)] = null;
                    } else {
                        rowTiles[i].dataset.state = 'absent';
                        updateKeyColor(c, 'absent');
                    }
                });
            } else if (r === currentRow && !isGameOver) {
                // Just fill letters for current active row
                 guesses[r].forEach((c, i) => {
                     updateTile(r, i, c);
                 });
                 currentTile = guesses[r].filter(c => c !== '').length;
            }
        }

        if(isGameOver) {
            const lastGuess = guesses[currentRow>0 ? currentRow-1 : 0].join('');
            if(lastGuess === SOLUTION) showModal('You Won!', 'Welcome back!');
            else showModal('Game Over', `The word was ${SOLUTION}`);
        }
    }

    async function saveProgress(score, finished = false) {
        const state = {
            guesses: guesses,
            currentRow: currentRow,
            isGameOver: isGameOver
        };
        
        try {
            await fetch('index.php?page=games&action=save', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    gameId: 'wordle',
                    state: state,
                    score: score // saving attempts count as score? or 1 for win? keeping simple.
                })
            });
        } catch(e) {
            console.error("Save failed", e);
        }
    }

</script>
</body>
</html>
