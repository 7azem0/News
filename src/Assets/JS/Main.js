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
