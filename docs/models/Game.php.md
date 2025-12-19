# Model: `Game`

## Source file
- `src/Models/Game.php`

## Role of this model
Stores per-user game progress.

## Table used
- `user_game_progress`

## Who calls it
- `GamesController`

## Line-by-line (block-by-block)

### Lines 1–10: bootstrap + connection
- Requires DB connection and opens PDO connection.

### Lines 12–36: `saveState($userId, $gameId, $state, $score)`
- Upserts into `user_game_progress` using `ON DUPLICATE KEY UPDATE`.
- Logs error and returns false on exception.

### Lines 38–49: `getState($userId, $gameId)`
- Reads `state` and `score`.
- Returns null on exception.

## Inputs / Outputs contract
- `state` is stored as JSON string by `GamesController`.
- `getState` returns associative array `{ state, score }`.
