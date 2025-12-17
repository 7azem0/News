<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/ArticleInteraction.php';

$userModel = new User();
$user = $userModel->getUserById($_SESSION['user_id']);

$interactionModel = new ArticleInteraction();
$likedArticles = $interactionModel->getLikedArticles($_SESSION['user_id']);
$savedArticles = $interactionModel->getSavedArticles($_SESSION['user_id']);

$activeTab = $_GET['tab'] ?? 'liked';

include __DIR__ . '/../Layout/Header.php';
?>

<main class="container" style="max-width: 1200px; padding: 2rem 1rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h1 style="font-family: var(--font-serif); font-size: 2.5rem; margin-bottom: 0.5rem;">
            My Profile
        </h1>
        <p style="color: #666; font-size: 1.1rem;">
            Welcome back, <strong><?= htmlspecialchars($user['username']) ?></strong>!
        </p>
    </div>

    <!-- Tabs -->
    <div style="border-bottom: 2px solid #eee; margin-bottom: 2rem;">
        <div style="display: flex; gap: 2rem; justify-content: center;">
            <a href="?page=profile&tab=liked" 
               style="padding: 1rem 2rem; text-decoration: none; font-weight: bold; border-bottom: 3px solid <?= $activeTab === 'liked' ? '#ff4757' : 'transparent' ?>; color: <?= $activeTab === 'liked' ? '#ff4757' : '#666' ?>; transition: all 0.3s;">
                ❤️ Liked Articles (<?= count($likedArticles) ?>)
            </a>
            <a href="?page=profile&tab=saved" 
               style="padding: 1rem 2rem; text-decoration: none; font-weight: bold; border-bottom: 3px solid <?= $activeTab === 'saved' ? '#1e90ff' : 'transparent' ?>; color: <?= $activeTab === 'saved' ? '#1e90ff' : '#666' ?>; transition: all 0.3s;">
                📚 Saved Articles (<?= count($savedArticles) ?>)
            </a>
            <a href="?page=profile&tab=settings" 
               style="padding: 1rem 2rem; text-decoration: none; font-weight: bold; border-bottom: 3px solid <?= $activeTab === 'settings' ? '#667eea' : 'transparent' ?>; color: <?= $activeTab === 'settings' ? '#667eea' : '#666' ?>; transition: all 0.3s;">
                ⚙️ Settings
            </a>
        </div>
    </div>

    <!-- Liked Articles Tab -->
    <?php if ($activeTab === 'liked'): ?>
        <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 3rem;">
            <?php if (!empty($likedArticles)): ?>
                <?php foreach ($likedArticles as $article): ?>
                    <a href="?page=article&id=<?= $article['id'] ?>" style="text-decoration: none; color: inherit;">
                        <div class="article-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer; height: 100%; display: flex; flex-direction: column;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <?php if (!empty($article['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Article" style="width: 100%; height: 200px; object-fit: cover; flex-shrink: 0;">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); flex-shrink: 0;"></div>
                            <?php endif; ?>
                            <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.5rem; color: #333; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; height: 3.5rem;">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h3>
                                <p style="color: #888; font-size: 0.85rem; margin-bottom: 1rem;">
                                    Liked on <?= date('F j, Y', strtotime($article['liked_at'])) ?>
                                </p>
                                <p style="color: #666; line-height: 1.6; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; flex: 1;">
                                    <?= htmlspecialchars(substr($article['content'], 0, 150)) ?>...
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
                    <svg width="80" height="80" viewBox="0 0 24 24" style="margin-bottom: 1rem; opacity: 0.3;">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" fill="#ddd"/>
                    </svg>
                    <p style="color: #999; font-size: 1.2rem;">No liked articles yet.</p>
                    <a href="?page=article" style="display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                        Browse Articles
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Saved Articles Tab -->
    <?php if ($activeTab === 'saved'): ?>
        <div class="articles-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 3rem;">
            <?php if (!empty($savedArticles)): ?>
                <?php foreach ($savedArticles as $article): ?>
                    <a href="?page=article&id=<?= $article['id'] ?>" style="text-decoration: none; color: inherit;">
                        <div class="article-card" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s; cursor: pointer; height: 100%; display: flex; flex-direction: column;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <?php if (!empty($article['thumbnail'])): ?>
                                <img src="<?= htmlspecialchars($article['thumbnail']) ?>" alt="Article" style="width: 100%; height: 200px; object-fit: cover; flex-shrink: 0;">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); flex-shrink: 0;"></div>
                            <?php endif; ?>
                            <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                                <h3 style="font-family: var(--font-serif); font-size: 1.25rem; margin-bottom: 0.5rem; color: #333; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; line-height: 1.4; height: 3.5rem;">
                                    <?= htmlspecialchars($article['title']) ?>
                                </h3>
                                <p style="color: #888; font-size: 0.85rem; margin-bottom: 1rem;">
                                    Saved on <?= date('F j, Y', strtotime($article['saved_at'])) ?>
                                </p>
                                <p style="color: #666; line-height: 1.6; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; flex: 1;">
                                    <?= htmlspecialchars(substr($article['content'], 0, 150)) ?>...
                                </p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 4rem 2rem;">
                    <svg width="80" height="80" viewBox="0 0 24 24" style="margin-bottom: 1rem; opacity: 0.3;">
                        <path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z" fill="#ddd"/>
                    </svg>
                    <p style="color: #999; font-size: 1.2rem;">No saved articles yet.</p>
                    <a href="?page=article" style="display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                        Browse Articles
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Settings Tab -->
    <?php if ($activeTab === 'settings'): ?>
        <div class="settings-section" style="max-width: 600px; margin: 0 auto;">
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h2 style="font-family: var(--font-serif); margin-bottom: 0.5rem;">Account Settings</h2>
                <p style="color: #666; margin-bottom: 2rem;">Manage your profile information and password.</p>

                <?php
                // Handle form submission
                $error = '';
                $success = '';
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'])) {
                    $currentPassword = $_POST['current_password'];
                    $newUsername = trim($_POST['username'] ?? '');
                    $newEmail = trim($_POST['email'] ?? '');
                    $newPassword = $_POST['new_password'] ?? '';
                    $confirmPassword = $_POST['confirm_password'] ?? '';
                    
                    // Verify current password
                    $currentUser = $userModel->getUserById($_SESSION['user_id']);
                    
                    if (password_verify($currentPassword, $currentUser['password'])) {
                        $updateSuccess = true;
                        
                        // Update username and email
                        if ($newUsername !== $currentUser['username'] || $newEmail !== $currentUser['email']) {
                            if ($userModel->updateProfile($_SESSION['user_id'], $newUsername, $newEmail)) {
                                $_SESSION['username'] = $newUsername;
                            } else {
                                $updateSuccess = false;
                                $error = 'Failed to update profile information.';
                            }
                        }
                        
                        // Update password if provided
                        if (!empty($newPassword)) {
                            if ($newPassword === $confirmPassword) {
                                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                                if (!$userModel->updatePassword($_SESSION['user_id'], $hashedPassword)) {
                                    $updateSuccess = false;
                                    $error = 'Failed to update password.';
                                }
                            } else {
                                $updateSuccess = false;
                                $error = 'New passwords do not match.';
                            }
                        }
                        
                        if ($updateSuccess && empty($error)) {
                            $success = 'Settings updated successfully!';
                            $user = $userModel->getUserById($_SESSION['user_id']); // Refresh user data
                        }
                    } else {
                        $error = 'Current password is incorrect.';
                    }
                }
                ?>

                <?php if (!empty($error)): ?>
                    <div style="padding: 1rem; margin-bottom: 1rem; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div style="padding: 1rem; margin-bottom: 1rem; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form action="?page=profile&tab=settings" method="POST">
                    <h3 style="margin-bottom: 1rem; color: #333;">Profile Information</h3>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label for="username" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Username</label>
                        <input type="text" id="username" name="username" 
                               value="<?= htmlspecialchars($user['username']) ?>" 
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($user['email']) ?>" 
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eee;">

                    <h3 style="margin-bottom: 1rem; color: #333;">Change Password <span style="color: #999; font-weight: normal; font-size: 0.9rem;">(Optional)</span></h3>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label for="new_password" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">New Password</label>
                        <input type="password" id="new_password" name="new_password" 
                               placeholder="Leave blank to keep current password"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #333;">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               placeholder="Confirm new password"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
                    </div>

                    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #eee;">

                    <div style="background: #fff3cd; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                        <label for="current_password" style="display: block; margin-bottom: 0.5rem; font-weight: bold; color: #856404;">🔒 Current Password (Required)</label>
                        <input type="password" id="current_password" name="current_password" 
                               required
                               placeholder="Enter your current password to save changes"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #ffc107; border-radius: 4px; font-size: 1rem;">
                    </div>

                    <button type="submit" 
                            style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: transform 0.2s;"
                            onmouseover="this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../Layout/Footer.php'; ?>
