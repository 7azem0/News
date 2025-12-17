<?php
$isEdit = isset($article);
$action = $isEdit ? 'index.php?page=admin_article_update' : 'index.php?page=admin_article_store';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Edit Packet' : 'New Article' ?></title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; }
        textarea.form-control { height: 300px; font-family: inherit; }
        .btn { padding: 0.8rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; background: #333; color: white; font-size: 1rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    </style>
</head>
<body>
    <div class="admin-page">
        <h1><?= $isEdit ? 'Edit Article' : 'Create New Article' ?></h1>
        
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 1rem;"><?= $error ?></div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $article['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= $article['thumbnail'] ?? '' ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= $article['title'] ?? '' ?>" required>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" value="<?= $article['author'] ?? $_SESSION['username'] ?? 'Admin' ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" <?= ($article['category'] ?? 'general') == $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(ucfirst($cat)) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="general">General</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="grid">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= ($article['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($article['status'] ?? '') == 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= ($article['status'] ?? '') == 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Visibility</label>
                    <select name="visibility" class="form-control" onchange="togglePlanSelect(this)">
                        <option value="public" <?= ($article['visibility'] ?? '') == 'public' ? 'selected' : '' ?>>Public</option>
                        <option value="subscribed" <?= ($article['visibility'] ?? '') == 'subscribed' ? 'selected' : '' ?>>Subscribers Only</option>
                    </select>
                </div>
                
                <div class="form-group" id="plan-select-group" style="display: <?= ($article['visibility'] ?? '') == 'subscribed' ? 'block' : 'none' ?>;">
                    <label>Minimum Plan Required (Higher Price = Higher Tier)</label>
                    <select name="required_plan_id" class="form-control">
                        <option value="">Any Subscription (Basic+)</option>
                        <?php if (isset($plans)): ?>
                            <?php foreach ($plans as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= ($article['required_plan_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['name']) ?> ($<?= $p['price'] ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <script>
                function togglePlanSelect(select) {
                    var planGroup = document.getElementById('plan-select-group');
                    if (select.value === 'subscribed') {
                        planGroup.style.display = 'block';
                    } else {
                        planGroup.style.display = 'none';
                    }
                }
            </script>

            <div class="form-group">
                <label>Scheduled Publish Date (Optional)</label>
                <input type="datetime-local" name="scheduled_at" class="form-control" value="<?= isset($article['scheduled_at']) ? date('Y-m-d\TH:i', strtotime($article['scheduled_at'])) : '' ?>">
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea name="description" class="form-control" required><?= $article['content'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>Tags (comma separated)</label>
                <input type="text" name="tags" class="form-control" value="<?= $article['tags'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label>Featured Image</label>
                <?php if (!empty($article['thumbnail'])): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="<?= $article['thumbnail'] ?>" style="max-width: 200px; border-radius: 4px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_featured" value="1" <?= ($article['is_featured'] ?? 0) == 1 ? 'checked' : '' ?>>
                    Feature this article on Homepage
                </label>
            </div>

            <button type="submit" class="btn"><?= $isEdit ? 'Update Article' : 'Create Article' ?></button>
            <a href="index.php?page=admin_articles" style="margin-left: 1rem;">Cancel</a>
        </form>
    </div>
</body>
</html>
