# View Page: `Articles/list.php`

## Source file
- `src/Views/Articles/list.php`

## Role of this view
Renders the **local articles listing** page and an embedded "Latest World News" section.

## Route / controller relationship
Route:
- `page=article` without `id` → `ArticleController::index()` listing branch

This view expects `ArticleController` to provide the dataset variables.

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
Provided by `ArticleController::index()` listing branch:
- `$articles`: array of local articles
- `$news`: array of external news items (from NewsAPI)
- `$availableLangs`: map of language code → label
- `$selectedLang`: currently selected language code

Each local `$article` item is assumed to have keys like:
- `id`
- `title`
- `thumbnail` (optional)
- `publishedAt` (optional; falls back to `now`)
- `description` (optional)
- `category_name` (optional)

Each external `$item` is assumed to have keys like:
- `title`
- `description`
- `url`
- `urlToImage` or `imageUrl`

## Routes referenced by links/forms
- Language select form submits GET to `index.php?page=article&lang=<code>`.
- Article links go to `?page=article&id=<id>`.
- External news links open in a new tab (`target="_blank"`).

## Line-by-line (block-by-block)

### Lines 1–2: header include
- Loads the global layout header.

### Lines 3–19: page heading + language selector
- Page title "Articles".
- A GET form with `<select name="lang">`.
- Dropdown options are built from `$availableLangs` and mark the selected item using `$selectedLang`.

Relationship:
- Changing language triggers `ArticleController::index()` again with `$_GET['lang']`, which may translate content.

### Lines 21–55: articles grid
- If `$articles` is not empty:
  - loops through each article
  - renders optional thumbnail image
  - renders title linking to single article
  - renders date + optional category name
  - renders a truncated description
- Else prints "No articles available."

### Lines 56–81: embedded "Latest World News"
- Displays external news cards from `$news`.
- Each card optionally shows image (`urlToImage` or `imageUrl`), title linking to external URL, and a truncated description.

### Lines 85–86: footer include
- Closes page via layout footer.

## Side effects
- None (pure rendering).

## Common pitfalls
- Some local article records returned by `Article::getAll()` only include `id,title,thumbnail` (no `publishedAt`/`description`).
  - This view handles missing keys by using defaults, but the excerpt will be empty.
