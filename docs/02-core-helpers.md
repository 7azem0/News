# Core Helpers

## File: `src/Core/Helpers.php`

### Role of this file
Defines global helper functions used across controllers and views:
- `redirect($path)`
- `sanitize($str)`
- `generateCsrfToken()` / `verifyCsrfToken($token)`
- `getIpAddress()`

### Who calls it
- Included by `src/index.php`.
- Controllers frequently call `redirect`, `sanitize`, CSRF helpers.

### Dependencies
- Uses:
  - `header()` and `exit` (HTTP response control)
  - session state (`$_SESSION`)
  - `random_bytes()` for CSRF token generation

### Line-by-line (block-by-block)

#### Lines 1–8: `redirect` helper
- **Line 1**: starts PHP.
- **Line 2**: prevents redeclaring the function if included twice.
- **Line 3**: declares `redirect($path)`.
- **Line 4**: sends `Location` header pointing to `index.php` + `$path`.
  - The second parameter `true` replaces previous headers.
  - Status `303` forces clients to do a GET after a POST (useful for Post/Redirect/Get pattern).
- **Line 5**: stops execution immediately after redirect.
- **Lines 6–8**: close function and guard.

#### Lines 10–14: `sanitize` helper
- **Line 10**: guard against redeclaration.
- **Line 11**: declares `sanitize($str)`.
- **Line 12**: trims whitespace and escapes HTML special chars.
  - Prevents reflected/stored XSS when outputting user-provided strings.
- **Lines 13–14**: close function and guard.

#### Lines 16–25: CSRF token generation
- **Line 16**: section label.
- **Line 17**: guard.
- **Line 18**: declares `generateCsrfToken()`.
- **Line 19**: ensures session is started (CSRF token is stored in session).
- **Lines 20–22**: if missing, generate and store a random 32-byte token as hex.
- **Line 23**: return token.
- **Lines 24–25**: close.

#### Lines 27–32: CSRF token verification
- **Line 27**: guard.
- **Line 28**: declares `verifyCsrfToken($token)`.
- **Line 29**: ensures session started.
- **Line 30**: uses `hash_equals` to compare user token vs session token safely.
  - `hash_equals` helps prevent timing attacks.
- **Lines 31–32**: close.

#### Lines 34–39: IP address helper
- **Line 34**: section label.
- **Line 35**: guard.
- **Line 36**: declares `getIpAddress()`.
- **Line 37**: reads `$_SERVER['REMOTE_ADDR']`.
- **Line 38**: fallback to `'unknown'`.
- **Line 39**: close.

### Relationships (cross-references)
- `generateCsrfToken` and `verifyCsrfToken` are used in `UserController::register()` and `UserController::login()`.
- `getIpAddress` is used for login rate limiting (`User::getRecentAttempts`, `User::recordLoginAttempt`).
- `redirect('?page=Home')` relies on `Router::route` understanding the `page` parameter.
