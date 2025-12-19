# View Page: `Admin/Users/index.php`

## Source file
- `src/Views/Admin/Users/index.php`

## Role of this view
Admin user management list.

Renders a full HTML document (no shared layout) and displays:
- all users
- role and status
- admin actions: suspend/activate, delete, promote

## Route / controller relationship
Route:
- `page=admin_users` → `UserController::admin_index()`

Authorization:
- `UserController::ensureAdmin()` is called before including this view.

## Variables expected (inputs)
- `$users`: array of user records from `User::getAllUsers()`.

Session usage:
- `$_SESSION['user_id']` is used to prevent admins from modifying themselves.

## Forms and endpoints
- Toggle status:
  - `POST index.php?page=admin_user_toggle`
  - fields: `id`, `status`

- Delete user:
  - `POST index.php?page=admin_user_delete`
  - field: `id`

- Promote user:
  - `POST index.php?page=admin_user_promote`
  - field: `id`

## Line-by-line (block-by-block)

### Lines 1–28: page header
- Title and link back to admin dashboard.

### Lines 30–92: users table
- Iterates `$users`.
- Shows role based on `is_admin`.
- Shows status badge based on `status`.
- For non-self rows (`$user['id'] != $_SESSION['user_id']`), shows action forms.

## Side effects
- None by itself; forms post to controller actions that mutate DB.

## Notable caveats
- No CSRF tokens are present on admin action forms.
