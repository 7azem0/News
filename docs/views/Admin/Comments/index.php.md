# View Page: `Admin/Comments/index.php`

## Source file
- `src/Views/Admin/Comments/index.php`

## Role of this view
Admin comment moderation table.

## Route / controller relationship
Route:
- `page=admin_comments` → `CommentController::admin_index()`

Authorization:
- `CommentController::ensureAdmin()`.

## Variables expected
- `$comments`: list from `Comment::getAllComments()`.

## Forms/endpoints
- Approve:
  - `POST index.php?page=admin_comment_approve` with `id`
- Flag:
  - `POST index.php?page=admin_comment_reject` with `id`
- Delete:
  - `POST index.php?page=admin_comment_delete` with `id`

## Side effects
- None directly.

## Notable caveats
- No CSRF tokens.
