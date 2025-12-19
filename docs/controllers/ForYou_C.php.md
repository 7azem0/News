# Controller: `ForYouController`

## Source file
- `src/Controllers/ForYou_C.php`

## Role of this controller
`ForYouController` renders the **For You** page, which shows:
- personalized recommendations (when a user is logged in)
- fallback latest articles (when no user is logged in)
- trending articles (based on likes)

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=for_you` → `ForYouController::index()`

## Dependencies
### Required files
- `src/Models/Article.php`
- `src/Models/User.php`

### Session dependencies
- `$_SESSION['user_id']`: determines whether personalization can happen.

### Model dependencies
This controller expects `Article` to implement:
- `Article::getRecommendations($userId, $limit)`
- `Article::getLatest($limit)`
- `Article::getTrendyArticles($limit)`

It also instantiates `User`, but in this controller version it does not directly call any `User` methods.

### View dependency
- `src/Views/Articles/ForYou.php`

## Line-by-line (block-by-block)

### Lines 1–4: bootstrap
- **Line 1**: enters PHP.
- **Line 2**: loads `Article` model.
- **Line 3**: loads `User` model.
- **Line 4**: blank separator.

### Lines 5–12: class state + constructor
- **Line 5**: defines `class ForYouController`.
- **Lines 6–7**: declare typed private properties:
  - `$articleModel` of type `Article`
  - `$userModel` of type `User`
- **Lines 9–12**: constructor instantiates both models.
  - This means each request that hits this controller creates new instances.

### Lines 14–37: main action (`index()`)
- **Lines 14–16**: docblock describing the page intent.
- **Line 17**: method declaration.

#### Lines 18–20: session safety
- Ensures session exists before reading `$_SESSION['user_id']`.

#### Lines 22–30: choose recommendation strategy
- **Line 22**: reads user id from session (or `null` if not logged in).
- **Lines 25–30**: builds `$recommendations`.
  - If `$userId` exists:
    - **Line 27** calls `Article::getRecommendations($userId, 6)`.
  - Else:
    - **Line 29** calls `Article::getLatest(6)`.

#### Lines 32–33: trending articles
- **Line 33** calls `Article::getTrendyArticles(6)`.

#### Lines 35–36: render
- Includes `Views/Articles/ForYou.php` and relies on it to read:
  - `$recommendations`
  - `$trendyArticles`

## Relationships (cross-references)
- Router auth gate: `page=for_you` is **not listed in `$publicPages`** in `Router::route`, meaning:
  - If the user is not logged in, Router normally redirects to login.
  - However, this controller *also* supports a non-logged-in fallback (`getLatest`).
  - If you want this page to actually be accessible to guests, you must add `for_you` to Router’s `$publicPages`.

## Side effects
- Includes a view which outputs HTML.

## Inputs / Outputs contract
- **Inputs**:
  - Optional: `$_SESSION['user_id']`.
- **Outputs**:
  - Always renders `Views/Articles/ForYou.php`.
  - Exposes `$recommendations` and `$trendyArticles` to the view scope.
