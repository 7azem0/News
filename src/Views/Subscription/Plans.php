<?php include(__DIR__ . '../../Layout/Header.php'); ?>
<?php
require_once __DIR__ . '/../../Models/User.php';
require_once __DIR__ . '/../../Models/Subscription.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=Login');
    exit;
}

$userModel = new User();
$subModel = new Subscription();

$subscription = $userModel->getSubscription($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
$allPlans = $subModel->getAllPlans();

// Check if subscription is valid
$isActive = $subscription && strtotime($subscription['expires_at']) > time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Plans - Digital Newsstand</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/Styles.css">
    <style>
        /* ... existing styles ... */
        .plans-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .plans-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .plans-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .current-subscription-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .current-subscription-banner h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.5rem;
        }

        .current-subscription-banner p {
            margin: 0;
            opacity: 0.9;
        }

        .subscription-plans {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .subscription-plan {
            background: white;
            border: 2px solid #e1e8ed;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .subscription-plan:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* NEW — visible blue outline on click */
        .subscription-plan:active {
            transform: translateY(-2px) scale(0.98);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.25);
        }  

        .subscription-plan.selected {
            box-shadow: 0 0 0 4px rgba(1, 44, 236, 0.76);
            animation: pulse 0.6s ease-out;
            border-color: #667eea;
        }

        .subscription-plan h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .price {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .price span {
            font-size: 1rem;
            color: #7f8c8d;
        }

        .features {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }

        .features li {
            padding: 0.5rem 0;
            color: #34495e;
            border-bottom: 1px solid #ecf0f1;
            white-space: pre-wrap; /* Preserve newlines from DB */
        }

        .features li:last-child {
            border-bottom: none;
        }

        .subscription-form {
            margin-top: 2rem;
        }

        .auto-renew-section {
            margin: 1.5rem 0;
            text-align: left;
        }

        .auto-renew-section label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: #34495e;
        }

        .subscribe-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .cancel-btn {
             background: #e74c3c;
             margin-top: 1rem;
        }
        .cancel-btn:hover {
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        .subscribe-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .subscribe-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .plans-container {
                padding: 1rem;
            }

            .subscription-plans {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
   

    <main>
        <div class="plans-container">
            <a href="index.php?page=Home" class="back-link">← Back to Home</a>

            <div class="plans-header">
                <h1>Choose Your Plan</h1>
                <p>Select the perfect subscription plan for your news reading needs</p>
            </div>

            <?php if ($isActive): ?>
                <div class="current-subscription-banner">
                    <h2>Current Plan: <?php echo htmlspecialchars($subscription['plan']); ?></h2>
                    <p>Expires on: <?= date('F j, Y', strtotime($subscription['expires_at'])) ?></p>
                </div>
            <?php endif; ?>

            <form class="subscription-form" id="subscriptionForm" method="POST" action="index.php?page=subscribe">
                <div class="subscription-plans">
                    <?php foreach ($allPlans as $plan): ?>
                        <div class="subscription-plan" onclick="document.getElementById('plan_<?= $plan['id'] ?>').click()">
                            <input type="radio" id="plan_<?= $plan['id'] ?>" name="plan_id" value="<?= $plan['id'] ?>" 
                                <?= ($isActive && ($subscription['plan_id'] ?? 0) == $plan['id']) ? 'checked' : '' ?> 
                                style="display: none;">
                            
                            <h3><?= htmlspecialchars($plan['name']) ?></h3>
                            <div class="price">$<?= number_format($plan['price'], 2) ?><span>/<?= (int)$plan['duration_days'] === 30 ? 'month' : 'year' ?></span></div>
                            
                            <ul class="features">
                                <?php 
                                    $feats = explode("\n", $plan['features']);
                                    foreach ($feats as $f) {
                                        if (trim($f)) echo "<li>" . htmlspecialchars(trim($f)) . "</li>";
                                    }
                                ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="auto-renew-section">
                    <label for="autoRenew">
                        <input type="checkbox" id="autoRenew" name="autoRenew" <?= ($isActive && ($subscription['auto_renew'] ?? 0)) ? 'checked' : 'checked' ?>>
                        Auto-renew subscription
                    </label>
                </div>

                <button type="submit" class="subscribe-btn" id="subscribeBtn">
                    <?php echo $isActive ? 'Switch Plan / Update Renewal' : 'Subscribe Now'; ?>
                </button>
            </form>
            
            <?php if ($isActive): ?>
                <form action="index.php?page=cancel_subscription" method="POST" style="margin-top: 20px;" onsubmit="return confirm('Are you sure you want to cancel? You will lose access immediately.');">
                    <button type="submit" class="subscribe-btn cancel-btn">Cancel Subscription</button>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Search toggle functionality
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

                    var isVisible = overlay.style.display === 'block' ||
                                   window.getComputedStyle(overlay).display === 'block';

                    if (isVisible) {
                        overlay.style.display = 'none';
                    } else {
                        overlay.style.display = 'block';
                        setTimeout(function() {
                            if (input) {
                                input.focus();
                            }
                        }, 10);
                    }
                });

                document.addEventListener('click', function(event) {
                    if (overlay && overlay.style.display === 'block') {
                        if (!overlay.contains(event.target) && !toggle.contains(event.target)) {
                            overlay.style.display = 'none';
                        }
                    }
                });
            }


            // Handle form submission
            function initSubscriptionForm() {
                var subscriptionForm = document.getElementById('subscriptionForm');
                var subscribeBtn = document.getElementById('subscribeBtn');

                if (subscriptionForm && subscribeBtn) {
                    subscriptionForm.addEventListener('submit', function(event) {
                        event.preventDefault();

                        var formData = new FormData(subscriptionForm);
                        subscribeBtn.disabled = true;
                        subscribeBtn.textContent = 'Processing...';

                        fetch('index.php?page=subscribe', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert('Error: ' + data.error);
                            }
                            window.location.reload(); // Always reload on success
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
                var profileMenu = document.getElementById('profileMenu');
                var profileToggle = document.getElementById('profileToggle');
                if (profileMenu && profileMenu.style.display === 'block') {
                    if (!profileMenu.contains(event.target) && !profileToggle.contains(event.target)) {
                        profileMenu.style.display = 'none';
                    }
                }

                var searchOverlay = document.getElementById('searchPopover');
                var searchToggle = document.querySelector('.search-toggle');
                if (searchOverlay && searchOverlay.style.display === 'block') {
                    if (!searchOverlay.contains(event.target) && !searchToggle.contains(event.target)) {
                        searchOverlay.style.display = 'none';
                    }
                }
            });

            // Handle plan selection animation
            function initPlanSelection() {
                var planInputs = document.querySelectorAll('input[name="plan_id"]');
                planInputs.forEach(function(input) {
                    input.addEventListener('change', function() {
                        // Remove selected class from all plans
                        document.querySelectorAll('.subscription-plan').forEach(function(plan) {
                            plan.classList.remove('selected');
                        });
                        // Add selected class to the checked plan's parent
                        if (input.checked) {
                            input.closest('.subscription-plan').classList.add('selected');
                        }
                    });
                });
                // Set initial selected state
                var checkedInput = document.querySelector('input[name="plan_id"]:checked');
                if (checkedInput) {
                    checkedInput.closest('.subscription-plan').classList.add('selected');
                }
            }

            // Run when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initSearchToggle();
                // initProfileToggle();
                    initSubscriptionForm();
                    initPlanSelection();
                });
            } else {
                initSearchToggle();
                //initProfileToggle();
                initSubscriptionForm();
                initPlanSelection();
            }
        })();
    </script>
</body>
</html>
