<?php
$isEdit = isset($plan);
$action = $isEdit ? 'index.php?page=admin_plan_update' : 'index.php?page=admin_plan_store';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Edit Plan' : 'New Plan' ?></title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-page { padding: 2rem; max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .form-control { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; }
        textarea.form-control { height: 100px; font-family: inherit; }
        .btn { padding: 0.8rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; background: #333; color: white; font-size: 1rem; }
    </style>
</head>
<body>
    <div class="admin-page">
        <h1><?= $isEdit ? 'Edit Plan' : 'Create New Plan' ?></h1>
        
        <form action="<?= $action ?>" method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $plan['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Plan Name</label>
                <input type="text" name="name" class="form-control" value="<?= $plan['name'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $plan['price'] ?? '' ?>" required>
            </div>

            <div class="form-group">
                <label>Duration (Days)</label>
                <input type="number" name="duration_days" class="form-control" value="<?= $plan['duration_days'] ?? 30 ?>" required>
            </div>

            <div class="form-group">
                <label>Features (JSON or Comma Separated)</label>
                <textarea name="features" class="form-control"><?= $plan['features'] ?? '' ?></textarea>
            </div>

            <button type="submit" class="btn"><?= $isEdit ? 'Update Plan' : 'Create Plan' ?></button>
            <a href="index.php?page=admin_plans" style="margin-left: 1rem;">Cancel</a>
        </form>
    </div>
</body>
</html>
