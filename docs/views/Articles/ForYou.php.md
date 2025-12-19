# View Page: `Articles/ForYou.php`

## Source file
- `src/Views/Articles/ForYou.php`

## Role of this view
Renders the "For You" page showing:
- recommended articles
- trending articles with like counts

## Route / controller relationship
Route:
- `page=for_you` → `ForYouController::index()`

Controller provides:
- `$recommendations`
- `$trendyArticles`

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
### `$recommendations`
Each item is assumed to have:
- `id`
- `title`
- `thumbnail` (optional)
- `content` (optional; shown as excerpt)

### `$trendyArticles`
Each item is assumed to have:
- `id`
- `title`
- `thumbnail` (optional)
- `content` (optional)
- `like_count` (required for the badge)

### Session usage
- Checks `$_SESSION['user_id']` to show a "Log in" prompt.

## Routes referenced by links
- Article cards navigate to: `?page=article&id=<id>`.
- Login prompt links to `?page=Login`.

## Line-by-line (block-by-block)

### Lines 1–3: header + container
- Includes header and starts main container.

### Lines 4–40: recommendations section
- Title "Recommended For You".
- If not logged in, shows a prompt linking to login.
- Renders a grid of `$recommendations` with thumbnail + title + excerpt.

### Lines 42–77: trendy section
- Renders a grid of `$trendyArticles`.
- Shows a badge with like count.

### Lines 80–84: small hover styling

### Line 86: footer include

## Side effects
- None.

## Relationship note
- This page is designed to work for guests (it displays non-personalized content), but Router auth gating may still redirect guests unless `for_you` is in the router public pages list.
