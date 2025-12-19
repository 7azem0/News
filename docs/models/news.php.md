# Model file: `news.php` (legacy NewsFetcher)

## Source file
- `src/Models/news.php`

## What this is
This file defines a class `NewsFetcher` that fetches top headlines from NewsAPI using `file_get_contents`.

Despite living in `Models/`, the file header comment says `src/Services/NewsFetcher.php`, and functionally it behaves like a Service.

## Overlap with current code
The project already has:
- `src/Services/NewsAPI_S.php` (`NewsAPIService`) which uses cURL and supports:
  - `fetch` (top-headlines)
  - `search` (everything)
  - `getBreakingNews`

Additionally, `Article::fetchNews()` also fetches NewsAPI data via `file_get_contents`.

So there are currently **three** NewsAPI fetch implementations:
- `NewsAPIService` (preferred)
- `Article::fetchNews()` (DB fallback helper)
- `NewsFetcher` (legacy)

## Line-by-line (block-by-block)

### Lines 1–13: class and config
- Loads config by including `../config/API.php`.
- Reads `newsapi_key` and `newsapi_url`.

### Lines 15–43: `fetch(country, category)`
- Builds URL using `sprintf`.
- Fetches response via `file_get_contents`.
- Parses JSON.
- Maps results to a simplified array structure.

## Security note
Config file `config/API.php` handling is inconsistent in the codebase; see:
- `docs/03-config-and-secrets.md`

## Recommendation (documentation only)
Consider consolidating all NewsAPI logic into `NewsAPIService` and removing duplicates.
