# Controller: `GamesController`

## Source file
- `src/Controllers/Games_C.php`

## Role of this controller
`GamesController` provides:
- a Games hub page
- pages for specific games (Wordle / Connections / Spelling Bee)
- JSON endpoints for saving/resetting/loading per-user game progress

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=games` → `GamesController` with sub-routing using `$_GET['action']`:
  - no action → `index()`
  - `action=play&game=...` → `play($game)`
  - `action=save` → `saveProgress()`
  - `action=reset` → `resetProgress()`
  - `action=load` → `getProgress()`

## Dependencies
### Required files
- `src/Models/Game.php`

### Constants
- `GAMES_VIEWS_PATH = src/Views/Games/`

### Session dependencies
- `$_SESSION['user_id']` is required for progress save/reset/load.

### Views included
- `Views/Games/Index.php`
- `Views/Games/Wordle.php`
- `Views/Games/Connections.php`
- `Views/Games/Spellingbee.php` (case must match actual file)

### Model methods expected
- `Game::saveState($userId, $gameId, $stateJson, $score)`
- `Game::getState($userId, $gameId)`

## Line-by-line (block-by-block)

### Lines 1–9: bootstrap + constants
- Starts session.
- Requires Game model.
- Defines `GAMES_VIEWS_PATH` for reuse.

### Lines 10–16: class state + constructor
- Holds a `Game` model instance (`$gameModel`).
- Instantiates it in constructor.

### Lines 18–24: `index()` hub page
- Sets `$pageTitle` and includes `Views/Games/Index.php`.

### Lines 26–43: `play($gameName)`
- Implements a whitelist of allowed games to prevent arbitrary file inclusion.
- If allowed:
  - sets `$pageTitle`
  - sets `$currentGame`
  - includes a view file based on the game name.
- Else:
  - redirects back to the games hub.

Relationship:
- Router passes `$_GET['game']` into this method.

### Lines 45–71: `saveProgress()` JSON endpoint
- Returns JSON.
- Requires login.
- Reads JSON request body from `php://input`.
- Validates it contains `gameId` and `state`.
- Saves state via `Game::saveState`.

### Lines 73–100: `resetProgress()` JSON endpoint
- Same auth + JSON structure.
- Reads JSON body with `gameId`.
- Resets state by saving `null` JSON state and score 0.

### Lines 102–130: `getProgress()` JSON endpoint
- Requires login.
- Requires query param `gameId`.
- Fetches state via `Game::getState`.
- If found, decodes JSON state and returns it.
- Else returns success with `data: null`.

## Relationships (cross-references)
- The Router’s `page=games` case does the action multiplexing; this controller assumes that routing has already determined which method to call.

## Side effects
- Reads JSON body.
- Writes/reads progress from DB (via Game model).
- Includes views for game pages.

## Inputs / Outputs contract
- **play**: input `game` query param (whitelisted), outputs HTML view.
- **save/reset**: input JSON body, outputs JSON.
- **load**: input `gameId` query param, outputs JSON.
