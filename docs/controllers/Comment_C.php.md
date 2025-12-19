# Controller: `CommentController`

## Source file
- `src/Controllers/Comment_C.php`

## Role of this controller
`CommentController` handles:
- admin moderation of comments (list, approve, reject, delete)
- user-facing comment submission (`store`)

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):

Admin routes:
- `page=admin_comments` → `CommentController::admin_index()`
- `page=admin_comment_approve` → `CommentController::approve()`
- `page=admin_comment_reject` → `CommentController::reject()`
- `page=admin_comment_delete` → `CommentController::destroy()`

User-facing:
- `page=comment_store` → `CommentController::store()`

## Dependencies
### Required files
- `src/Models/Comment.php`

### Session dependencies
- `$_SESSION['is_admin']` for admin moderation
- `$_SESSION['user_id']` for comment submission

### Views included
- `Views/Admin/Comments/index.php`

## Line-by-line (block-by-block)

## File header
### Lines 1–7: session + model
- Starts session.
- Requires `Comment` model.

---

## Admin authorization helper
### Lines 10–15: `ensureAdmin()`
- Checks `$_SESSION['is_admin']` is set and equals `1`.
- Redirects to `index.php?page=Home` via raw `header('Location: ...')`.

Relationship:
- Admin status is set during login (`User::login()`), and other controllers use similar checks.

---

## Admin: moderation endpoints
### `admin_index()` (lines 17–22)
- Ensures admin.
- Loads all comments via `Comment::getAllComments()`.
- Includes admin comments index view.

### `approve()` (lines 24–30)
- Ensures admin.
- Reads `id` from POST.
- Updates status to `approved`.
- Redirects back to `admin_comments`.

### `reject()` (lines 32–38)
- Ensures admin.
- Reads `id`.
- Updates status to `flagged`.
- Redirects back.

### `destroy()` (lines 40–46)
- Ensures admin.
- Reads `id`.
- Deletes comment.
- Redirects back.

---

## User-facing: `store()`
### Lines 49–77: comment submission
- Ensures session.
- Requires user login; redirects to login if missing `$_SESSION['user_id']`.
- Reads `article_id` and comment `content` from POST.
- Validates non-empty.
- Calls `Comment::add(userId, articleId, content)`.
- Redirects back to the article page with a success flag.

Relationship:
- The article page (`ArticleController::index()` single-article branch) fetches comments via `Comment::getByArticleId($id)`.

## Side effects
- DB writes/updates/deletes via `Comment` model.
- Redirects using raw `header('Location: ...')`.

## Inputs / Outputs contract
- **Admin endpoints**:
  - Input: POST `id`
  - Output: redirect
- **store**:
  - Input: POST `article_id`, POST `content`, session `user_id`
  - Output: redirect to `page=article&id=...`
