# Controller: `AdminController`

## Source file
- `src/Controllers/Admin_C.php`

## Role of this controller
`AdminController` provides the **entry page** for the admin dashboard.

It is responsible for:
- ensuring an HTTP session exists
- ensuring the current user is logged in and has admin privileges
- including the admin dashboard view

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=admin` → `AdminController::index()`

## Dependencies
### Required files
- `src/Models/User.php` (required at file load time)
- `src/Core/Helpers.php` (for `redirect()`)

### Session dependencies
- `$_SESSION['user_id']`: indicates logged-in user
- `$_SESSION['is_admin']`: indicates admin privileges (expected to be `1`)

### View dependency
- `src/Views/Admin/Dashboard.php`

## Line-by-line (block-by-block)

### Lines 1–4: bootstrap the controller file
- **Line 1**: enters PHP mode.
- **Line 2**: loads `User` model.
  - Even though this controller does not directly use `User` in `index()`, the file preloads it.
- **Line 3**: loads helper functions, especially `redirect()`.
- **Line 4**: blank separator.

### Lines 5–22: class + `index()` action
- **Line 5**: defines `class AdminController`.

#### Lines 7–21: `index()` method
- **Line 7**: declares `public function index()`.
- **Lines 8–10**: ensures the session is started.
  - This is required before reading `$_SESSION` values.
- **Lines 12–17**: authorization check.
  - **Line 13** checks both:
    - user is logged in (`$_SESSION['user_id']` exists)
    - user is admin (`$_SESSION['is_admin']` exists and equals `1`)
  - **Lines 15–16** redirect non-admin users to home and stop execution.
    - Cross-reference: `redirect('?page=Home')` ultimately sends the browser to `index.php?page=Home`, which is handled by `Router::route`.
- **Lines 19–20**: includes the dashboard view.

## Relationships (cross-references)
- Admin status (`$_SESSION['is_admin']`) is initially set during login inside `User::login()` (see `docs/models/User.php.md` later).
- The Router already blocks non-logged-in users for protected routes, but **admin-only authorization is enforced here**.

## Side effects
- May emit an HTTP redirect header (via `redirect()`).
- Includes a view that outputs HTML.

## Inputs / Outputs contract
- **Inputs**:
  - Requires a valid session.
  - Requires `$_SESSION['user_id']` and `$_SESSION['is_admin'] == 1`.
- **Outputs**:
  - If authorized: renders `Views/Admin/Dashboard.php`.
  - If unauthorized: redirects to home.
