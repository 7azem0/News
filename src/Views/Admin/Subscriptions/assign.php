<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Subscription - Admin</title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 0.8rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; background: #333; color: white; font-size: 1rem; }
    </style>
</head>
<body>
    <div class="admin-page">
        <h1>Assign Subscription</h1>
        
        <form action="index.php?page=admin_subscription_store_assignment" method="POST">
            
            <div class="form-group">
                <label>Select User</label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Choose User --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>">
                            <?= htmlspecialchars($user['username']) ?> (<?= $user['email'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Select Plan</label>
                <select name="plan_id" class="form-control" required>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?= $plan['id'] ?>">
                            <?= htmlspecialchars($plan['name']) ?> - $<?= $plan['price'] ?> (<?= $plan['duration_days'] ?> days)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Duration (Days Override)</label>
                <input type="number" name="duration_days" class="form-control" value="30" required>
                <small>Leave as is to use plan default, or modify for complimentary access period.</small>
            </div>

            <button type="submit" class="btn">Assign Subscription</button>
            <a href="index.php?page=admin_users" style="margin-left: 1rem;">Cancel</a>
        </form>
    </div>
</body>
</html>
