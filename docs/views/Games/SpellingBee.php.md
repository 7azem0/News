# View Page: `Games/SpellingBee.php`

## Source file
- `src/Views/Games/SpellingBee.php`

## Role of this view
Implements a Spelling Bee-like word finding game.

Uses:
- shared layout header/footer
- client-side validation against a hardcoded list
- backend persistence endpoints

## Route / controller relationship
Route:
- `page=games&action=play&game=spellingbee` → `GamesController::play('spellingbee')`.

Persistence endpoints:
- Save: `POST index.php?page=games&action=save` with `gameId=spellingbee`
- Load: `GET index.php?page=games&action=load&gameId=spellingbee`

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Game state persisted
- `foundWords` array
- `score`

## Side effects
- Uses fetch calls to persist state.

## Notable caveats
- Game uses a hardcoded small solutions list; server does not validate words.
