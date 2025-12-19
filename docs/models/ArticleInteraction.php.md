# Model: `ArticleInteraction`

## Source file
- `src/Models/ArticleInteraction.php`

## Role of this model
Encapsulates persistence for:
- article likes
- saved articles

It provides both read operations (isLiked/isSaved/count lists) and toggle operations used by AJAX controllers.

## Tables used
- `article_likes`
- `saved_articles`

## Who calls it
- `ArticleInteractionController` for toggles
- `ArticleController` for displaying like/save state and like counts

## Line-by-line (block-by-block)

### Lines 1–10: bootstrap + connection
- Requires DB connection and opens PDO connection.

## Likes
### Lines 14–27: `toggleLike($userId, $articleId)`
- If already liked, delete like row.
- Else insert like row.
- Returns action string plus new like count.

### Lines 29–33: `isLiked(...)`
- Checks existence of a like row.

### Lines 35–40: `getLikeCount($articleId)`
- Returns `COUNT(*)` for an article.

### Lines 42–52: `getLikedArticles($userId)`
- Returns article rows joined with `article_likes.created_at`.

## Saves
### Lines 56–69: `toggleSave($userId, $articleId)`
- If saved, delete row.
- Else insert row.
- Returns action string.

### Lines 71–75: `isSaved(...)`
- Checks existence in `saved_articles`.

### Lines 77–87: `getSavedArticles($userId)`
- Returns articles joined with `saved_articles.saved_at`.

## Inputs / Outputs contract
- Toggle methods return arrays describing the action.
- Read/list methods return bools/ints/arrays.
