# Service: `NewsAPIService`

## Source file
- `src/Services/NewsAPI_S.php`

## Role of this service
`NewsAPIService` integrates with the external **NewsAPI** service to:
- fetch top headlines (`/v2/top-headlines`)
- search all news (`/v2/everything`)
- fetch breaking news (specialized `everything` query)

## Environment variables
- `NEWS_API_KEY`
  - If not set, the code falls back to a hardcoded key.

## Who calls it
- `ArticleController` (`news()` and list page widget)
- `SearchController` (`search()` endpoint)
- `BriefingController` (`getTopHeadlines`, `getBreakingNews`)

## Line-by-line (block-by-block)

### Lines 1–11: class + configuration
- Reads `NEWS_API_KEY` from environment.
- Sets `apiUrl` to `https://newsapi.org/v2/top-headlines`.

### Lines 13–65: `fetch(country, category, keyword, pageSize)`
- Builds query params:
  - `country`, `category`, optional `q` keyword
  - `apiKey`
  - `pageSize`
- Builds URL with `http_build_query`.
- Uses cURL:
  - `CURLOPT_RETURNTRANSFER` for string response
  - timeout 10 seconds
  - includes a `User-Agent: NewsApp/1.0` header
- Decodes JSON and validates `status == 'ok'`.
- Returns `articles` array or `[]` on errors.

### Lines 67–151: `search(query, category, language)`
- Builds a query for `/v2/everything`:
  - if category provided, it is appended to query string.
  - language mapping supports human language names (English/Arabic/etc.) and falls back to first 2 chars.
- Uses cURL similarly and returns `articles`.

### Lines 153–158: `getTopHeadlines(category, country, pageSize)`
- Thin wrapper around `fetch()`.

### Lines 160–188: `getBreakingNews(limit)`
- Uses `/v2/everything` endpoint with query:
  - `breaking OR live OR updates`
- Returns decoded `articles` (no explicit error handling beyond JSON decode).

## Relationships (cross-references)
- `Article::fetchNews()` and `Models/news.php` also fetch NewsAPI data via `file_get_contents`, but `NewsAPIService` is the more robust implementation.

## Side effects
- External HTTP requests.
- Logs errors to PHP error log.

## Inputs / Outputs contract
- Returns arrays shaped like NewsAPI’s `articles` payload.
