# View Page: `Articles/news.php`

## Source file
- `src/Views/Articles/news.php`

## Role of this view
Renders the **World News** page, showing:
- a hero article
- featured stories (next 4)
- latest news (remaining items)

All items are external NewsAPI items (or DB fallback items shaped similarly).

## Route / controller relationship
Route:
- `page=news` → `ArticleController::news(country, category)`

Controller provides:
- `$articles`: list of news items (external or fallback)

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
- `$articles`: array of items; each item is expected to include:
  - `title`
  - `description`
  - `publishedAt`
  - `url`
  - `urlToImage` or `imageUrl`

## Line-by-line (block-by-block)

### Lines 1–2: header include
- Loads global header.

### Lines 3–25: hero section
- If `$articles[0]` exists:
  - shows image
  - shows title linking to external URL
  - shows description and published date

### Lines 27–53: featured stories
- Iterates `$articles[1..4]` (up to 4 items).

### Lines 55–83: latest news
- Iterates remaining items starting from index 5.

### Lines 86–87: footer include

## Side effects
- None beyond rendering.

## Relationship notes
- If `ArticleController::news()` falls back to DB (`Article::fetchNews`), the returned keys are `imageUrl` and may not include `urlToImage`.
  - This view supports both.
