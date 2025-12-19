# View Page: `Games/Wordle.php`

## Source file
- `src/Views/Games/Wordle.php`

## Role of this view
Implements a Wordle-like game in the browser.

It uses:
- shared layout header/footer
- client-side game logic
- backend persistence via the `GamesController` JSON endpoints

## Route / controller relationship
Route:
- `page=games&action=play&game=wordle` → `GamesController::play('wordle')` includes this view.

Persistence endpoints:
- Save: `POST index.php?page=games&action=save`
- Load: `GET index.php?page=games&action=load&gameId=wordle`
- Reset: `POST index.php?page=games&action=reset`

Cross-reference:
- `GamesController::{saveProgress,getProgress,resetProgress}`
- `Game` model writes to `user_game_progress`.

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Embedded game logic (high level)
- Hardcoded solution: `WORLD`.
- Uses a 6x5 grid.
- Color states: correct/present/absent.

## Side effects
- Uses fetch to persist state to server.
- Uses `navigator.clipboard` to copy share text.

## Notable caveats
- Persistence requires login; `GamesController` will return unauthorized JSON if user isn’t logged in.
