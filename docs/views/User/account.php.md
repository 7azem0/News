# View Page: `User/account.php`

## Source file
- `src/Views/User/account.php`

## Role of this view
Account settings page for editing:
- username (UI allows unlocking readonly input)
- email
- password

Unlike Login/Register/ForgotPassword, this page uses the shared layout.

## Route / controller relationship
Route:
- `page=account` → `UserController::account()` which includes `views/User/account.php`

The controller prepares `$currentUser`, `$error`, `$success`.

## Layout includes
- Requires `Views/Layout/Header.php`
- Requires `Views/Layout/Footer.php`

## Variables expected (inputs)
From `UserController::account()`:
- `$currentUser` (array): contains `username`, `email`, and `password` hash
- `$error` (string)
- `$success` (string)

Session variables used as fallback:
- `$_SESSION['username']`
- `$_SESSION['email']`

## Forms and endpoints
- Main form:
  - `action="index.php?page=Account"` (note uppercase `Account`)
  - `method="POST"`
  - Fields:
    - `username` (readonly by default, unlocked via JS)
    - `email` (readonly by default)
    - `new_password`
    - `confirm_password`
    - `current_password` (**required**)

## Line-by-line (block-by-block)

### Lines 1–2: header include

### Lines 3–15: headings + alerts
- Shows `$error` and `$success`.

### Lines 16–73: account form
- Profile info section:
  - username and email inputs are `readonly` and can be enabled by clicking ✏️.
- Change password section.
- Current password required section.

### Lines 77–98: helper JS
- `enableEdit(id)` removes readonly.
- `togglePassword(id, icon)` toggles input type.

### Line 100: footer include

## Side effects
- None by itself; posts to controller which updates DB.

## Notable caveats
- No CSRF token is included on this form in the current view.
