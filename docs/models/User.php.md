# Model: `User`

## Source file
- `src/Models/User.php`

## Role of this model
`User` encapsulates database operations related to accounts and some account-adjacent concerns:
- create user
- existence checks (username/email)
- login (password verification + session initialization)
- login rate limiting persistence (`login_attempts` table)
- reading current subscription
- profile updates
- admin user management operations (list/toggle status/promote/delete)

## Tables used
- `users` (primary)
- `subscriptions` (read via `getSubscription`)
- `login_attempts` (created dynamically by code; **not** present in `news_dump.sql`)

## Who calls it
- `UserController` (`src/Controllers/User_C.php`)
- `ArticleController` reads `getSubscription` for feature gating
- Admin controllers use user listing/status changes

## Line-by-line (block-by-block)

### Lines 1–10: bootstrap + connection
- **Line 1**: enter PHP.
- **Line 2**: require DB connection class.
- **Lines 4–10**: define `User` class and create a PDO connection in constructor.

### Lines 12–35: `create($username, $email, $password)`
- Prepares an INSERT into `users`.
- Catches `PDOException`:
  - if MySQL duplicate entry (`1062`), returns `false`.
  - otherwise rethrows.

Relationship:
- Called by `UserController::register()` after password hashing.

### Lines 37–47: existence checks
- `existsByUsername` and `existsByEmail` return boolean based on `COUNT(*)`.

Used by:
- AJAX endpoints `UserController::ajax_check_username/email()`.

### Lines 49–85: login rate limiting helpers
- `recordLoginAttempt(email, ip)`:
  - creates table `login_attempts` if it doesn’t exist
  - inserts an attempt row
- `getRecentAttempts(email, ip, minutes)`:
  - counts attempts by email OR ip within the last N minutes
  - returns 0 on DB errors
- `clearLoginAttempts(email, ip)`:
  - deletes attempts for email OR ip

Relationship:
- Called by `UserController::login()`.

Important design note:
- Creating tables at runtime is convenient for demos but unusual for production (migrations are preferred).

### Lines 88–115: `login($email, $password)`
- Loads user row by email.
- Verifies password hash.
- Checks status:
  - returns `'suspended'` if user is suspended.
- Ensures session exists, then sets:
  - `$_SESSION['user_id']`
  - `$_SESSION['username']`
  - `$_SESSION['is_admin']`
- Returns `true` on success, `false` otherwise.

Relationships:
- Router uses `$_SESSION['user_id']` to allow protected pages.
- Admin checks use `$_SESSION['is_admin']`.

### Lines 117–129: `getSubscription($userId)`
- Reads most recent subscription row where:
  - `expires_at` is NULL OR in the future
- Returns subscription row or null.

Used by:
- `ArticleController` (translation + PDF gating + access control)
- `Translation_C.php` script

### Lines 130–156: account update helpers
- `getUserById($id)` fetches full user row.
- `updateProfile($id, $username, $email)` updates username/email.
- `updatePassword($id, $hashedPassword)` updates password.

Used by:
- `UserController::account()`.

### Lines 158–178: admin methods
- `getAllUsers()`
- `updateStatus($id, $status)`
- `promoteToAdmin($id)`
- `deleteUser($id)`

Used by:
- `UserController` admin actions.

## Side effects
- Writes `$_SESSION` in `login()`.
- Can create DB table `login_attempts`.

## Inputs / Outputs contract
- Most methods accept scalar parameters and return arrays/bools.
- `login()` returns: `true` / `false` / `'suspended'`.
