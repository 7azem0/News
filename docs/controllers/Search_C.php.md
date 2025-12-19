# Controller: `SearchController`

## Source file
- `src/Controllers/Search_C.php`

## Role of this controller
`SearchController` renders the Search page and coordinates:
- searching local articles in the database (with optional filters)
- searching external news via NewsAPI (query/category/language)
- providing sidebar filter lists (categories/tags/authors/languages)

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=search` → `SearchController::index()`

## Dependencies
### Required files
- `src/Models/Article.php`
- `src/Services/NewsAPI_S.php`

### Query-string inputs
- `q`: full-text query (string)
- `category`: category filter
- `author`: author filter
- `language`: language filter
- `tags` (array) or `tag` (single)

### View included
- `src/Views/Articles/Search.php`

### Model/service methods expected
- `Article::search($query, $category, $tags, $author, $language)`
- `Article::getAllCategories()`, `getAllTags()`, `getAllAuthors()`, `getAllLanguages()`
- `NewsAPIService::search($query, $category, $language)`

## Line-by-line (block-by-block)

### Lines 1–5: bootstrap
- **Line 3**: load Article model (DB search + filter lists).
- **Line 4**: load NewsAPI service (external search).

### Lines 6–48: `index()` action
#### Lines 8–16: read search inputs
- Reads inputs from `$_GET`.
- Supports either `tags` (likely array) or a single `tag`.

#### Lines 17–26: empty state rendering
- If no query and no filters are provided:
  - still loads filter lists from Article model (categories/tags/authors/languages)
  - includes `Views/Articles/Search.php`
  - returns early

This supports a UX where the Search page loads with filters even when no search is active.

#### Lines 28–38: local DB search
- Instantiates `Article`.
- Loads sidebar filter lists (again).
- Calls `Article::search(...)` to get `$articles`.

#### Lines 39–45: external API search
- Initializes `$news = []`.
- Calls the external API search only if `q` or `category` is present.
  - Tags are ignored for API search (comment explains why).

#### Line 47: render
- Includes the search view, which is expected to render:
  - `$articles` (local results)
  - `$news` (API results)
  - `$allCategories`, `$allTags`, `$allAuthors`, `$allLanguages` (sidebar filters)

## Relationships (cross-references)
- Route `page=search` is protected by Router unless `search` is included in `$publicPages`.
- Search depends heavily on the Article model’s query logic.

## Side effects
- None beyond rendering (reads query string + calls DB/API).

## Inputs / Outputs contract
- **Input**: query params listed above.
- **Output**: HTML view `Views/Articles/Search.php`.
