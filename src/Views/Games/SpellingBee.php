<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spelling Bee | News App</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f7da21; /* Bee Yellow */
            --dark: #333;
            --light: #fff;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--dark);
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            background: white;
        }

        .hive {
            position: relative;
            width: 300px;
            height: 300px;
            margin: 2rem auto;
        }
        
        .hex {
            width: 80px;
            height: 92px; /* width * 1.1547 */
            background-color: #e6e6e6;
            margin: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            position: absolute;
            font-size: 2rem;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
            transition: transform 0.1s;
        }

        .hex:active { transform: scale(0.95); }
        
        .hex.center {
            background-color: var(--primary);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        /* Positioning 6 surrounding hexes */
        .hex:nth-child(2) { top: 15%; left: 50%; transform: translateX(-50%); } /* Top */
        .hex:nth-child(3) { top: 29%; left: 74%; } /* Top Right */
        .hex:nth-child(4) { bottom: 29%; left: 74%; } /* Bottom Right */
        .hex:nth-child(5) { bottom: 15%; left: 50%; transform: translateX(-50%); } /* Bottom */
        .hex:nth-child(6) { bottom: 29%; right: 74%; } /* Bottom Left */
        .hex:nth-child(7) { top: 29%; right: 74%; } /* Top Left */


        .input-area {
            font-size: 2rem;
            font-weight: 800;
            height: 3rem;
            margin-bottom: 1rem;
            color: var(--dark);
            text-transform: uppercase;
        }
        
        .cursor {
            border-left: 3px solid #f7da21;
            animation: blink 1s infinite;
        }
        @keyframes blink { 50% { opacity: 0; } }

        .controls {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        button {
            padding: 12px 20px;
            border-radius: 20px;
            border: 1px solid #ddd;
            background: white;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #f0f0f0; }
        
        .enter-btn { background: var(--dark); color: white; border-color: var(--dark); }

        .word-list {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            width: 90%;
            max-width: 400px;
            min-height: 100px;
            background: white;
            text-align: left;
        }
        .word-list h3 { margin-top: 0; }
        .found-words {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .found-word { text-transform: capitalize; border-bottom: 1px solid #eee; padding-bottom: 2px; }

        .message-toast {
            position: fixed;
            top: 10%;
            background: var(--dark);
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

    </style>
</head>
<body>

    <div class="header">
        <a href="index.php?page=games" style="text-decoration:none; color:black; font-weight:bold;">✕</a>
        <span>Score: <strong id="score-display">0</strong></span>
        <!-- Reset implementation similar to Wordle can go here -->
    </div>

    <div class="input-area">
        <span id="input-text"></span><span class="cursor"></span>
    </div>

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

    <div class="controls">
        <button onclick="deleteChar()">Delete</button>
        <button onclick="shuffleLetters()">Shuffle</button>
        <button class="enter-btn" onclick="submitWord()">Enter</button>
    </div>

    <div class="word-list">
        <h3>Found Words</h3>
        <div class="found-words" id="found-words-list"></div>
    </div>

    <div class="message-toast" id="toast"></div>

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
        // Update global LETTERS order visual only? Or logic too?
        // Logic relies on CENTER being index 0. We only shuffle outer.
        
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
</body>
</html>
