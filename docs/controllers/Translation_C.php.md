# Page Script: `Translation_C.php`

## Source file
- `src/Controllers/Translation_C.php`

## What this file is (important)
Despite being inside `src/Controllers`, `Translation_C.php` is **not a controller class**. It is a **standalone PHP page script** that:
- runs logic at file-load time
- then outputs an entire HTML document

It behaves more like a View+Controller combined in a single file.

## How it is used
There is **no direct route** in `Router::route` for `page=translation`.
- This file may be used manually (directly accessed) or included from some view.
- If you want it accessible via router, you would add a router case.

## Dependencies
### Required files
- `src/config/DataBase_Connection.php` (note the lowercase `config` in path)
- `src/Models/Article.php`
- `src/Services/Translation_S.php`
- `src/Models/User.php`

### Session dependencies
- `$_SESSION['user_id']` (optional) to determine subscription plan/language access.

### Query-string inputs
- `id`: article id (defaults to 1)
- `lang`: selected language (defaults to `en`)

### DB access
This file directly queries the DB using PDO:
- `SELECT * FROM articles WHERE id=?`

## Line-by-line (block-by-block)

### Lines 1–10: session + dependencies
- Starts session.
- Loads DB connection.
- Loads Article model, Translation service, and User model.

### Lines 13–16: create DB + translator
- Instantiates `Database` and opens PDO connection.
- Instantiates `TranslationService`.

### Lines 17–25: compute plan-based language availability
- Loads `User` model.
- If logged in, loads subscription via `User::getSubscription`.
- Extracts `$plan` and calls `TranslationService::getAvailableLangsForPlan($plan)`.

### Lines 26–33: read inputs + fetch article
- Reads article id (`id`) and selected language (`lang`).
- Fetches the article record from DB via prepared statement.
- Dies if not found.

### Lines 35–46: determine article original language
- Defines a `$languageCodeMap`.
- Computes `$articleLangCode` from `$article['language']`.
- Ensures the article’s original language appears in `$availableLangs`.

### Lines 48–56: translate if user selected a different language
- Initializes `$translatedArticle` to original `$article`.
- If selected language differs from original:
  - tries to translate via `TranslationService::translateArticle(articleId, selectedLang, plan)`
  - on error, falls back to original title/content.

### Lines 57–83: HTML output
- Exits PHP mode and prints a full HTML page.
- Includes a GET form with a language `<select>` that auto-submits on change.
- Displays title and content, escaped with `htmlspecialchars`.

## Relationships (cross-references)
- `ArticleController::index()` already implements translation for single articles and the list page using `TranslationService`.
- This file duplicates some of that logic but in a standalone page format.

## Side effects
- Opens a DB connection.
- Outputs a complete HTML document.

## Inputs / Outputs contract
- **Input**: `$_GET['id']`, `$_GET['lang']`, optional session user.
- **Output**: standalone HTML page.
