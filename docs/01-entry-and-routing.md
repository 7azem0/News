# Entry Point & Routing

## File: `src/index.php`

### Role of this file
`src/index.php` is the **front controller** (single entry point). It initializes session/config and forwards the request to the router.

### Who calls it
- The web server / PHP runtime when the user visits the site.

### Dependencies
- `src/Config/DataBase_Connection.php` (defines `Database`)
- `src/Core/Router.php` (defines `Router`)
- `src/Core/Helpers.php` (defines helper functions like `redirect`, `sanitize`, CSRF helpers)
- `src/Controllers/Article_C.php` (preloaded here; other controllers are loaded lazily by the router)

### Line-by-line (block-by-block)

#### Lines 1–4: PHP start + session initialization
- **Line 1**: `<?php` starts PHP mode.
- **Line 2**: checks whether a session is already active.
- **Line 3**: starts a session when none exists.
- **Line 4**: closes the `if` block.

#### Lines 6–9: load core dependencies
- **Line 6**: loads the database connection class.
- **Line 7**: loads the router definition.
- **Line 8**: loads helper functions.
- **Line 9**: preloads `ArticleController` (note: other controllers are loaded inside `Router::route`).

#### Lines 11–13: language selection persistence
- **Line 11**: checks if the request contains `?lang=...`.
- **Line 12**: stores the selected language in the session (`$_SESSION['lang']`) so it persists across requests.
- **Line 13**: closes the `if` block.

#### Lines 15–19: resolve page + dispatch
- **Line 15**: determines `$selectedLang` from session, defaults to `en`.
- **Line 17**: determines `$page` from query string, defaults to empty string.
- **Line 19**: dispatches to `Router::route($page, $selectedLang)`.

### Relationships (cross-references)
- `index.php` always ends by calling `Router::route`.
- `Router::route` uses `$_SESSION['user_id']` to decide whether to redirect to login.

---

## File: `src/Core/Router.php`

### Role of this file
Defines `Router::route`, the central dispatcher. It:
- performs **auth gating** (public pages vs protected)
- maps `$_GET['page']` values to controller methods or views

### Who calls it
- Directly called by `src/index.php`.

### Dependencies
- Loads controllers on demand with `require_once`.
- Includes views using `include`.

### Line-by-line (block-by-block)

#### Lines 1–8: class definition + constants
- **Line 1**: starts PHP.
- **Line 2**: file marker.
- **Line 4**: declares the `Router` class.
- **Lines 6–7**: define filesystem paths for controllers/views.

#### Lines 9–25: `route()` signature + session + auth gate
- **Line 9**: declares `public static function route(string $page, string $selectedLang = 'en'): void`.
- **Lines 11–14**: defensively starts session.
- **Line 17**: defines `$publicPages` (routes accessible without login).
- **Lines 19–24**: if the requested page is not public and `$_SESSION['user_id']` is missing, redirect to `page=login`.
  - **Line 22**: includes `redirect` context using `redirect` query parameter so the app can return the user after login (if implemented).

#### Lines 26–282: routing switch
- **Line 26**: `switch (strtolower($page))` normalizes routing case.
- Each `case`:
  - loads a controller file (if needed)
  - creates a controller instance
  - calls a controller method
  - OR includes a view file

Key route groups (see `04-route-table.md` for a full mapping):
- **Home routes**: `""`, `"index"`, `"home"` → includes `Views/Home.php`
- **Articles**:
  - `"article"` → `ArticleController::index`
  - `"article_download_pdf"` → `ArticleController::downloadPdf`
  - `"news"` → `ArticleController::news(country, category)`
- **Discovery**:
  - `"search"` → `SearchController::index`
  - `"for_you"` → `ForYouController::index`
  - `"morning"`, `"live"`, `"ajax_live"` → `BriefingController::*`
- **User/auth**:
  - `"login"`, `"register"`, `"forgot-password"`, `"logout"`, `"account"`
  - `"check_username"`, `"check_email"` are AJAX endpoints returning JSON
- **Admin**:
  - `"admin"` → Admin dashboard
  - `"admin_users"` etc. → user management
  - `"admin_articles"` etc. → article management
  - `"admin_plans"` etc. → plan/subscription management
  - `"admin_comments"` etc. → comment moderation
- **Games**:
  - `"games"` route multiplexes sub-actions via `$_GET['action']`.
- **Interactions**:
  - `"comment_store"`, `"article_like"`, `"article_save"`

#### Lines 277–281: 404 handling
- Sets HTTP status 404 and prints a simple message.

### Relationships (cross-references)
- **Auth gate**: the router checks `$_SESSION['user_id']`, which is set by `User::login()` (see `Models/User.php`) and `UserController::login()`.
- **Admin**: controllers further enforce admin permissions with `$_SESSION['is_admin']`.

### Notable design implications
- Routing is centralized and explicit (easy to read, but can grow large).
- Controllers are instantiated per-request.
- Route parameters are often taken directly from `$_GET`/`$_POST`.
