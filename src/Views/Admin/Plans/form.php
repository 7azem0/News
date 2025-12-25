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
        .form-group { margin-bottom: 1.25rem; }
        .form-control { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 0.8rem 1.25rem; background: #333; color: white; border: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="admin-page">
        <?php include __DIR__ . '/../Sidebar.php'; ?>
        <main>
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
        </main>
    </div>
</div>

