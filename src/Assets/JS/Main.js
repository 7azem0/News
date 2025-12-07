(function () {
    // Wait for DOM to be ready
    function initSearchToggle() {
        var toggle = document.querySelector('.search-toggle');
        var overlay = document.getElementById('searchPopover');

        if (!toggle || !overlay) {
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


/*
//Start Code Halim Y Hazem Yleader
document.addEventListener("DOMContentLoaded", function () {
    const dropdown = document.querySelector(".user-dropdown");
    const btn = document.querySelector("#userMenuBtn");
    const menu = document.querySelector("#userMenu");

    if (!dropdown || !btn || !menu) return;

    btn.addEventListener("click", function (e) {
        e.stopPropagation();
        dropdown.classList.toggle("open");
    });

    menu.addEventListener("click", function (e) {
        e.stopPropagation();
    });

    document.addEventListener("click", function () {
        dropdown.classList.remove("open");
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") dropdown.classList.remove("open");
    });
});
*/