# View Page: `Home.php`

## Source file
- `src/Views/Home.php`

## Role of this view
This is the main landing page rendered for:
- `page=""|"index"|"home"` in the router.

Unlike many pages, this view performs some of its own data loading (it instantiates `Article` model directly).

## Route / controller relationship
From `Router::route`:
- Home route includes `Views/Home.php` directly (no controller).

## Dependencies
### Layout includes
- Includes header: `include(__DIR__ . '/layout/Header.php')`
- Includes footer: `include(__DIR__ . '/layout/Footer.php')`

Important casing note:
- The actual folder is `Views/Layout/` (uppercase `L`). This include uses lowercase `layout/`.
  - On Windows this often works.
  - On Linux it may fail.

### Model usage inside the view
- Requires `Models/Article.php`.
- Instantiates `Article` and calls:
  - `Article::getLatest(4)`

So variables are produced in-view:
- `$latestArticles`, `$mainStory`, `$sideStories`

### Routes referenced by links/buttons
- `?page=morning` (Morning briefing)
- `?page=live` (Live briefing)
- `?page=article&id=<id>` (single article pages)
- `?page=games` (games hub)

## Line-by-line (block-by-block)

### Lines 1–14: bootstrap + data preparation
- **Line 1**: includes layout header.
- **Lines 2–5**: loads Article model and instantiates it.
- **Lines 6–9**: tries to fetch latest 4 articles.
  - Errors are swallowed.
- **Lines 11–12**: derives:
  - `$mainStory` (first article)
  - `$sideStories` (next three)

### Lines 15–99: page layout
This page is structured as a 2-column grid:

Main column:
- "The Morning" banner linking to `page=morning`.
- Lead story block (if `$mainStory` exists):
  - title linking to single article
  - date line
  - optional thumbnail image
  - short excerpt and "Read more" link

Side column:
- Live briefing ticker linking to `page=live`.
- Latest headlines list from `$sideStories`.
- Games widget linking to `page=games`.

### Lines 102–109: inline CSS
- Defines hover style and `@keyframes pulse` used by live ticker indicator.

### Line 114: footer include
- Includes layout footer to close the HTML document.

## Variables expected by this view
- None required from the router/controller.

## Variables produced and consumed by this view
- `$latestArticles`, `$mainStory`, `$sideStories`.

## Side effects
- Performs a DB read (via `Article::getLatest`) even though it is a view.

## Output contract
- Outputs a full HTML page via layout includes.
