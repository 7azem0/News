# Controller: `ArticleInteractionController`

## Source file
- `src/Controllers/ArticleInteraction_C.php`

## Role of this controller
`ArticleInteractionController` provides **AJAX/POST endpoints** for:
- liking/unliking an article
- saving/unsaving an article

These endpoints return JSON responses that the frontend can use to update UI state without a full page reload.

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=article_like` → `ArticleInteractionController::like()`
- `page=article_save` → `ArticleInteractionController::save()`

## Dependencies
### Required files
- `src/Models/ArticleInteraction.php`

### Session dependencies
- `$_SESSION['user_id']` is required for both endpoints.

### Expected request type
- Both endpoints read `$_POST['article_id']`.

## Line-by-line (block-by-block)

### Lines 1–7: bootstrap
- **Lines 1–4**: start session if needed.
- **Line 6**: load `ArticleInteraction` model.

### Lines 8–34: `like()` endpoint
- **Lines 10–16**: authentication gate.
  - If `$_SESSION['user_id']` is missing: returns HTTP `401` and a JSON error.
- **Lines 18–24**: validate `article_id`.
  - If missing/empty: returns HTTP `400` and a JSON error.
- **Lines 26–33**: perform action and return JSON.
  - Instantiates model and calls `toggleLike(userId, articleId)`.
  - Returns JSON with:
    - `success: true`
    - `action` (e.g. like/unlike)
    - `count` (new like count)

Important note:
- This method echoes JSON but does **not** set `Content-Type: application/json`. If the frontend expects JSON strictly, consider adding that header (documenting current behavior only).

### Lines 36–59: `save()` endpoint
- Same structure as `like()`:
  - auth gate → validate `article_id` → call `toggleSave` → return JSON.
- Response includes:
  - `success: true`
  - `action`

## Relationships (cross-references)
- The single article page (`ArticleController::index()` single-article branch) checks:
  - `ArticleInteraction::isLiked(userId, articleId)`
  - `ArticleInteraction::isSaved(userId, articleId)`
  - `ArticleInteraction::getLikeCount(articleId)`

These endpoints are the write-side of the same feature.

## Side effects
- Updates DB rows related to likes/saves (inside `ArticleInteraction` model).
- Emits JSON + HTTP status codes.

## Inputs / Outputs contract
- **Input**: POST `article_id`, session `user_id`
- **Output**: JSON payload, plus `401` or `400` on error
