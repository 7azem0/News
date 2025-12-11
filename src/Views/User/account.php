<?php require_once __DIR__ . '/../Layout/Header.php'; ?>

<div class="account-page-container">
    <div class="account-card">
        <h2>Account Settings</h2>
        <p class="subtitle">Manage your profile.</p>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="index.php?page=Account" method="POST" class="account-form">
            
            <h3>Profile Information</h3>
            
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($currentUser['username'] ?? $_SESSION['username']); ?>" 
                           required readonly>
                    <span class="icon-btn edit-btn" onclick="enableEdit('username')">✏️</span>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($currentUser['email'] ?? $_SESSION['email']); ?>" 
                           required readonly>
                    <span class="icon-btn edit-btn" onclick="enableEdit('email')">✏️</span>
                </div>
            </div>

            <hr class="divider">

            <h3>Change Password <span class="optional-text">(Optional)</span></h3>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <div class="input-wrapper">
                    <input type="password" id="new_password" name="new_password" placeholder="New password">
                    <span class="icon-btn toggle-password" onclick="togglePassword('new_password', this)">👁️</span>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <div class="input-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password">
                    <span class="icon-btn toggle-password" onclick="togglePassword('confirm_password', this)">👁️</span>
                </div>
            </div>

            <hr class="divider">

            <div class="current-pass-box">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="current_password" class="warning-label">🔒 Current Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="current_password" name="current_password" required placeholder="Required to save">
                        <span class="icon-btn toggle-password" onclick="togglePassword('current_password', this)">👁️</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>
</div>

<script>
    // Enable Editing
    function enableEdit(inputId) {
        var input = document.getElementById(inputId);
        input.removeAttribute('readonly'); 
        input.focus(); 
        input.style.backgroundColor = "#fff"; 
        input.style.cursor = "text";
    }

    // Toggle Password View
    function togglePassword(inputId, icon) {
        var input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text"; 
            icon.style.opacity = "1"; 
        } else {
            input.type = "password"; 
            icon.style.opacity = "0.5"; 
        }
    }
</script>

<?php require_once __DIR__ . '/../Layout/Footer.php'; ?>