# Controller: `UserController`

## Source file
- `src/Controllers/User_C.php`

## Role of this controller
`UserController` handles:
- user registration
- login
- AJAX validation endpoints (username/email availability)
- forgot password page (placeholder)
- logout
- account/profile update page
- admin-only user management endpoints (list users, toggle status, promote, delete)

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=register` → `UserController::register()`
- `page=login` → `UserController::login()`
- `page=check_username` → `UserController::ajax_check_username()`
- `page=check_email` → `UserController::ajax_check_email()`
- `page=forgot-password` → `UserController::forgotPassword()`
- `page=account` → `UserController::account()`
- `page=logout` → `UserController::logout()`

Admin routes:
- `page=admin_users` → `UserController::admin_index()`
- `page=admin_user_toggle` → `UserController::toggle_status()`
- `page=admin_user_promote` → `UserController::promote()`
- `page=admin_user_delete` → `UserController::destroy()`

## Dependencies
### Required files
- `src/Models/User.php`
- `src/Core/Helpers.php`

### Helper dependencies (from `Core/Helpers.php`)
- `sanitize($str)`
- `redirect($path)`
- `generateCsrfToken()`
- `verifyCsrfToken($token)`
- `getIpAddress()`

### Session dependencies
- `$_SESSION['csrf_token']`: CSRF protection
- `$_SESSION['user_id']`: logged-in user id
- `$_SESSION['is_admin']`: admin flag
- `$_SESSION['username']`, `$_SESSION['email']`: updated during account/profile update

### View dependencies
This controller includes view files using paths like `views/User/...` (note the lowercase `views`):
- `views/User/Registeration.php`
- `views/User/Login.php`
- `views/User/ForgotPassword.php`
- `views/User/account.php`

Cross-reference: your repository also contains `src/Views/...`. On Windows this often works due to case-insensitivity, but on Linux deployments it may break if the casing does not match exactly.

## Line-by-line (block-by-block)

## File header
### Lines 1–4: load dependencies
- **Line 1**: enters PHP.
- **Line 2**: loads the `User` model.
- **Line 3**: loads helper functions.

---

## Method: `register()`
### Lines 10–56: user registration flow

#### Lines 10–14: initialize state + CSRF token
- **Line 10**: declares the method.
- **Lines 11–12**: initialize `$errors` and `$old` for form handling.
- **Line 13**: generate a CSRF token and store it in `$csrfToken`.
  - Cross-reference: `generateCsrfToken()` also ensures `$_SESSION['csrf_token']` exists.

#### Lines 15–53: handle POST submission
- **Line 15**: only proceeds if the request is a POST.

##### Lines 16–19: CSRF verification
- Reads `$_POST['csrf_token']` and verifies it.
- On failure, stops execution with `die(...)`.

##### Lines 21–26: read + sanitize input
- Stores sanitized username/email in `$old`.
- Reads passwords without sanitizing (passwords are not meant to be HTML-escaped).

##### Lines 27–41: validation rules
- Required fields (username/email/password).
- Email format validation via `filter_var`.
- Password complexity enforced by a regex (min length 8 and character class requirements).
- Confirm password matching.

##### Lines 42–52: create user
- Hashes password via `password_hash`.
- Instantiates `User` model.
- Calls `User::create($username, $email, $hashedPassword)`.
- Redirects to `?page=Login` on success.

#### Lines 55–56: render register view
- Includes the registration view.

Relationships:
- `User::create` inserts into `users` table.
- Redirect uses `index.php` + `?page=...` convention (see `Router::route`).

---

## Method: `ajax_check_username()`
### Lines 58–65: username availability JSON endpoint
- Sanitizes `$_GET['username']`.
- Calls `User::existsByUsername()`.
- Outputs JSON and exits.

This endpoint is meant to be called via JavaScript while the user types.

---

## Method: `ajax_check_email()`
### Lines 67–74: email availability JSON endpoint
- Same pattern as username check.

---

## Method: `login()`
### Lines 81–123: login flow with CSRF + rate limiting

#### Lines 81–85: init state + CSRF token
- Initializes `$errors`, `$old`, `$csrfToken`.

#### Lines 86–120: handle POST

##### Lines 87–90: CSRF verification
- Same pattern as registration.

##### Lines 92–98: read inputs
- Sanitizes email.
- Reads password.
- Reads IP using `getIpAddress()` for rate limiting.

##### Lines 99–119: authenticate + rate limit
- Instantiates `User` model.
- Rate limiting:
  - calls `User::getRecentAttempts($email, $ip)`
  - blocks login after `>= 5` attempts
- Calls `User::login($email, $password)`.
  - if `true`: clears attempts and redirects home
  - if `'suspended'`: show suspension error
  - else: record attempt and show remaining attempts

Important relationship:
- `User::login()` sets:
  - `$_SESSION['user_id']`
  - `$_SESSION['username']`
  - `$_SESSION['is_admin']`
These are later used by `Router::route` and admin authorization.

#### Lines 122–123: render login view
- Includes the login view.

---

## Admin helper: `ensureAdmin()`
### Lines 129–135
- Starts session if needed.
- Verifies `$_SESSION['is_admin'] == 1`.
- Redirects home if not admin.

This is a controller-level authorization mechanism used by the admin actions below.

---

## Admin methods
### `admin_index()` (lines 137–142)
- Ensures admin.
- Fetches all users via `User::getAllUsers()`.
- Includes the admin users listing view: `Views/Admin/Users/index.php`.

### `toggle_status()` (lines 144–160)
- Ensures admin.
- Reads `id` and `status` from `$_POST`.
- Prevents the admin from suspending themselves.
- Toggles status between `active` and `suspended` via `User::updateStatus()`.
- Redirects back to `admin_users`.

### `promote()` (lines 162–170)
- Ensures admin.
- Reads `id`.
- Promotes user via `User::promoteToAdmin()`.
- Redirects back to `admin_users`.

### `destroy()` (lines 172–186)
- Ensures admin.
- Reads `id`.
- Prevents self-delete.
- Deletes user via `User::deleteUser()`.
- Redirects back to `admin_users`.

---

## Method: `forgotPassword()`
### Lines 194–213
- Implements a placeholder flow:
  - validates email
  - always returns a generic message when valid
- Includes forgot-password view.

Security note:
- The placeholder intentionally avoids leaking whether an email exists.

---

## Method: `logout()`
### Lines 221–233
- Starts session if needed.
- Calls `session_destroy()`.
- Redirects to `Home`.

Relationship:
- Clearing the session removes `user_id`, so `Router::route` will treat subsequent protected-page requests as unauthenticated.

---

## Method: `account()`
### Lines 239–312: account settings / profile updates

#### Lines 239–249: session + auth check
- Starts session.
- If not logged in (`$_SESSION['user_id']` missing), redirects to login.

#### Lines 251–256: load current user record
- Uses `User::getUserById($user_id)`.
- Stores the DB record in `$currentUser`.

#### Lines 257–259: init page messages
- `$error` and `$success` strings.

#### Lines 260–308: handle POST save
- Reads current password and proposed new values.
- Verifies current password using `password_verify(..., $currentUser['password'])`.
  - This ensures the user confirms identity before changing sensitive settings.

##### Lines 276–289: update username/email
- If username/email differs, calls `User::updateProfile(...)`.
- Updates `$_SESSION['username']` and `$_SESSION['email']` after success.

##### Lines 291–306: update password
- If new password provided:
  - confirms match
  - checks length >= 6
  - hashes via `password_hash`
  - saves via `User::updatePassword(...)`

#### Lines 310–312: render account view
- Includes `views/User/account.php`.

## Side effects
- Writes to session (`$_SESSION`) in multiple methods.
- Sends redirects (HTTP headers) via `redirect()`.
- Sends JSON responses (AJAX endpoints) via `header + echo + exit`.
- Writes to database via `User` model.

## Inputs / Outputs contract (summary)
- **register/login**:
  - Input: `$_POST` with fields + `csrf_token`
  - Output: HTML view or redirect
- **ajax_check_* **:
  - Input: `$_GET['username']` or `$_GET['email']`
  - Output: JSON `{ available: boolean }`
- **admin_* **:
  - Input: `$_POST` ids/status
  - Output: redirect or admin views
- **account**:
  - Input: session + `$_POST` values
  - Output: account view
