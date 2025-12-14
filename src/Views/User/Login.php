<?php
// /views/User/Login.php

$action = $action ?? 'index.php?page=Login';
$old = $old ?? [];
$errors = $errors ?? [];
$message = $message ?? null;

// Simple escape helper
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | Digital Newsstand</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <link rel="stylesheet" href="../../Assets/CSS/account.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2rem;
            border: 1px solid var(--border-light);
            background: white;
            text-align: center;
        }
        .form-field { margin-bottom: 1.5rem; text-align: left; }
        .form-field label { display: block; font-family: var(--font-sans); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; }
        .form-field input { width: 100%; padding: 0.75rem; border: 1px solid #ccc; font-family: var(--font-sans); box-sizing: border-box; }
        .btn-submit { width: 100%; background: black; color: white; padding: 1rem; border: none; font-weight: bold; cursor: pointer; }
        .btn-submit:hover { opacity: 0.8; }
    </style>
</head>
<body style="background-color: #f9f9f9;">

    <div style="text-align: center; margin-top: 2rem;">
        <a href="index.php" style="font-family: 'Playfair Display', serif; font-size: 2rem; color: black; text-decoration: none; font-weight: 900;">Digital Newsstand</a>
    </div>

    <div class="auth-container">
        <h2 class="serif-headline" style="font-size: 1.5rem; margin-bottom: 2rem;">Log in to your account</h2>

        <?php if (!empty($message)): ?>
            <div style="background: #e6fffa; color: #004d40; padding: 0.5rem; margin-bottom: 1rem; font-family: var(--font-sans);"><?= e($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="background: #fff5f5; color: #9b2c2c; padding: 0.5rem; margin-bottom: 1rem; font-family: var(--font-sans);">
                <?php
                if (is_array($errors)) {
                    foreach ($errors as $err) { echo '<div>' . e($err) . '</div>'; }
                } else {
                    echo e($errors);
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=Login" autocomplete="off">
            <?php if (!empty($csrfToken)): ?>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <?php endif; ?>

            <div class="form-field">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" required autofocus value="<?= e($old['email'] ?? '') ?>">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div style="text-align: left; margin-bottom: 1.5rem;">
                <label style="font-family: var(--font-sans); font-size: 0.9rem;">
                    <input type="checkbox" name="remember" <?= !empty($old['remember']) ? 'checked' : '' ?>> Remember me
                </label>
            </div>

            <button type="submit" class="btn-submit">Log In</button>
            
            <div style="margin-top: 1rem; font-family: var(--font-sans); font-size: 0.9rem;">
                <a href="index.php?page=forgot-password">Forgot password?</a>
            </div>
        </form>
    </div>

    <div style="text-align: center; font-family: var(--font-sans); font-size: 0.9rem;">
        Don't have an account? <a href="index.php?page=register" style="font-weight: bold;">Create one</a>
    </div>

</body>
</html>
