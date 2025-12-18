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

        <form method="post" action="<?= e($action) ?>" autocomplete="off" id="regForm">
            <?php if (!empty($csrfToken)): ?>
                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
            <?php endif; ?>

            <div class="form-field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" required value="<?= e($old['username'] ?? '') ?>">
                <small id="username-msg" style="display:block; margin-top:0.25rem; font-size:0.8rem;"></small>
            </div>

            <div class="form-field">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" required value="<?= e($old['email'] ?? '') ?>">
                <small id="email-msg" style="display:block; margin-top:0.25rem; font-size:0.8rem;"></small>
            </div>

            <div class="form-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                <div id="password-strength" style="height:4px; width:100%; background:#eee; margin-top:5px; border-radius:2px;">
                    <div id="strength-bar" style="height:100%; width:0%; border-radius:2px; transition:0.3s;"></div>
                </div>
                <small id="password-msg" style="display:block; margin-top:0.25rem; font-size:0.8rem; color:#666;">
                    Min 8 chars, mixed case, number & symbol.
                </small>
            </div>

            <div class="form-field">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">Register</button>
        </form>
    </div>

    <div style="text-align: center; font-family: var(--font-sans); font-size: 0.9rem;">
        Already have an account? <a href="index.php?page=Login" style="font-weight: bold;">Log in</a>
    </div>

    <script>
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const usernameMsg = document.getElementById('username-msg');
        const emailMsg = document.getElementById('email-msg');
        const strengthBar = document.getElementById('strength-bar');
        const submitBtn = document.getElementById('submitBtn');

        let isUsernameValid = true;
        let isEmailValid = true;

        async function checkAvailability(type, value, msgElement) {
            if (!value) {
                msgElement.textContent = '';
                return true;
            }
            try {
                const resp = await fetch(`index.php?page=check_${type}&${type}=${encodeURIComponent(value)}`);
                const data = await resp.json();
                if (data.available) {
                    msgElement.textContent = '✓ Available';
                    msgElement.style.color = 'green';
                    return true;
                } else {
                    msgElement.textContent = '✗ Already taken';
                    msgElement.style.color = 'red';
                    return false;
                }
            } catch (e) {
                return true;
            }
        }

        usernameInput.addEventListener('blur', async () => {
            isUsernameValid = await checkAvailability('username', usernameInput.value, usernameMsg);
        });

        emailInput.addEventListener('blur', async () => {
            isEmailValid = await checkAvailability('email', emailInput.value, emailMsg);
        });

        passwordInput.addEventListener('input', () => {
            const val = passwordInput.value;
            let strength = 0;
            if (val.length >= 8) strength += 25;
            if (/[A-Z]/.test(val)) strength += 25;
            if (/[0-9]/.test(val)) strength += 25;
            if (/[@$!%*?&]/.test(val)) strength += 25;

            strengthBar.style.width = strength + '%';
            if (strength <= 25) strengthBar.style.background = 'red';
            else if (strength <= 50) strengthBar.style.background = 'orange';
            else if (strength <= 75) strengthBar.style.background = 'yellow';
            else strengthBar.style.background = 'green';
        });

        document.getElementById('regForm').addEventListener('submit', (e) => {
            if (!isUsernameValid || !isEmailValid) {
                e.preventDefault();
                alert('Please fix the errors before submitting.');
            }
        });
    </script>

</body>
</html>
