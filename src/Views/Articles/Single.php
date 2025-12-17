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

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
