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
    <title>Register | Digital Newsstand</title>
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
        <h2 class="serif-headline" style="font-size: 1.5rem; margin-bottom: 2rem;">Create your account</h2>

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
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required value="<?= e($old['username'] ?? '') ?>">
            </div>

            <div class="form-field">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <div class="form-field">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>
    </div>

    <div style="text-align: center; font-family: var(--font-sans); font-size: 0.9rem;">
        Already have an account? <a href="index.php?page=Login" style="font-weight: bold;">Log in</a>
    </div>

</body>
</html>
