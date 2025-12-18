<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="container" style="max-width: 800px; padding-top: 2rem;">

    <article class="single-article">
        <!-- Header -->
        <div class="article-header-container" style="margin-bottom: 2rem; text-align: center;">
             <h1 class="serif-headline" style="font-size: 2rem; line-height: 1.25; margin-bottom: 1rem;">
                <?= htmlspecialchars($displayArticle['title'] ?? $article['title'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <div style="font-family: var(--font-sans); color: #666; font-size: 0.9rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1.5rem; margin-bottom: 2rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                <span>By <strong><?= htmlspecialchars($article['author'] ?? 'Staff Writer') ?></strong></span>
                <span>•</span>
                <span><?= date('F j, Y', strtotime($article['publishedAt'] ?? 'now')) ?></span>
                
                <?php if (empty($displayArticle['is_blocked'])): ?>
                    <button id="start-listen-btn" class="sans-text" style="background: #000; color: #fff; border: none; padding: 4px 12px; border-radius: 4px; font-size: 0.75rem; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 6px; letter-spacing: 1px; transition: transform 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                        LISTEN
                    </button>
                <?php endif; ?>

                <?php if (empty($displayArticle['is_blocked']) && isset($availableLangs, $selectedLang)): ?>
                    <form method="GET" action="" style="margin:0; margin-left: 1rem;">
                        <input type="hidden" name="page" value="article">
                        <input type="hidden" name="id" value="<?= (int)$article['id'] ?>">
                        <select name="lang" onchange="this.form.submit()" style="padding: 2px; font-size: 0.8rem;">
                            <?php foreach ($availableLangs as $code => $name): ?>
                                <option value="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedLang === $code ? 'selected' : '' ?>>
                                     Translate: <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Image -->
        <?php if (empty($displayArticle['is_blocked']) && !empty($article['thumbnail'])): ?>
            <figure style="margin-bottom: 2rem;">
                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Article Image" style="width: 100%; height: auto; display: block;">
                <figcaption style="font-family: var(--font-sans); font-size: 0.8rem; color: #888; margin-top: 0.5rem;">
                   <?= htmlspecialchars($article['title']) ?>
                </figcaption>
            </figure>
        <?php elseif (!empty($displayArticle['is_blocked'])): ?>
            <div style="background: #f1f1f1; height: 300px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem; border-radius: 8px; border: 1px dashed #ccc;">
                <p style="color: #666;">Premium Article Content Blocked</p>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="article-body" style="font-family: var(--font-serif); font-size: 1.25rem; line-height: 1.8; color: #333;">
            <?php if (!empty($displayArticle['is_blocked'])): ?>
                <?= $displayArticle['content'] ?>
            <?php else: ?>
                <?= nl2br(htmlspecialchars($displayArticle['content'] ?? $article['content'], ENT_QUOTES, 'UTF-8')) ?>
            <?php endif; ?>
        </div>

        <!-- Like & Save Buttons -->
        <?php if (empty($displayArticle['is_blocked'])): ?>
            <div class="article-actions" style="margin-top: 2rem; padding: 1.5rem 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; display: flex; gap: 1rem; align-items: center;">
                <!-- Like Button -->
                <button 
                    id="like-btn" 
                    data-article-id="<?= (int)$article['id'] ?>"
                    data-liked="<?= $isLiked ? 'true' : 'false' ?>"
                    style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 50px; transition: all 0.3s ease; <?= $isLiked ? 'background: #ffe6e6;' : 'background: #f5f5f5;' ?>"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'"
                >
                    <svg id="heart-icon" width="24" height="24" viewBox="0 0 24 24" style="transition: all 0.3s ease;">
                        <path 
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" 
                            fill="<?= $isLiked ? '#ff4757' : 'none' ?>" 
                            stroke="<?= $isLiked ? '#ff4757' : '#666' ?>" 
                            stroke-width="2"
                        />
                    </svg>
                    <span id="like-count" style="font-weight: bold; color: <?= $isLiked ? '#ff4757' : '#666' ?>;">
                        <?= $likeCount ?>
                    </span>
                </button>

                <!-- Save Button -->
                <button 
                    id="save-btn" 
                    data-article-id="<?= (int)$article['id'] ?>"
                    data-saved="<?= $isSaved ? 'true' : 'false' ?>"
                    style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 50px; transition: all 0.3s ease; <?= $isSaved ? 'background: #e6f7ff;' : 'background: #f5f5f5;' ?>"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'"
                >
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path 
                            d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" 
                            fill="<?= $isSaved ? '#1e90ff' : 'none' ?>" 
                            stroke="<?= $isSaved ? '#1e90ff' : '#666' ?>" 
                            stroke-width="2"
                        />
                    </svg>
                    <span style="font-weight: bold; color: <?= $isSaved ? '#1e90ff' : '#666' ?>;">
                        <?= $isSaved ? 'Saved' : 'Save' ?>
                    </span>
                </button>

                <!-- PDF Download Button -->
                <?php if ($canDownloadPdf): ?>
                    <a href="?page=article_download_pdf&id=<?= (int)$article['id'] ?>" 
                       target="_blank"
                       style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 50px; transition: all 0.3s ease; background: #f5f5f5; text-decoration: none; color: #666;"
                       onmouseover="this.style.transform='scale(1.05)'; this.style.background='#e8f5e9'"
                       onmouseout="this.style.transform='scale(1)'; this.style.background='#f5f5f5'">
                        <svg width="24" height="24" viewBox="0 0 24 24">
                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" fill="#4caf50"/>
                        </svg>
                        <span style="font-weight: bold; color: #4caf50;">Download PDF</span>
                    </a>
                <?php elseif (isset($_SESSION['user_id'])): ?>
                    <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 50px; background: #fff3cd; border: 1px solid #ffc107;">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="#856404"/>
                        </svg>
                        <span style="font-size: 0.85rem; color: #856404;">
                            <strong>Pro Feature:</strong> <a href="?page=plans" style="color: #856404; text-decoration: underline;">Upgrade to Pro</a> to download PDFs
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <style>
                @keyframes heartBeat {
                    0%, 100% { transform: scale(1); }
                    25% { transform: scale(1.3); }
                    50% { transform: scale(1.1); }
                }
                
                .heart-animate {
                    animation: heartBeat 0.5s ease;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const likeBtn = document.getElementById('like-btn');
                    const saveBtn = document.getElementById('save-btn');
                    const heartIcon = document.getElementById('heart-icon');
                    const likeCount = document.getElementById('like-count');

                    // Like button handler
                    likeBtn.addEventListener('click', function() {
                        const articleId = this.dataset.articleId;
                        const isLiked = this.dataset.liked === 'true';

                        fetch('index.php?page=article_like', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'article_id=' + articleId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Animate heart
                                heartIcon.classList.add('heart-animate');
                                setTimeout(() => heartIcon.classList.remove('heart-animate'), 500);

                                // Update UI
                                const newLiked = data.action === 'liked';
                                this.dataset.liked = newLiked;
                                likeCount.textContent = data.count;
                                
                                const path = heartIcon.querySelector('path');
                                if (newLiked) {
                                    path.setAttribute('fill', '#ff4757');
                                    path.setAttribute('stroke', '#ff4757');
                                    likeCount.style.color = '#ff4757';
                                    this.style.background = '#ffe6e6';
                                } else {
                                    path.setAttribute('fill', 'none');
                                    path.setAttribute('stroke', '#666');
                                    likeCount.style.color = '#666';
                                    this.style.background = '#f5f5f5';
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });

                    // Save button handler
                    saveBtn.addEventListener('click', function() {
                        const articleId = this.dataset.articleId;
                        const isSaved = this.dataset.saved === 'true';

                        fetch('index.php?page=article_save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'article_id=' + articleId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update UI
                                const newSaved = data.action === 'saved';
                                this.dataset.saved = newSaved;
                                
                                const path = this.querySelector('path');
                                const span = this.querySelector('span');
                                
                                if (newSaved) {
                                    path.setAttribute('fill', '#1e90ff');
                                    path.setAttribute('stroke', '#1e90ff');
                                    span.textContent = 'Saved';
                                    span.style.color = '#1e90ff';
                                    this.style.background = '#e6f7ff';
                                } else {
                                    path.setAttribute('fill', 'none');
                                    path.setAttribute('stroke', '#666');
                                    span.textContent = 'Save';
                                    span.style.color = '#666';
                                    this.style.background = '#f5f5f5';
                                }
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });
            </script>
        <?php endif; ?>

    </article>

    <?php if (empty($displayArticle['is_blocked'])): ?>
        <!-- Comments Section -->
        <div class="comments-section" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light);">
            <h2 style="font-family: var(--font-serif); font-size: 1.5rem; margin-bottom: 1.5rem;">
                Comments (<?= count($comments) ?>)
            </h2>

            <!-- Display Comments -->
            <?php if (!empty($comments)): ?>
                <div class="comments-list" style="margin-bottom: 2rem;">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment" style="padding: 1rem; margin-bottom: 1rem; background: #f9f9f9; border-left: 3px solid #667eea; border-radius: 4px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <strong style="color: #333;"><?= htmlspecialchars($comment['username'] ?? 'Anonymous') ?></strong>
                                <span style="color: #888; font-size: 0.85rem;"><?= date('F j, Y \a\t g:i A', strtotime($comment['created_at'])) ?></span>
                            </div>
                            <p style="margin: 0; color: #555; line-height: 1.6;"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="color: #888; font-style: italic; margin-bottom: 2rem;">No comments yet. Be the first to comment!</p>
            <?php endif; ?>

            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_GET['comment']) && $_GET['comment'] === 'success'): ?>
                    <div style="padding: 1rem; margin-bottom: 1rem; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                        Your comment has been submitted and is pending approval.
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'empty'): ?>
                    <div style="padding: 1rem; margin-bottom: 1rem; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                        Please enter a comment before submitting.
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=comment_store" style="margin-top: 1.5rem;">
                    <input type="hidden" name="article_id" value="<?= (int)$article['id'] ?>">
                    <div style="margin-bottom: 1rem;">
                        <label for="comment-content" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">
                            Add a Comment
                        </label>
                        <textarea 
                            id="comment-content" 
                            name="content" 
                            rows="4" 
                            required
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; font-size: 1rem; resize: vertical;"
                            placeholder="Share your thoughts..."
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: transform 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.transform='translateY(0)'"
                    >
                        Post Comment
                    </button>
                </form>
            <?php else: ?>
                <div style="padding: 1.5rem; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; text-align: center;">
                    <p style="margin: 0 0 1rem 0; color: #666;">Please log in to leave a comment.</p>
                    <a href="?page=login" style="display: inline-block; background: #667eea; color: white; padding: 0.5rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: bold;">
                        Log In
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="divider"></div>

    <div style="text-align: center; margin: 3rem 0;">
        <a href="?page=article" class="btn-primary" style="background: white; color: black; border: 1px solid #ccc;">&larr; Back to All Articles</a>
    </div>

</main>

<!-- Listening Mode UI (Hidden initially) -->
<div id="listening-player" style="position: fixed; bottom: 0; left: 0; width: 100%; height: 0; background: #fff; border-top: 1px solid #000; z-index: 1000; transition: height 0.4s ease; overflow: hidden; box-shadow: 0 -10px 30px rgba(0,0,0,0.1);">
    <div style="max-width: 1200px; margin: 0 auto; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 2rem;">
            <!-- Simple Waveform Animation -->
            <div id="waveform" style="display: flex; align-items: flex-end; gap: 3px; height: 24px; width: 40px;">
                <div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div><div class="wave-bar"></div>
            </div>
            <div>
                <div style="font-family: var(--font-sans); font-size: 0.7rem; font-weight: 900; text-transform: uppercase; color: #666; letter-spacing: 1px;">Listening Mode</div>
                <div id="player-title" style="font-family: var(--font-serif); font-size: 1rem; font-weight: 700; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;">
                    <?= htmlspecialchars($displayArticle['title'] ?? $article['title']) ?>
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <!-- Controls -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button id="listen-prev" style="background: none; border: none; cursor: pointer; color: #000;"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg></button>
                <button id="listen-toggle" style="background: #000; color: #fff; border: none; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s;">
                    <svg id="play-icn" width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    <svg id="pause-icn" style="display:none;" width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                </button>
                <button id="listen-next" style="background: none; border: none; cursor: pointer; color: #000;"><svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11 18l8.5-6L11 6zm-5-12h2v12H6z"/></svg></button>
            </div>

            <!-- Speed Selector -->
            <div style="display: flex; align-items: center; gap: 0.5rem; background: #f5f5f5; padding: 4px 12px; border-radius: 4px;">
                <span style="font-family: var(--font-sans); font-size: 0.75rem; font-weight: 800; color: #666;">SPEED</span>
                <select id="listen-speed" style="background: none; border: none; font-family: var(--font-sans); font-size: 0.85rem; font-weight: 800; cursor: pointer; outline: none;">
                    <option value="1">1x</option>
                    <option value="1.25">1.25x</option>
                    <option value="1.5">1.5x</option>
                    <option value="2">2x</option>
                </select>
            </div>
        </div>

        <button id="close-player" style="background: none; border: none; cursor: pointer; padding: 10px; color: #888;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>
</div>

<style>
    .wave-bar { width: 4px; background: #000; border-radius: 2px; }
    .wave-bar:nth-child(1) { height: 40%; }
    .wave-bar:nth-child(2) { height: 70%; }
    .wave-bar:nth-child(3) { height: 100%; }
    .wave-bar:nth-child(4) { height: 60%; }
    .wave-bar:nth-child(5) { height: 30%; }

    .playing .wave-bar { animation: wave 1s ease-in-out infinite; }
    .playing .wave-bar:nth-child(1) { animation-delay: 0s; }
    .playing .wave-bar:nth-child(2) { animation-delay: 0.2s; }
    .playing .wave-bar:nth-child(3) { animation-delay: 0.4s; }
    .playing .wave-bar:nth-child(4) { animation-delay: 0.1s; }
    .playing .wave-bar:nth-child(5) { animation-delay: 0.3s; }

    @keyframes wave {
        0%, 100% { transform: scaleY(0.4); }
        50% { transform: scaleY(1.2); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('start-listen-btn');
        const player = document.getElementById('listening-player');
        const toggleBtn = document.getElementById('listen-toggle');
        const playIcn = document.getElementById('play-icn');
        const pauseIcn = document.getElementById('pause-icn');
        const closeBtn = document.getElementById('close-player');
        const speedSelect = document.getElementById('listen-speed');
        const waveform = document.getElementById('waveform');
        
        const synth = window.speechSynthesis;
        let utterance = null;
        let isPaused = false;

        if (!btn) return;

        btn.onclick = () => {
            player.style.height = '100px';
            if (!synth.speaking) startSpeaking();
        };

        closeBtn.onclick = () => {
            player.style.height = '0';
            synth.cancel();
            updateUI(false);
        };

        toggleBtn.onclick = () => {
            if (synth.speaking && !isPaused) {
                synth.pause();
                isPaused = true;
                updateUI(false);
            } else if (isPaused) {
                synth.resume();
                isPaused = false;
                updateUI(true);
            } else {
                startSpeaking();
            }
        };

        speedSelect.onchange = () => {
            if (synth.speaking) {
                const currentText = utterance.text;
                synth.cancel();
                startSpeaking(currentText); 
            }
        };

        const currentLang = '<?= $selectedLang ?>';
        const langMap = {
            'en': 'en-US', 'es': 'es-ES', 'fr': 'fr-FR', 'de': 'de-DE',
            'it': 'it-IT', 'pt': 'pt-PT', 'ru': 'ru-RU', 'zh': 'zh-CN',
            'nl': 'nl-NL', 'no': 'no-NO', 'sv': 'sv-SE', 'he': 'he-IL',
            'ar': 'ar-SA', 'ur': 'ur-PK', 'ja': 'ja-JP', 'ko': 'ko-KR'
        };

        // Initialize voices
        let voices = [];
        const loadVoices = () => {
            voices = synth.getVoices();
            console.log("[TTS] Loaded " + voices.length + " voices");
        };
        if (synth.onvoiceschanged !== undefined) synth.onvoiceschanged = loadVoices;
        loadVoices();

        function startSpeaking() {
            const bodyEl = document.querySelector('.article-body');
            const titleEl = document.querySelector('.serif-headline');
            const content = bodyEl ? bodyEl.innerText : '';
            const title = titleEl ? titleEl.innerText : '';
            const textToRead = title + ". " + content;

            if (!textToRead.trim()) return;

            // Stop any current reading
            synth.cancel();
            isPaused = false;

            const targetLang = langMap[currentLang] || 'en-US';
            const langPrefix = targetLang.split('-')[0].toLowerCase();
            
            // Search for a voice that matches the language
            const findVoice = () => {
                const allVoices = synth.getVoices();
                return allVoices.find(v => v.lang.toLowerCase() === targetLang.toLowerCase()) ||
                       allVoices.find(v => v.lang.toLowerCase().startsWith(langPrefix)) ||
                       allVoices.find(v => v.name.toLowerCase().includes('arabic') && langPrefix === 'ar');
            };

            const selectedVoice = findVoice();
            console.log("[TTS] Selected Voice:", selectedVoice ? selectedVoice.name : "None found, using system default");

            const utterance = new SpeechSynthesisUtterance(textToRead);
            utterance.lang = targetLang;
            if (selectedVoice) {
                utterance.voice = selectedVoice;
                // Some browsers need the lang to match the voice exactly to avoid defaulting to English
                utterance.lang = selectedVoice.lang;
            }
            
            utterance.rate = parseFloat(speedSelect.value);

            utterance.onstart = () => updateUI(true);
            utterance.onend = () => {
                updateUI(false);
                player.style.height = '0';
            };
            utterance.onerror = (e) => {
                console.error("[TTS] Error:", e);
                updateUI(false);
            };

            // Force RTL UI for Arabic/Hebrew/Urdu
            document.getElementById('player-title').style.direction = ['ar', 'he', 'ur'].includes(currentLang) ? 'rtl' : 'ltr';

            // Speak after a small timeout to let cancel() settle
            setTimeout(() => {
                synth.speak(utterance);
            }, 100);
        }

        function updateUI(playing) {
            if (playing) {
                playIcn.style.display = 'none';
                pauseIcn.style.display = 'block';
                waveform.classList.add('playing');
            } else {
                playIcn.style.display = 'block';
                pauseIcn.style.display = 'none';
                waveform.classList.remove('playing');
            }
        }
    });
</script>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
