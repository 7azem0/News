<?php
// Minimal admin head to avoid site header and keep admin pages independent
$pageTitle = $pageTitle ?? 'Admin - Digital Newsstand';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="../../Assets/CSS/theme.css">
    <style>
        /* Page-level admin layout helpers (restore original spacing) */
        .admin-container { max-width: 1100px; margin: 2rem auto; padding: 0 1rem; }
        .admin-page { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .admin-header { margin-bottom: 1rem; border-bottom: 1px solid #eee; padding-bottom: 1rem; position: relative; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; gap: 1rem; }

        /* Buttons similar to previous admin styling */
        .btn { padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; display: inline-block; cursor: pointer; border: none; background: transparent; color: inherit; }
        .btn-primary { background: #111827; color: #fff; padding: 0.6rem 1rem; border-radius: 6px; }
        .btn-danger { background: #d32f2f; color: white; }

        .admin-card { background: white; border: 1px solid #eee; border-radius: 8px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        /* Position the hamburger aligned with the admin container's left edge */
        #adminSidebarToggle { position: fixed; top: 20px; left: calc(50% - 550px); z-index:1300; border:none; background:transparent; padding:8px; cursor:pointer; display:flex; align-items:center; gap:6px; }
        @media (max-width: 1200px) {
            #adminSidebarToggle { left: 24px; }
        }

    </style>
</head>
<body>

<button id="adminSidebarToggle" aria-label="Open admin menu" style="position:fixed; top:18px; left:16px; z-index:1300; border:none; background:transparent; padding:8px; cursor:pointer; display:flex; align-items:center; gap:6px;">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4 6H20M4 12H20M4 18H20" stroke="#111827" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<script>
(function () {
    function initAdminSidebar() {
        var toggle = document.getElementById('adminSidebarToggle');
        var sidebar = document.getElementById('adminSidebar');
        var overlay = document.getElementById('adminSidebarOverlay');

        if (!toggle) { return; }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('open');
            if (overlay) overlay.classList.remove('visible');
        }
        function openSidebar() {
            if (sidebar) sidebar.classList.add('open');
            if (overlay) overlay.classList.add('visible');
        }

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (sidebar && sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        // clicking overlay closes
        if (overlay) {
            overlay.addEventListener('click', function () { closeSidebar(); });
        }

        // close on Escape
        document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') closeSidebar();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminSidebar);
    } else {
        initAdminSidebar();
    }
})();
</script>