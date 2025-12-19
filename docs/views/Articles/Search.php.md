# View Page: `Articles/Search.php`

## Source file
- `src/Views/Articles/Search.php`

## Role of this view
Renders the Search page with:
- a filter sidebar (query/category/tags/author/language)
- results grid combining:
  - local DB articles
  - external NewsAPI results

## Route / controller relationship
Route:
- `page=search` → `SearchController::index()`

Controller provides:
- input echo variables (`$query`, `$category`, `$tags`, `$author`, `$language`)
- filter lists (`$allCategories`, `$allTags`, `$allAuthors`, `$allLanguages`)
- results (`$articles`, `$news`)

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
Filter echo:
- `$query`
- `$category`
- `$tags` (array or string)
- `$author`
- `$language`

Filter lists:
- `$allCategories` (array)
- `$allTags` (array)
- `$allAuthors` (array)
- `$allLanguages` (array)

Results:
- `$articles` (local DB results)
- `$news` (external results)

## Routes referenced by forms/links
- Filter form submits GET to `index.php?page=search`.
- Clear filters link: `index.php?page=search`.
- Local results link to `index.php?page=article&id=<id>`.
- External results link to `url` in new tab.

## Line-by-line (block-by-block)

### Lines 1–4: header + grid layout

### Lines 6–74: filter sidebar form
- Hidden `page=search`.
- Search term input `q`.
- Category select.
- Tags section renders checkboxes `tags[]`.
  - Computes `$selectedTags` by normalizing `$tags` to an array.
- Author select.
- Language select.
- Submit button + clear link.

### Lines 76–146: results section
- Prints count = local articles + external news.
- Renders a grid:
  - local article cards (thumbnail, category, title, excerpt)
  - external news cards (image, title, outbound arrow)
- If both datasets are empty, prints "No results found".

### Lines 150–151: footer include

## Side effects
- None.
