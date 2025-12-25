
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; font-size: 0.9rem; }
        .btn-primary { background: #333; color: white; }
        .btn-warning { background: #fbc02d; color: black; }
        .btn-danger { background: #d32f2f; color: white; }
        .btn-success { background: #388e3c; color: white; }
        .table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; }
        .badge-active { background: #e8f5e9; color: #2e7d32; }
        .badge-suspended { background: #ffebee; color: #c62828; }
        .role-admin { font-weight: bold; color: #1976d2; }
    </style>
</head>
<body>
    <div class="admin-page">
        <?php include __DIR__ . '/../Sidebar.php'; ?>
        <main>
            <div class="admin-page">
        <div class="page-header">
            <h1>Manage Users</h1>
            <a href="index.php?page=admin" class="btn">Back to Dashboard</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                        <?php if ($user['is_admin']): ?>
                            <span class="role-admin">Admin</span>
                        <?php else: ?>
                            User
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-<?= $user['status'] ?? 'active' ?>">
                            <?= ucfirst($user['status'] ?? 'active') ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            
                            <!-- Toggle Status -->
                            <form action="index.php?page=admin_user_toggle" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="status" value="<?= $user['status'] ?? 'active' ?>">
                                <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                    <button type="submit" class="btn btn-warning">Suspend</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success">Activate</button>
                                <?php endif; ?>
                            </form>

                            <!-- Delete -->
                            <form action="index.php?page=admin_user_delete" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this user?');">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            
                            <!-- Promote -->
                            <?php if (!$user['is_admin']): ?>
                                <form action="index.php?page=admin_user_promote" method="POST" style="display:inline;" onsubmit="return confirm('Make this user an Admin?');">
                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Make Admin</button>
                                </form>
                            <?php endif; ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        </main>
    </div>
</div>

