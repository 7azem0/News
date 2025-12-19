# Model: `Article`

## Source file
- `src/Models/Article.php`

## Role of this model
`Article` encapsulates database operations around local articles and article discovery:
- listing published articles
- fetching an article by id
- computing previous/next accessible article ids
- featured/latest feeds
- search with filters
- admin create/update/delete (via `save`/`delete`)
- recommendation and trending logic (based on likes)
- DB fallback news fetch (uses NewsAPI via `file_get_contents`)

## Tables used
- `articles`
- `plans` (used indirectly in `getAdjacentIds` via subquery)
- `article_likes` (recommendations + trending)

## Who calls it
- `ArticleController`
- `SearchController`
- `ForYouController`
- `BriefingController`

## Line-by-line (block-by-block)

### Lines 1–12: bootstrap + connection
- Requires DB connection class and creates PDO connection.

### Lines 14–17: `getAll()`
- Returns `id`, `title`, `thumbnail` from `articles` where `status='published'`.

### Lines 19–24: `getById($id)`
- Returns `SELECT * FROM articles WHERE id=?` as associative array or null.

### Lines 26–70: `getAdjacentIds($id, $maxPrice)`
Purpose:
- Returns previous/newer and next/older article IDs for navigation.

Key logic:
- Reads current article `created_at`.
- Builds an access predicate (`$accessibleSql`):
  - always requires `status='published'`
  - always allows `visibility='public'`
  - if `$maxPrice` is not null (user subscribed), also allows subscribed articles where:
    - `required_plan_id` is null OR plan price <= `:maxPrice`
- Queries for:
  - next article (older)
  - previous article (newer)

Relationship:
- Used by `ArticleController` single-article page.

### Lines 72–83: `getLatest($limit)`
- Returns latest articles ordered by featured first then scheduled/created date.
- Select includes `content as description` for view/API compatibility.

### Lines 85–99: `getFeatured($limit)`
- Similar to `getLatest`, but filters `is_featured=1`.

### Lines 101–144: `search(...)`
- Builds SQL dynamically based on optional filters:
  - query searches title/content via LIKE
  - category, author, language exact matches
  - tags uses LIKE (supports array or single)
- Returns published results ordered by `created_at DESC`.

Used by:
- `SearchController::index()`.

### Lines 146–218: filter list helpers
- `getAllCategories()`:
  - merges standard category list with distinct categories used in DB.
- `getAllTags()`:
  - loads all tags strings and explodes them into unique tag list.
- `getAllAuthors()`:
  - distinct author values.
- `getAllLanguages()`:
  - distinct language values + normalization.

### Lines 220–279: `save($data)`
Purpose:
- Insert or update an article.

Key behaviors:
- If `is_featured=1`, it first unfeatures all other articles.
- If `id` present → UPDATE.
- Else → INSERT.

Important mapping detail:
- The code uses `$data['description']` as the source for the DB column `content`.
  - Admin forms likely send `description` for article body.

Used by:
- `ArticleController` admin store/update.

### Lines 281–284: `delete($id)`
- Deletes row by id.

### Lines 286–359: `getRecommendations($userId, $limit)`
- Loads distinct categories/tags from articles the user liked.
- If none, falls back to latest.
- Otherwise selects published articles not already liked by user, matching category/tags.

Used by:
- `ForYouController`.

### Lines 361–378: `getTrendyArticles($limit)`
- Joins `article_likes` and orders by like count.

### Lines 380–384: `getAllAdmin()`
- Returns all articles regardless of status.

### Lines 386–419: `fetchNews($country, $category)`
- Uses `NEWS_API_KEY` env var.
- Uses `file_get_contents` and maps NewsAPI output to a local structure.

Relationship:
- Used as fallback when `NewsAPIService` fails (via `ArticleController::news()`).

## Side effects
- Writes to `articles` table.
- Uses `exec("UPDATE articles SET is_featured = 0")` when setting featured article.

## Inputs / Outputs contract
- Returns arrays of associative arrays for list methods.
- `save()` returns boolean.
- `getAdjacentIds()` returns `['prev'=>?, 'next'=>?]`.
