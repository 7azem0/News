# View Page: `Admin/Dashboard.php`

## Source file
- `src/Views/Admin/Dashboard.php`

## Role of this view
Standalone Admin dashboard landing page.

This template renders a full HTML document and does not include the shared layout header/footer.

## Route / controller relationship
Route:
- `page=admin` → `AdminController::index()` includes `Views/Admin/Dashboard.php`

Authorization:
- Admin access is enforced by `AdminController::index()` before including this view.

## Variables expected
- None.

## Links/routes referenced
- Return to site: `index.php?page=Home`

Admin sections:
- `index.php?page=admin_articles`
- `index.php?page=admin_users`
- `index.php?page=admin_plans`
- `index.php?page=admin_subscription_assign`
- `index.php?page=admin_comments`

## Line-by-line (block-by-block)

### Lines 1–29: document head
- Full HTML document structure with inline CSS.

### Lines 30–66: dashboard cards
- Renders a set of links styled as cards for admin subsystems.

## Side effects
- None (pure rendering).
