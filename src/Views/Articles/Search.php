<?php include __DIR__ . '/../Layout/Header.php'; ?>

<div class="container" style="margin-top: 3rem; margin-bottom: 3rem;">
    <div class="row" style="display: grid; grid-template-columns: 1fr 3fr; gap: 3rem;">
        
        <!-- Sidebar Filters -->
        <aside class="search-sidebar">
            <h3 class="sans-text" style="font-weight: 700; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px; margin-bottom: 1.5rem; border-bottom: 2px solid black; padding-bottom: 10px;">Filter News</h3>
            
            <form method="GET" action="index.php" style="background: #f9f9f9; padding: 1.5rem; border: 1px solid #eee;">
                <input type="hidden" name="page" value="search">
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Search Term</label>
                    <input type="text" name="q" value="<?= htmlspecialchars($query ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Keywords..." style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Category</label>
                    <select name="category" style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                        <option value="">All Categories</option>
                        <?php foreach ($allCategories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($category) && $category === $cat) ? 'selected' : '' ?>>
                                <?= htmlspecialchars(ucfirst($cat)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Tags</label>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: white;">
                        <?php 
                            $selectedTags = is_array($tags) ? $tags : ($tags ? [$tags] : []);
                        ?>
                        <?php foreach ($allTags as $t): ?>
                            <div style="margin-bottom: 5px;">
                                <label style="font-family: var(--font-sans); font-size: 0.85rem; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($t) ?>" <?= in_array($t, $selectedTags) ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($t) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Author</label>
                    <select name="author" style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                        <option value="">All Authors</option>
                        <?php foreach ($allAuthors ?? [] as $a): ?>
                            <option value="<?= htmlspecialchars($a) ?>" <?= (isset($author) && $author === $a) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-family: var(--font-sans); font-size: 0.8rem; font-weight: bold; margin-bottom: 5px;">Language</label>
                    <select name="language" style="width: 100%; padding: 8px; border: 1px solid #ddd;">
                        <option value="">All Languages</option>
                        <?php foreach ($allLanguages ?? [] as $lang): ?>
                            <option value="<?= htmlspecialchars($lang) ?>" <?= (isset($language) && $language === $lang) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lang) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; border: none; cursor: pointer;">Update Results</button>
                <a href="index.php?page=search" style="display: block; text-align: center; margin-top: 10px; font-size: 0.8rem; color: #666; text-decoration: underline;">Clear Filters</a>
            </form>
        </aside>

        <!-- Results Area -->
        <section class="search-results">
            <h2 class="serif-headline" style="font-size: 2rem; margin-bottom: 0.5rem;">Search Results</h2>
            <p style="font-family: var(--font-sans); color: #666; margin-bottom: 2rem;">
                <?php 
                    $count = count($articles ?? []) + count($news ?? []);
                    echo $count . " result" . ($count !== 1 ? 's' : '') . " found";
                    if (!empty($query)) echo " for &quot;" . htmlspecialchars($query) . "&quot;";
                ?>
            </p>

            <div class="results-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 2rem;">
                
                <!-- Local Articles -->
                <?php if (!empty($articles)): ?>
                    <?php foreach ($articles as $a): ?>
                        <article class="news-card">
                            <?php if (!empty($a['thumbnail'])): ?>
                                <a href="index.php?page=article&id=<?= $a['id'] ?>">
                                    <img src="<?= htmlspecialchars($a['thumbnail']) ?>" alt="" style="width: 100%; height: 180px; object-fit: cover; margin-bottom: 1rem; display: block;">
                                </a>
                            <?php endif; ?>
                            
                            <div class="meta" style="font-size: 0.75rem; color: var(--accent-color); font-weight: bold; text-transform: uppercase; margin-bottom: 0.5rem;">
                                <?= htmlspecialchars($a['category'] ?? 'Article') ?>
                            </div>

                            <a href="index.php?page=article&id=<?= $a['id'] ?>" style="text-decoration: none; color: black;">
                                <h3 class="serif-headline" style="font-size: 1.3rem; margin-bottom: 0.5rem; line-height: 1.2;">
                                    <?= htmlspecialchars($a['title']) ?>
                                </h3>
                            </a>
                            
                            <p style="font-size: 0.95rem; color: #555; line-height: 1.5;">
                                <?= htmlspecialchars(substr($a['description'] ?? '', 0, 100)) ?>...
                            </p>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- External News (Only if query is set) -->
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $n): ?>
                        <article class="news-card external" style="opacity: 0.9;">
                             <?php if (!empty($n['urlToImage'])): ?>
                                <a href="<?= htmlspecialchars($n['url']) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($n['urlToImage']) ?>" alt="" style="width: 100%; height: 180px; object-fit: cover; margin-bottom: 1rem; display: block; filter: grayscale(10%);">
                                </a>
                            <?php endif; ?>
                             <div class="meta" style="font-size: 0.75rem; color: #666; font-weight: bold; text-transform: uppercase; margin-bottom: 0.5rem;">
                                World News
                            </div>
                            <a href="<?= htmlspecialchars($n['url']) ?>" target="_blank" style="text-decoration: none; color: black;">
                                <h3 class="serif-headline" style="font-size: 1.3rem; margin-bottom: 0.5rem; line-height: 1.2;">
                                    <?= htmlspecialchars($n['title']) ?> <span style="font-size: 1rem;">&#8599;</span>
                                </h3>
                            </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            <?php if (empty($articles) && empty($news)): ?>
                <div style="padding: 3rem; background: #f9f9f9; text-align: center; border: 1px dashed #ddd;">
                    <h3 class="sans-text" style="color: #888;">No results found</h3>
                    <p>Try adjusting your filters or search term.</p>
                </div>
            <?php endif; ?>

        </section>
    </div>
</div>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
