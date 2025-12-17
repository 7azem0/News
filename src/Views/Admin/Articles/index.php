<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Articles - Admin</title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-page { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; }
        .btn-primary { background: #333; color: white; }
        .btn-danger { background: #d32f2f; color: white; }
        .table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; }
        .badge-draft { background: #eee; color: #333; }
        .badge-published { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="page-header">
            <h1>Manage Articles</h1>
            <div>
                <a href="index.php?page=admin" class="btn">Back to Dashboard</a>
                <a href="index.php?page=admin_article_create" class="btn btn-primary">Create New Article</a>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Visibility</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article['title']) ?></td>
                    <td><?= htmlspecialchars($article['author']) ?></td>
                    <td>
                        <span class="badge badge-<?= $article['status'] ?? 'draft' ?>">
                            <?= ucfirst($article['status'] ?? 'draft') ?>
                        </span>
                    </td>
                    <td><?= ucfirst($article['visibility'] ?? 'public') ?></td>
                    <td><?= date('M j, Y', strtotime($article['created_at'])) ?></td>
                    <td>
                        <a href="index.php?page=admin_article_edit&id=<?= $article['id'] ?>" class="btn">Edit</a>
                        <form action="index.php?page=admin_article_delete" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?= $article['id'] ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
