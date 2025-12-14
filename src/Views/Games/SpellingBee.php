<?php include(__DIR__ . '/../Layout/Header.php'); ?>

<div class="container" style="max-width: 600px; margin: 2rem auto; text-align: center;">
    <h1 class="serif-headline" style="font-size: 2.5rem; margin-bottom: 2rem;">Spelling Bee</h1>

    <div class="score-board" style="margin-bottom: 1rem; font-family: var(--font-sans);">
        <span>Score: <strong id="score-display">0</strong></span>
    </div>

    <!-- Hive Container: Needs relative positioning context for hexes -->
    <div class="hive-container" style="position: relative; height: 320px; width: 100%; display: flex; justify-content: center;">
        <div class="hive" id="hive">
            <!-- Center (Index 0 in logic) -->
            <div class="hex center" id="hex-0"></div>
            <!-- Surrounding -->
            <div class="hex" id="hex-1"></div>
            <div class="hex" id="hex-2"></div>
            <div class="hex" id="hex-3"></div>
            <div class="hex" id="hex-4"></div>
            <div class="hex" id="hex-5"></div>
            <div class="hex" id="hex-6"></div>
        </div>
    </div>

    <div class="sb-input" style="margin-top: 2rem;">
        <span id="input-text"></span><span class="sb-cursor"></span>
    </div>

    <div class="controls" style="display:flex; justify-content: center; gap: 1rem; margin-bottom: 2rem; margin-top: 1rem;">
        <button onclick="deleteChar()" class="btn-primary" style="background:white; color:black; border:1px solid #ccc;">Delete</button>
        <button onclick="shuffleLetters()" class="btn-primary" style="background:white; color:black; border:1px solid #ccc;">Shuffle</button>
        <button class="enter-btn btn-primary" onclick="submitWord()" style="background: black; color: white;">Enter</button>
    </div>

    <div class="word-list" style="margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; padding: 1rem; width: 90%; max-width: 400px; min-height: 100px; background: white; text-align: left;">
        <h3 class="sans-text" style="margin-top:0;">Found Words</h3>
        <div class="found-words" id="found-words-list" style="display:flex; flex-wrap:wrap; gap:10px;"></div>
    </div>

    <div class="message-toast" id="toast" style="position:fixed; top:10%; left: 50%; transform: translateX(-50%); background:black; color:white; padding:10px 20px; border-radius:4px; opacity:0; pointer-events:none; transition: opacity 0.3s; z-index: 200;"></div>
</div>

<script>
    // Config
    const LETTERS = ["L", "C", "I", "O", "T", "G", "A"]; // Center is first (L)
    const CENTER = LETTERS[0];
    const SOLUTIONS = [
        "LOGICAL", "LOCAL", "COIL", "TOIL", "GOAL", "TAIL", "TOLL", "LILAC", "CALL", "TALL"
    ]; // Tiny subset for MVP

    let currentInput = "";
    let foundWords = [];
    let score = 0;

    function renderHive() {
        document.getElementById('hex-0').textContent = LETTERS[0];
        // Outer hive
        const outer = LETTERS.slice(1);
        for(let i=0; i<6; i++) {
            document.getElementById(`hex-${i+1}`).textContent = outer[i];
            document.getElementById(`hex-${i+1}`).onclick = () => addChar(outer[i]);
        }
        document.getElementById('hex-0').onclick = () => addChar(LETTERS[0]);
    }

    function addChar(char) {
        currentInput += char;
        updateInput();
    }

    function deleteChar() {
        currentInput = currentInput.slice(0, -1);
        updateInput();
    }

    function updateInput() {
        document.getElementById('input-text').textContent = currentInput;
    }

    function submitWord() {
        const word = currentInput;
        
        if (word.length < 4) {
            showToast("Too short");
            return;
        }
        if (!word.includes(CENTER)) {
            showToast("Missing center letter");
            return;
        }
        if (foundWords.includes(word)) {
            showToast("Already found");
            return;
        }
        
        // Client-side validation for MVP (Server validation would be better for full production)
        if (SOLUTIONS.includes(word)) {
            addScore(word);
            foundWords.push(word);
            renderFoundWords();
            saveProgress();
            currentInput = "";
            updateInput();
            showToast("Nice!");
        } else {
            showToast("Not in word list");
        }
    }

    function addScore(word) {
        if (word.length === 4) score += 1;
        else score += word.length;
        if (isPangram(word)) score += 7; // Bonus
        document.getElementById('score-display').textContent = score;
    }

    function isPangram(word) {
        return LETTERS.every(l => word.includes(l));
    }

    function renderFoundWords() {
        const container = document.getElementById('found-words-list');
        container.innerHTML = foundWords.map(w => `<span class="found-word">${w}</span>`).join(' ');
    }

    function getPermutation(array) {
        // Fisher-Yates shuffle for outer only
        const outer = array.slice(1);
        for (let i = outer.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [outer[i], outer[j]] = [outer[j], outer[i]];
        }
        return [array[0], ...outer];
    }
    
    function shuffleLetters() {
        const outer = LETTERS.slice(1);
         for (let i = outer.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [outer[i], outer[j]] = [outer[j], outer[i]];
        }
        
        // Update visual hexes
        for(let i=0; i<6; i++) {
            document.getElementById(`hex-${i+1}`).textContent = outer[i];
            // Update onclick listeners to match visual
            document.getElementById(`hex-${i+1}`).onclick = () => addChar(outer[i]);
        }
    }

    function showToast(msg) {
        const el = document.getElementById('toast');
        el.textContent = msg;
        el.style.opacity = 1;
        setTimeout(() => el.style.opacity = 0, 2000);
    }

    // --- Persistence ---
    async function saveProgress() {
        await fetch('index.php?page=games&action=save', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ 
                gameId: 'spellingbee', 
                state: { foundWords }, 
                score 
            })
        });
    }

    async function loadProgress() {
        const res = await fetch('index.php?page=games&action=load&gameId=spellingbee');
        const json = await res.json();
        
        if (json.success && json.data && json.data.state) {
            foundWords = json.data.state.foundWords || [];
            score = json.data.score || 0;
            
            document.getElementById('score-display').textContent = score;
            renderFoundWords();
        }
    }

    renderHive();
    loadProgress();

</script>

<?php include(__DIR__ . '/../Layout/Footer.php'); ?>
