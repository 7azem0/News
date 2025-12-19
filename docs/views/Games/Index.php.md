# View Page: `Games/Index.php`

## Source file
- `src/Views/Games/Index.php`

## Role of this view
Games hub page that lists available mini-games and links into the `page=games&action=play&game=...` routes.

This is a standalone HTML document (does not include shared layout).

## Route / controller relationship
Route:
- `page=games` with no `action` → `GamesController::index()` includes this view.

## Variables expected
- None.

## Links/routes referenced
- Back to News: `index.php`
- Play Word Guess: `index.php?page=games&action=play&game=wordle`
- Play Connections: `index.php?page=games&action=play&game=connections`
- Play Spelling Bee: `index.php?page=games&action=play&game=spellingbee`

## Side effects
- None.
