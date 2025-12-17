<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Plans - Admin</title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 1000px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .btn { padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; }
        .btn-primary { background: #333; color: white; }
        .btn-danger { background: #d32f2f; color: white; }
        .table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="page-header">
            <h1>Manage Subscription Plans</h1>
            <div>
                <a href="index.php?page=admin" class="btn">Back to Dashboard</a>
                <a href="index.php?page=admin_plan_create" class="btn btn-primary">Create New Plan</a>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Duration (Days)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                <tr>
                    <td><?= htmlspecialchars($plan['name']) ?></td>
                    <td>$<?= number_format($plan['price'], 2) ?></td>
                    <td><?= $plan['duration_days'] ?></td>
                    <td>
                        <a href="index.php?page=admin_plan_edit&id=<?= $plan['id'] ?>" class="btn">Edit</a>
                        <form action="index.php?page=admin_plan_delete" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="id" value="<?= $plan['id'] ?>">
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
