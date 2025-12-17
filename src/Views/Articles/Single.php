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
