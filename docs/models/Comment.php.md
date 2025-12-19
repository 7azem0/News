# Model: `Comment`

## Source file
- `src/Models/Comment.php`

## Role of this model
Encapsulates comment persistence:
- list all comments for admin moderation
- approve/flag/delete
- fetch approved comments for an article
- add a new comment

## Table used
- `comments`

## Who calls it
- `CommentController`
- `ArticleController` loads comments for single article page

## Line-by-line (block-by-block)

### Lines 1–10: bootstrap + connection
- Requires DB connection and opens PDO connection.

### Lines 12–21: `getAllComments()`
- Returns comments joined with:
  - `users.username`
  - `articles.title` as `article_title`
- Ordered by newest first.

Used by:
- `CommentController::admin_index()`.

### Lines 23–26: `updateStatus($id, $status)`
- Updates `comments.status`.

Used by:
- admin approve/reject endpoints.

### Lines 28–31: `delete($id)`
- Deletes comment by id.

### Lines 33–44: `getByArticleId($articleId)`
- Loads approved comments for a single article.
- Joins with users to include username.

Used by:
- `ArticleController` single page.

### Lines 46–48: `add($userId, $articleId, $content)`
- Inserts a new comment.
- DB default sets status to `pending`.

Used by:
- `CommentController::store()`.

## Inputs / Outputs contract
- Methods return arrays/bools.
