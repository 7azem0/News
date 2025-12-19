# Troubleshooting & Security Notes

This chapter documents common failure modes and security-relevant behaviors observed in the current codebase.

## Troubleshooting

## 1) "Database Connection Error"
Symptoms:
- You see `Database Connection Error: ...` from `DataBase_Connection.php`.

Likely causes:
- MySQL container not running or not healthy.
- Database not imported (`news_dump.sql`).
- Credentials mismatch.

Important configuration detail:
- `Database` reads **`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`**.
- `docker-compose.yml` sets `MYSQL_HOST`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DB` for the PHP container.

If you change the DB credentials in compose, also set matching `DB_*` vars or update the defaults in `Database`.

## 2) NewsAPI returns empty results
Possible reasons:
- Missing/invalid `NEWS_API_KEY`.
- Rate limit reached.
- Network issues inside container.

Code behavior:
- `NewsAPIService::fetch()` returns `[]` on API failure.
- Some pages use DB fallback (`Article::fetchNews`).

## 3) Translation fails
Possible reasons:
- LibreTranslate container not running.
- Wrong `LIBRE_TRANSLATE_URL`.
- External libretranslate instance blocked / rate limited.

Code behavior:
- Many translation calls are wrapped in try/catch and silently fall back to original text.
- Translation results are cached into `translations` table.

## 4) "Page not found" (404)
- Router falls back to 404 if `$_GET['page']` doesn’t match any route.
- See `docs/04-route-table.md`.

## 5) Linux deployment issues (case sensitivity)
On Windows, path casing often doesn’t matter; on Linux it does.

Potential problems:
- Some includes reference `views/...` while repo uses `Views/...`.
- Some includes reference `config/...` while repo uses `Config/...`.

Recommendation:
- Standardize directory casing and update all includes accordingly.

---

## Security notes

## 1) Hardcoded secrets in repository
- `src/Config/API.php` and `src/Services/NewsAPI_S.php` contain a hardcoded NewsAPI key fallback.

Risk:
- If the repo is public, the key may be compromised.

Recommendation:
- Rotate the key.
- Use environment variables only (no hardcoded fallback).

## 2) CSRF protection is present but not universal
- `UserController::register()` and `UserController::login()` verify CSRF.
- Other POST endpoints (e.g. admin CRUD, comments, subscribe) may not enforce CSRF.

Recommendation:
- Add CSRF tokens to all state-changing POST requests.

## 3) Session authorization patterns
- Router enforces login for non-public pages using `$_SESSION['user_id']`.
- Admin controllers also enforce admin checks using `$_SESSION['is_admin']`.

Recommendation:
- Keep admin checks centralized or consistent.

## 4) Input validation consistency
- Some endpoints sanitize user input (`sanitize`).
- Others read `$_POST` / `$_GET` directly.

Recommendation:
- Validate and sanitize all user-controlled inputs, especially in admin CRUD and file upload.

## 5) File upload risks
- `ArticleController::store()` / `update()` accept `thumbnail` uploads.

Risks:
- Uploading unexpected file types.
- Path traversal via file name.

Recommendation:
- Restrict allowed MIME types/extensions.
- Generate random filenames (not based on original file name alone).
- Store uploads outside the web root or enforce strict access controls.

## 6) `src/Config/API.php` self-include risk
`src/Config/API.php` includes `__DIR__ . '/../config/API.php'` and then returns an array.
Depending on the environment and filesystem casing behavior, this can accidentally include itself.

Recommendation:
- Replace with a single authoritative config file.

## 7) JSON endpoints without explicit JSON content type
Some endpoints echo JSON but may not set `Content-Type: application/json`.

Recommendation:
- Set consistent response headers for all JSON endpoints.
