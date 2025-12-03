<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Newsstand</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../Assets/CSS/Styles.css">
</head>
<body>
    <header>
        <h1>Digital Newsstand</h1>
        <nav>
            <a href="index.php?page=Home">Home</a>
            <a href="index.php?page=article">Articles</a>
            <a href="index.php?page=Login">Login</a>
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

        // Run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSearchToggle);
        } else {
            // DOM is already ready
            initSearchToggle();
        }
    })();
    </script>

    <main>
