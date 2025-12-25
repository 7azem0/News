<?php
// Admin sidebar partial
$active = $_GET['page'] ?? '';
?>
<style>
/* Off-canvas admin sidebar - modernized look */
.admin-sidebar {
    width: 280px;
    /* subtle paper background with slight tint */
    background: linear-gradient(180deg,#ffffff,#fbfdff);
    border: 1px solid rgba(15,23,42,0.06);
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 12px 40px rgba(2,6,23,0.06);

    /* off-canvas using transform for smooth animation */
    position: fixed;
    left: 24px; /* resting offset; hidden via transform */
    top: 72px; /* below admin header */
    z-index: 1200;
    transform: translateX(-120%);
    transition: transform 260ms cubic-bezier(0.2,0.8,0.2,1);
    height: calc(100% - 96px);
    overflow: auto;
}
.admin-sidebar.open { transform: translateX(0); }

/* subtle scrollbar styling */
.admin-sidebar::-webkit-scrollbar { width: 8px; }
.admin-sidebar::-webkit-scrollbar-thumb { background: rgba(15,23,42,0.08); border-radius: 8px; }

/* Overlay (so page still readable but dimmed) */
#adminSidebarOverlay { display: none; position: fixed; inset: 0; background: rgba(2,6,23,0.28); z-index: 1100; }
#adminSidebarOverlay.visible { display: block; }

/* Sidebar header / brand */
.admin-sidebar .sidebar-brand {
    display: block; padding: 0.6rem 0.75rem; margin-bottom: 0.8rem; border-radius: 8px; color: #0f1724; font-weight: 700; background: linear-gradient(90deg,#f7fafc,#0f1724); box-shadow: 0 6px 18px rgba(2,6,23,0.06);
}
.admin-sidebar .sidebar-brand small { display:block; font-weight:600; font-size:0.85rem; opacity:0.95; }

.admin-sidebar nav { display:flex; flex-direction:column; gap:0.5rem; margin-top: 0.5rem; }
.admin-sidebar nav a {
    display: flex; align-items: center; gap: 12px;
    padding: 0.9rem 0.85rem; font-size: 15px;
    color: #0f1724; text-decoration: none; border-radius: 8px;
    transition: background 120ms ease, transform 120ms ease;
}
.admin-sidebar nav a:hover { background: rgba(15,23,42,0.06); transform: translateX(2px); }
.admin-sidebar nav a.active { background: linear-gradient(90deg,#0f1724,#0b0b0b); color: #fff; box-shadow: none; }

.admin-sidebar .return-home { display:block; margin-top: 1.25rem; padding: 0.85rem 0.9rem; text-align:center; background: #0f1724; color:#fff; text-decoration:none; border-radius:10px; font-weight:600; }

@media (max-width: 800px) {
    .admin-sidebar { width: 85%; left: 7.5%; transform: translateX(-110%); }
    .admin-sidebar.open { transform: translateX(0); }
}
</style>
<aside class="admin-sidebar" id="adminSidebar" aria-label="Admin navigation">
    <div class="sidebar-brand">Dashboard <small>Admin</small></div>
    <nav>
        <a href="index.php?page=admin" class="<?= $active === 'admin' ? 'active' : '' ?>">Dashboard</a>
        <a href="index.php?page=admin_articles" class="<?= $active === 'admin_articles' ? 'active' : '' ?>">Articles</a>
        <a href="index.php?page=admin_users" class="<?= $active === 'admin_users' ? 'active' : '' ?>">Users</a>
        <a href="index.php?page=admin_plans" class="<?= $active === 'admin_plans' ? 'active' : '' ?>">Subscription Plans</a>
        <a href="index.php?page=admin_subscription_assign" class="<?= $active === 'admin_subscription_assign' ? 'active' : '' ?>">Assign Plan</a>
        <a href="index.php?page=admin_comments" class="<?= $active === 'admin_comments' ? 'active' : '' ?>">Comments / Moderation</a>
    </nav>

    <a href="index.php?page=Home" class="return-home">Return to Site</a>
</aside>

<div id="adminSidebarOverlay"></div>

<button id="adminSidebarToggle" class="admin-sidebar-toggle" aria-label="Open admin menu" title="Open admin menu" aria-expanded="false">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M4 6H20M4 12H20M4 18H20" stroke="#0f1724" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<style>
/* Toggle button styling (modern, unobtrusive) */
.admin-sidebar-toggle { position: fixed; top: 20px; left: 24px; z-index:1301; width:40px; height:40px; border-radius:8px; border:1px solid rgba(15,23,42,0.08); background:#fff; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 6px rgba(2,6,23,0.06); cursor:pointer; transition: transform 120ms ease, background 120ms ease; }
.admin-sidebar-toggle:hover { transform: translateY(-2px); background:#f8fafc; }
.admin-sidebar-toggle[aria-expanded="true"] { background: linear-gradient(90deg,#0f1724,#0b0b0b); }
.admin-sidebar-toggle[aria-expanded="true"] svg path { stroke: #ffffff; }
.admin-sidebar-toggle svg { display:block; }
</style>

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
            toggle.setAttribute('aria-expanded', 'false');
        }
        function openSidebar() {
            if (sidebar) sidebar.classList.add('open');
            if (overlay) overlay.classList.add('visible');
            toggle.setAttribute('aria-expanded', 'true');
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