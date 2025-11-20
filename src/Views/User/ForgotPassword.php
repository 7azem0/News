<?php
// /views/User/ForgotPassword.php
$action = $action ?? 'index.php?page=forgot-password';
$old = $old ?? [];
$errors = $errors ?? [];
$message = $message ?? null;

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/Assets/CSS/Styles.css">
</head>
<body class="auth-page">
    <div class="card" role="main" aria-labelledby="forgot-heading">
        <h1 id="forgot-heading">Reset your password</h1>

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
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required
                       value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com">
            </div>

            <div class="actions">
                <button type="submit">Send Reset Link</button>
                <a class="muted" href="index.php?page=Login">Back to login</a>
            </div>
        </form>
    </div>
</body>
</html>
