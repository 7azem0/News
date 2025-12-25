<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Digital Newsstand</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .admin-header {
            margin-bottom: 2rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
        .admin-card {
            background: white;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include __DIR__ . '/Sidebar.php'; ?>
        <main>
        <header class="admin-header">
            <h1>Administration Panel</h1>
            <p>Welcome back, Admin.</p>
            <a href="index.php?page=Home">Return to Site</a>
        </header>
        
            <div class="admin-card">
                <h2>Dashboard Overview</h2>
                <p>Manage users, articles, and site settings here.</p>
                <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <a href="index.php?page=admin_articles" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
                        <h3>Articles</h3>
                        <p>Create, edit, and manage news articles.</p>
                    </a>
                    <a href="index.php?page=admin_users" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
                        <h3>Users</h3>
                        <p>Manage accounts, roles, and suspensions.</p>
                    </a>
                    <a href="index.php?page=admin_plans" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
                        <h3>Subscriptions</h3>
                        <p>Manage subscription plans and pricing.</p>
                    </a>
                    <a href="index.php?page=admin_subscription_assign" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
                        <h3>Assign Plan</h3>
                        <p>Manually grant subscriptions to users.</p>
                    </a>
                    <a href="index.php?page=admin_comments" class="admin-card" style="text-decoration: none; color: inherit; display: block;">
                        <h3>Moderation</h3>
                        <p>Approve, flag, or delete user comments.</p>
                    </a>
                    <!-- More cards will go here -->
                </div>
            </div>
        </main>
    </div>
</div>

