# View Page: `User/ForgotPassword.php`

## Source file
- `src/Views/User/ForgotPassword.php`

## Role of this view
Standalone "Forgot Password" page UI.

It collects an email address and posts back to the forgot-password route. The controller currently implements a placeholder flow (it does not send real email).

## Route / controller relationship
Route:
- `page=forgot-password` → `UserController::forgotPassword()` which includes `views/User/ForgotPassword.php`

## Variables expected (inputs)
- `$csrfToken` (string, optional)
- `$old` (array, optional): `email`
- `$errors` (array|string, optional)
- `$message` (string|null, optional)
- `$action` (string, optional; defaults to `index.php?page=forgot-password`)

## Forms and endpoints
- Form:
  - `method="post"`
  - `action="$action"`
  - Fields:
    - `csrf_token` (optional)
    - `email`

Links:
- Back to login: `index.php?page=Login`

## Line-by-line (block-by-block)

### Lines 1–9: defaults + escape helper

### Lines 11–35: HTML head and styles

### Lines 42–77: form + alerts
- Shows message/errors if present.
- Includes CSRF token if provided.
- Posts email.

## Side effects
- None (pure rendering).

## Notable caveats
- Controller currently does not enforce CSRF on forgot password (in `UserController::forgotPassword()`), but the view supports rendering a CSRF token if provided.
