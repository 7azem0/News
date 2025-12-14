<?php include(__DIR__ . '/../Layout/Header.php'); ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; text-align: center;">
    <h1 class="serif-headline" style="font-size: 2.5rem; margin-bottom: 2rem;">Connections</h1>
    
    <div class="game-container" style="width: 100%;">
        
        <!-- Solved Categories Container -->
        <div id="solved-container"></div>

        <!-- Active Grid -->
        <div id="grid" class="connections-grid"></div>

        <div class="mistakes" style="margin-top: 20px; font-size: 1.1rem; font-family: var(--font-sans);">
            Mistakes remaining: <span id="mistakes-dots"></span>
        </div>

        <div class="controls" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
            <button class="btn-primary" style="background: white; color: black; border: 1px solid black;" onclick="shuffleGrid()">Shuffle</button>
            <button class="btn-primary" style="background: white; color: black; border: 1px solid black;" onclick="deselectAll()">Deselect All</button>
            <button class="btn-primary" id="submit-btn" onclick="submitGuess()" disabled>Submit</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
    <div class="modal" style="background: white; padding: 2rem; border-radius: 12px; text-align: center; max-width: 90%; width: 300px;">
        <h2 id="modal-title" class="serif-headline"></h2>
        <p id="modal-msg" class="sans-text"></p>
        <button class="btn-primary" onclick="resetGame()" style="margin-top:1rem;">Play Again</button>
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
            // Use conn-card class from games.css
            el.className = `conn-card ${selected.includes(item) ? 'selected' : ''}`;
            el.textContent = item.word;
            el.onclick = () => toggleSelect(item);
            grid.appendChild(el);
        });

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = selected.length !== 4;
        submitBtn.style.opacity = selected.length !== 4 ? '0.5' : '1';
        submitBtn.style.cursor = selected.length !== 4 ? 'not-allowed' : 'pointer';
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
            const counts = {};
            selected.forEach(i => counts[i.groupId] = (counts[i.groupId] || 0) + 1);
            const isOneAway = Object.values(counts).some(c => c === 3);
            
            if(isOneAway) alert("One away!");
            else alert("Incorrect!"); 
            
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
        row.className = 'conn-row'; // Use conn-row from games.css
        row.style.background = group.color;
        row.innerHTML = `<div class="conn-title">${group.title}</div><div class="conn-words">${group.words.join(', ')}</div>`;
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
            // Use inline style or games.css dot if available. games.css doesn't have .dot
            // We can add simple style or use a char.
            dot.style.display = 'inline-block';
            dot.style.width = '12px';
            dot.style.height = '12px';
            dot.style.backgroundColor = '#5b5b5b';
            dot.style.borderRadius = '50%';
            dot.style.margin = '0 4px';
            container.appendChild(dot);
        }
    }
    
    function showModal(title, msg) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-msg').textContent = msg;
        document.getElementById('modal-overlay').style.display = 'flex';
    }

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

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
            const state = json.data.state;
            
            if (state.solvedGroupIds && state.solvedGroupIds.length > 0) {
                state.solvedGroupIds.forEach(gid => {
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

<?php include(__DIR__ . '/../Layout/Footer.php'); ?>
