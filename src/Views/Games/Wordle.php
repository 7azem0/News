<?php include(__DIR__ . '/../Layout/Header.php'); ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; text-align: center;">
    <h1 class="serif-headline" style="font-size: 2.5rem; margin-bottom: 2rem;">Word Guess</h1>

    <div id="game-board" class="wordle-board"></div>
    <div id="keyboard" class="keyboard"></div>

    <div class="overlay" id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:50;"></div>
    <div class="modal" id="result-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:white; padding:2rem; border-radius:8px; z-index:100; text-align:center; border: 1px solid #ccc; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 id="modal-title" class="serif-headline" style="margin-top:0;"></h2>
        <p id="modal-msg" class="sans-text" style="font-family: var(--font-sans); margin-bottom: 20px;"></p>
        <button class="btn-primary" onclick="shareResult()" style="background: black; color: white; padding: 0.5rem 1rem; border: none; cursor: pointer;">Share Result 📋</button>
        <button class="btn-primary" onclick="resetGame()" style="background: white; color: black; border: 1px solid black; padding: 0.5rem 1rem; cursor: pointer; margin-left: 10px;">Play Again 🔄</button>
    </div>
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
            document.querySelectorAll('.wordle-tile').forEach(t => {
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
        row.className = 'wordle-row';
        for(let c=0; c<WORD_LENGTH; c++) {
            const tile = document.createElement('div');
            tile.className = 'wordle-tile';
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
        const rowTiles = document.querySelectorAll('#game-board .wordle-row')[currentRow].children;
        
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
            const rowTiles = document.querySelectorAll('#game-board .wordle-row')[i].children;
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
                const rowTiles = document.querySelectorAll('#game-board .wordle-row')[r].children;

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

<?php include(__DIR__ . '/../Layout/Footer.php'); ?>
