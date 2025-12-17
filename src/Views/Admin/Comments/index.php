<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moderate Comments - Admin</title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; font-size: 0.9rem; }
        .btn-success { background: #388e3c; color: white; }
        .btn-warning { background: #fbc02d; color: black; }
        .btn-danger { background: #d32f2f; color: white; }
        .table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; }
        .badge-approved { background: #e8f5e9; color: #2e7d32; }
        .badge-pending { background: #fff3e0; color: #ef6c00; }
        .badge-flagged { background: #ffebee; color: #c62828; }
        .comment-content { max-width: 400px; }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="page-header">
            <h1>Moderate Comments</h1>
            <a href="index.php?page=admin" class="btn">Back to Dashboard</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Article</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= htmlspecialchars($comment['username'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($comment['article_title'] ?? 'Unknown') ?></td>
                    <td class="comment-content"><?= htmlspecialchars(substr($comment['content'], 0, 100)) ?>...</td>
                    <td>
                        <span class="badge badge-<?= $comment['status'] ?? 'pending' ?>">
                            <?= ucfirst($comment['status'] ?? 'pending') ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y H:i', strtotime($comment['created_at'])) ?></td>
                    <td>
                        <?php if (($comment['status'] ?? 'pending') !== 'approved'): ?>
                            <form action="index.php?page=admin_comment_approve" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                        <?php endif; ?>

                        <?php if (($comment['status'] ?? 'pending') !== 'flagged'): ?>
                            <form action="index.php?page=admin_comment_reject" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                <button type="submit" class="btn btn-warning">Flag</button>
                            </form>
                        <?php endif; ?>

                        <form action="index.php?page=admin_comment_delete" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this comment?');">
                            <input type="hidden" name="id" value="<?= $comment['id'] ?>">
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
