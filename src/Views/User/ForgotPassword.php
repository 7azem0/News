<?php
// /views/User/ForgotPassword.php
$action = $action ?? 'index.php?page=forgot-password';
$old = $old ?? [];
$errors = $errors ?? [];
$message = $message ?? null;

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>

<?php include __DIR__ . '/../Layout/Header.php'; ?>

<main class="container" style="max-width: 800px; padding: 2rem 1rem; background-color: #f9f9f9;">

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

    <div class="auth-container">
        <h2 class="serif-headline" style="font-size: 1.5rem; margin-bottom: 2rem;">Reset your password</h2>

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

        <form method="post" action="<?= e($action) ?>" autocomplete="off">
            <?php if (!empty($csrfToken)): ?>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <?php endif; ?>

            <div class="form-field">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" required autofocus value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com">
            </div>

            <button type="submit" class="btn-submit">Send Reset Link</button>
            
            <div style="margin-top: 1rem; font-family: var(--font-sans); font-size: 0.9rem;">
                <a href="index.php?page=Login">Back to Log In</a>
            </div>
        </form>
    </div>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
