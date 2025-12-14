<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!class_exists('User')) {
    require_once __DIR__ . '/../../Models/User.php';
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$subscription = null;

if ($isLoggedIn) {
    $userModel = new User();
    $subscription = $userModel->getSubscription($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Newsstand</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@400;600;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    
    <!-- Styles.css backup for legacy (news.php) -->
    <link rel="stylesheet" href="../../Assets/CSS/Styles.css">

    <!-- New Theme CSS (Overrides legacy) -->
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <link rel="stylesheet" href="../../Assets/CSS/games.css">
    <link rel="stylesheet" href="../../Assets/CSS/account.css">
</head>
<body class="<?= htmlspecialchars($_GET['page'] ?? 'home', ENT_QUOTES, 'UTF-8') ?>">

    <header class="main-header">
        <div class="container">
            <!-- Branding -->
            <div class="branding">
                <h1>
                    <a href="index.php?page=Home" style="color: black; text-decoration: none;">Digital Newsstand</a>
                </h1>
                <div style="text-align: center; font-family: var(--font-sans); font-size: 0.8rem; letter-spacing: 1px; margin-top: 5px;">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>

            <!-- Nav -->
            <nav class="nav-links">
                <a href="index.php?page=Home">Home</a>
                <a href="index.php?page=article">Articles</a>
                <a href="index.php?page=news">World News</a>
                <a href="index.php?page=games">Games Arcade</a>
                
                <?php if ($isLoggedIn): ?>
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <a href="#" id="profileToggle">Account (<?= htmlspecialchars($username) ?>)</a>
                        <div class="profile-menu" id="profileMenu">
                             <div class="sub-label">
                                SUBSCRIPTION: <span style="font-weight: 800;"><?= htmlspecialchars($subscription['plan'] ?? 'NONE') ?></span>
                            </div>
                            <div class="divider" style="margin: 0.5rem 0; border-color: #eee;"></div>
                            <a href="index.php?page=plans" class="menu-link">Manage/Subscribe</a>
                            <a href="?page=Account" class="menu-link">Settings</a>
                            <a href="?page=logout" class="menu-link text-danger">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?page=Login">Log In</a>
                <?php endif; ?>

                <!-- Search -->
                <a href="#" class="search-toggle">Search</a>
            </nav>
            
            <!-- Search Overlay (Hidden) -->
            <div id="searchPopover" style="display:none; position: absolute; top: 100%; right: 0; background: white; padding: 10px; border: 1px solid #ccc; z-index: 999;">
                 <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="search">
                    <input type="text" name="q" placeholder="Search..." autocomplete="off" style="padding: 5px;">
                    <button type="submit">Go</button>
                </form>
            </div>
        </div>
    </header>

    <script>
    // Search toggle functionality - inline to ensure it loads
    (function () {
        function initSearchToggle() {
            var toggle = document.querySelector('.search-toggle');
            var overlay = document.getElementById('searchPopover');

            if (!toggle || !overlay) {
                console.log('Search elements not found', {toggle: !!toggle, overlay: !!overlay});
                return;
            }

            var input = overlay.querySelector('input[name="q"]');

            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                // Check if overlay is currently visible
                var isVisible = overlay.style.display === 'block' ||
                               window.getComputedStyle(overlay).display === 'block';

                if (isVisible) {
                    overlay.style.display = 'none';
                } else {
                    overlay.style.display = 'block';
                    // Focus input after a brief delay to ensure it's visible
                    setTimeout(function() {
                        if (input) {
                            input.focus();
                        }
                    }, 10);
                }
            });

            // Close search popover when clicking outside
            document.addEventListener('click', function(event) {
                if (overlay && overlay.style.display === 'block') {
                    if (!overlay.contains(event.target) && !toggle.contains(event.target)) {
                        overlay.style.display = 'none';
                    }
                }
            });
        }

        // Profile dropdown toggle functionality
        function initProfileToggle() {
            var profileToggle = document.getElementById('profileToggle');
            var profileMenu = document.getElementById('profileMenu');

            if (profileToggle && profileMenu) {
                profileToggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var isVisible = profileMenu.style.display === 'block' ||
                                   window.getComputedStyle(profileMenu).display === 'block';

                    if (isVisible) {
                        profileMenu.style.display = 'none';
                    } else {
                        profileMenu.style.display = 'block';
                    }
                });
            }
        }

        // Subscription button toggle functionality
        function initSubscriptionToggle() {
            var subscriptionBtn = document.getElementById('subscriptionBtn');
            var manageSubscriptionBtn = document.getElementById('manageSubscriptionBtn');
            var subscriptionOptions = document.getElementById('subscriptionOptions');
            var subscriptionForm = document.getElementById('subscriptionForm');
            var subscribeBtn = document.getElementById('subscribeBtn');

            // Handle new subscription button (for users without subscription)
            if (subscriptionBtn && subscriptionOptions) {
                subscriptionBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var isVisible = subscriptionOptions.style.display === 'block' ||
                                   window.getComputedStyle(subscriptionOptions).display === 'block';

                    if (isVisible) {
                        subscriptionOptions.style.display = 'none';
                    } else {
                        subscriptionOptions.style.display = 'block';
                    }
                });
            }

            // Handle manage subscription button (for users with existing subscription)
            if (manageSubscriptionBtn && subscriptionOptions) {
                manageSubscriptionBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    var isVisible = subscriptionOptions.style.display === 'block' ||
                                   window.getComputedStyle(subscriptionOptions).display === 'block';

                    if (isVisible) {
                        subscriptionOptions.style.display = 'none';
                    } else {
                        subscriptionOptions.style.display = 'block';
                    }
                });
            }

            // Handle form submission
            if (subscriptionForm && subscribeBtn) {
                subscriptionForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    var formData = new FormData(subscriptionForm);
                    subscribeBtn.disabled = true;
                    subscribeBtn.textContent = 'Processing...';

                    fetch('?page=subscribe', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            // Reload page to update subscription status
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        alert('Error processing subscription. Please try again.');
                        console.error('Subscription error:', error);
                    })
                    .finally(() => {
                        subscribeBtn.disabled = false;
                        subscribeBtn.textContent = subscribeBtn.textContent.includes('Update') ? 'Update Subscription' : 'Subscribe Now';
                    });
                });
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            // Close profile menu
            var profileMenu = document.getElementById('profileMenu');
            var profileToggle = document.getElementById('profileToggle');
            if (profileMenu && profileMenu.style.display === 'block') {
                if (!profileMenu.contains(event.target) && !profileToggle.contains(event.target)) {
                    profileMenu.style.display = 'none';
                }
            }

            // Close search popover
            var searchOverlay = document.getElementById('searchPopover');
            var searchToggle = document.querySelector('.search-toggle');
            if (searchOverlay && searchOverlay.style.display === 'block') {
                if (!searchOverlay.contains(event.target) && !searchToggle.contains(event.target)) {
                    searchOverlay.style.display = 'none';
                }
            }
        });

        // Run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                initSearchToggle();
                initProfileToggle();
            });
        } else {
            // DOM is already ready
            initSearchToggle();
            initProfileToggle();
        }
    })();
    </script>

     
    <main>
