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
    <title>Digital Newsstand</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/Styles.css">
    <link rel="stylesheet" href="../../Assets/CSS/pages.css">
    <link rel="stylesheet" href="../../Assets/CSS/account.css">

</head>
<body>
    <header>
        <h1>
    <a href="index.php?page=Home" style="text-decoration: none; color: inherit;">
        Digital Newsstand
    </a>
</h1>
        <nav>
            <a href="index.php?page=Home">Home</a>
            <a href="index.php?page=article">Articles</a>
            <?php if ($isLoggedIn): ?>
                            <div class="user-dropdown">
                    <button class="user-btn" id="userMenuBtn">
                        👤 <?php echo htmlspecialchars($username); ?>
                        <span class="arrow">▼</span>
                    </button>

                    <div class="user-menu" id="userMenu">
                        <a href="?page=Subscription" class="user-item">Manage Subscription</a>
                        <a href="?page=Account" class="user-item">Account Settings</a>
                        <a href="?page=logout" class="user-item logout">Logout</a>
                    </div>
                </div>


            <?php else: ?>
                <a href="index.php?page=Login">Login</a>
            <?php endif; ?>
            <div style="position: relative; display: inline-block;">
                <a
                    href="#"
                    class="search-toggle"
                    aria-label="Toggle article search"
                >
                    🔍
                </a>
                <div
                    class="search-popover"
                    id="searchPopover"
                    style="display:none;"
                >
                    <form
                        method="GET"
                        action="index.php"
                        class="search-popover-form"
                    >
                        <input type="hidden" name="page" value="search">
                        <input
                            type="text"
                            name="q"
                            placeholder="Search articles..."
                            autocomplete="off"
                        >
                    </form>
                </div>
            </div>
        </nav>
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
            var profileToggle = document.getElementById('userMenuBtn');
            var profileMenu = document.getElementById('userMenu');

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
            var profileMenu = document.getElementById('userMenu');
            var profileToggle = document.getElementById('userMenuBtn');
            if (profileMenu && profileMenu.style.display === 'block') {
                if (!profileMenu.contains(event.target) && !profileToggle.contains(event.target)) {
                    profileMenu.style.display = 'none';
                }
            }

            // Close subscription options
            var subscriptionOptions = document.getElementById('subscriptionOptions');
            if (subscriptionOptions && subscriptionOptions.style.display === 'block') {
                if (!subscriptionOptions.contains(event.target)) {
                    subscriptionOptions.style.display = 'none';
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
                initSubscriptionToggle();
            });
        } else {
            // DOM is already ready
            initSearchToggle();
            initProfileToggle();
            initSubscriptionToggle();
        }
    })();
    </script>

     
    <main>

    
                 <script src="../../Assets/JS/Main.js">
                 </script>
                
</body>
</html>