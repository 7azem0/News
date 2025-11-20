<?php
// /views/User/Register.php
$action = $action ?? 'index.php?page=register';
$old = $old ?? [];
$errors = $errors ?? [];
$message = $message ?? null;

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/Assets/CSS/Styles.css">
</head>
<body class="auth-page">
    <div class="card" role="main" aria-labelledby="register-heading">
        <h1 id="register-heading">Create an account</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?= e($message) ?></div>
        <?php endif; ?>

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

        <form method="post" action="<?= e($action) ?>" autocomplete="off" novalidate>
            <?php if (!empty($csrfToken)): ?>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <?php endif; ?>

            <div class="field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required
                       value="<?= e($old['username'] ?? '') ?>" placeholder="Your username">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required
                       value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Password">
            </div>

            <div class="field">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password" required placeholder="Confirm Password">
            </div>

            <div class="actions">
                <button type="submit">Register</button>
                <a class="muted" href="index.php?page=Login">Already have an account?</a>
            </div>
        </form>
    </div>
</body>
</html>
