# View Page: `Admin/Articles/index.php`

## Source file
- `src/Views/Admin/Articles/index.php`

## Role of this view
Admin articles list page.

Displays all articles and provides:
- link to create new article
- link to edit existing article
- delete article form

## Route / controller relationship
Route:
- `page=admin_articles` → `ArticleController::admin_index()`

Authorization:
- `ArticleController::ensureAdmin()` is called before including this view.

## Variables expected (inputs)
- `$articles`: array from `Article::getAllAdmin()`.

## Links/forms
- Back to dashboard: `index.php?page=admin`
- Create: `index.php?page=admin_article_create`
- Edit: `index.php?page=admin_article_edit&id=<id>`
- Delete:
  - `POST index.php?page=admin_article_delete`
  - field: `id`

## Line-by-line (block-by-block)

### Lines 1–29: header

### Lines 31–64: articles table
- Renders title, author, status badge, visibility, created_at, and actions.

## Side effects
- None directly.
