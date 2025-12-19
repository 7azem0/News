# Project Overview (News)

## Purpose
This project is a PHP web application that serves news/articles content with:
- local articles stored in MySQL
- external news fetched from NewsAPI
- translation via a LibreTranslate-compatible API
- user accounts + admin panel
- subscriptions (plans limit translation/language features)

## High-level architecture
The codebase follows an MVC-style structure (not framework-based):
- **Entry point**: `src/index.php`
- **Routing/dispatch**: `src/Core/Router.php` (`Router::route`)
- **Controllers**: `src/Controllers/*_C.php` (request handling)
- **Models**: `src/Models/*.php` (database access)
- **Services**: `src/Services/*.php` (integration with external APIs / utilities)
- **Views**: `src/Views/**` (HTML/PHP templates)

## Request lifecycle (how a request becomes a page)
1. **Browser requests** `src/index.php` (usually `GET /index.php?page=...`).
2. `index.php` starts the session (if needed), loads core files, then computes:
   - `page` from `$_GET['page']` (defaults to empty string)
   - `selectedLang` from `$_SESSION['lang']` (defaults to `en`)
3. `index.php` calls `Router::route($page, $selectedLang)`.
4. `Router::route`:
   - starts the session (again, defensively)
   - enforces authentication for protected pages using `$_SESSION['user_id']`
   - dispatches to a controller method (e.g. `ArticleController::index`) or includes a view
5. Controllers load models/services, read `$_GET`/`$_POST`, and include a view.
6. Views render HTML and may contain forms that post back to `index.php?page=...`.

## Shared state conventions
- **Session keys** (examples used across files):
  - `$_SESSION['user_id']`: indicates logged-in user and is used by Router auth gate.
  - `$_SESSION['username']`, `$_SESSION['email']`: display + account pages.
  - `$_SESSION['is_admin']`: admin authorization.
  - `$_SESSION['lang']`: selected UI/content language.
  - `$_SESSION['csrf_token']`: CSRF protection for form posts.

## Documentation conventions used in `/docs`
- **Option A (block-by-block)**: every line is accounted for by explaining contiguous line ranges.
- **Line numbers** refer to the current repository snapshot. If code changes, regenerate docs.
- **Cross-references** are written as:
  - `Router::route` → route case `"for_you"` → `ForYouController::index` → view `Views/Articles/ForYou.php`

## Directory map (source)
- `src/Core`
  - `Router.php`: dispatcher + auth gate + route table
  - `Helpers.php`: `redirect`, `sanitize`, CSRF helpers
- `src/Controllers`: request handlers
- `src/Models`: PDO access layer
- `src/Services`: external integrations (translation/news/pdf)
- `src/Views`: UI templates

## Next chapters
- `01-entry-and-routing.md`
- `02-core-helpers.md`
- `03-config-and-secrets.md`
- `04-route-table.md`
