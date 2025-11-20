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
    <title>Login</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/Assets/CSS/Styles.css">
</head>
<body>
    <div class="card" role="main" aria-labelledby="login-heading">
        <h1 id="login-heading">Sign in to your account</h1>

        <!-- Flash message -->
        <?php if (!empty($message)): ?>
            <div class="message"><?= e($message) ?></div>
        <?php endif; ?>

        <!-- Errors -->
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php
                if (is_array($errors)) {
                    foreach ($errors as $err) {
                        echo '<div>' . e($err) . '</div>';
                    }
                } else {
                    echo e($errors);
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=Login" autocomplete="off" novalidate>

            <!-- CSRF token -->
            <?php if (!empty($csrfToken)): ?>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <?php endif; ?>

            <!-- Email -->
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com">
            </div>

            <!-- Password -->
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Password">
            </div>

            <!-- Remember me -->
            <div class="field">
                <label class="small">
                    <input type="checkbox" name="remember" <?= !empty($old['remember']) ? 'checked' : '' ?>> Remember me
                </label>
            </div>

            <!-- Actions -->
            <div class="actions">
                <button type="submit">Sign in</button>
                <a class="muted" href="index.php?page=forgot-password">Forgot?</a>
            </div>

            <!-- Register link -->
            <p class="small" style="margin-top:14px">
                Don't have an account? <a href="index.php?page=register">Register</a>
            </p>
        </form>
    </div>
</body>
</html>
