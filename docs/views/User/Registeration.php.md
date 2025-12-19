# View Page: `User/Registeration.php`

## Source file
- `src/Views/User/Registeration.php`

## Role of this view
Standalone registration page UI.

Like the login page, it renders a full HTML document and does not include the shared layout header/footer.

## Route / controller relationship
Route:
- `page=register` → `UserController::register()` which includes `views/User/Registeration.php`

## Variables expected (inputs)
- `$csrfToken` (string, optional)
- `$old` (array, optional): `username`, `email`
- `$errors` (array|string, optional)
- `$message` (string|null, optional)
- `$action` (string, optional; defaults to `index.php?page=register` and is used by the form)

## Forms and endpoints
- Registration form:
  - `method="post"`
  - `action="$action"`
  - Fields:
    - `csrf_token`
    - `username`
    - `email`
    - `password`
    - `confirm_password`

AJAX endpoints used by embedded JS:
- Username availability check:
  - `GET index.php?page=check_username&username=<value>`
  - Cross-reference: `UserController::ajax_check_username()`.
- Email availability check:
  - `GET index.php?page=check_email&email=<value>`
  - Cross-reference: `UserController::ajax_check_email()`.

Links:
- Login: `index.php?page=Login`

## Line-by-line (block-by-block)

### Lines 1–9: defaults + escape helper

### Lines 11–36: HTML head and styles

### Lines 42–100: registration form
- Shows error/message alerts.
- Includes CSRF token hidden input.
- Adds `<small>` elements for availability messages.
- Includes password strength meter UI.

### Lines 102–165: client-side validation / UX helpers
- `checkAvailability(type, value, msgElement)` calls the AJAX endpoints.
- `blur` handlers trigger checks for username and email.
- `input` handler for password strength meter.
- On submit, blocks submission if username/email checks failed.

## Side effects
- Client-side network calls to availability endpoints.

## Notable caveats
- The file name is `Registeration.php` (spelling). The controller includes `views/User/Registeration.php`.
