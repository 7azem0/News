# View Page: `Games/Connections.php`

## Source file
- `src/Views/Games/Connections.php`

## Role of this view
Implements a Connections-like word grouping game in the browser.

Includes game logic and uses backend persistence endpoints.

## Route / controller relationship
Route:
- `page=games&action=play&game=connections` → `GamesController::play('connections')`

Persistence endpoints:
- Save: `POST index.php?page=games&action=save` with `gameId=connections`
- Load: `GET index.php?page=games&action=load&gameId=connections`
- Reset: `POST index.php?page=games&action=reset`

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected
- None.

## Game state persisted
- `solvedGroupIds`
- `mistakes`
- score is derived from number of solved groups.

## Side effects
- Uses `fetch` to save/load/reset game state.

## Notable caveats
- `markSolved(group)` both mutates in-memory state and also renders UI; during `loadProgress`, it calls `markSolved` for each previously solved group which may reorder UI compared to original play.
