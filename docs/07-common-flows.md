# Common Flows

This chapter documents the most important user and admin flows end-to-end and how code modules interact.

## 1) Request routing flow (baseline)
1. Browser requests `src/index.php`.
2. `index.php` computes `$page` from `$_GET['page']` and calls `Router::route($page, $selectedLang)`.
3. `Router::route`:
   - starts session
   - checks authentication (public vs protected routes)
   - dispatches to controller methods or includes views.

Cross-reference:
- `docs/01-entry-and-routing.md`
- `docs/04-route-table.md`

## 2) Registration flow
Route:
- `page=register` → `UserController::register()`

Steps:
1. Controller calls `generateCsrfToken()` (stores token in session).
2. On POST:
   - verifies CSRF using `verifyCsrfToken($_POST['csrf_token'])`
   - sanitizes username/email
   - validates email format and password complexity
3. Password is hashed with `password_hash`.
4. `User::create(username, email, hashed)` inserts into `users`.
5. Redirect to login.

Key dependencies:
- `Core/Helpers.php` (CSRF + sanitize + redirect)
- `Models/User.php`

## 3) Login flow + rate limiting
Route:
- `page=login` → `UserController::login()`

Steps:
1. CSRF token generated.
2. On POST:
   - verifies CSRF
   - sanitizes email
   - obtains IP via `getIpAddress()`
3. Rate limiting:
   - `User::getRecentAttempts(email, ip)` counts attempts in last 15 minutes.
   - after 5 attempts, login is blocked.
4. `User::login(email, password)`:
   - loads DB row
   - verifies password
   - if suspended returns `'suspended'`
   - on success sets:
     - `$_SESSION['user_id']`
     - `$_SESSION['username']`
     - `$_SESSION['is_admin']`
5. On success: clear attempts and redirect home.

Router relationship:
- After login, `Router::route` sees `$_SESSION['user_id']` and allows protected pages.

## 4) Article listing flow
Route:
- `page=article` with no `id` → `ArticleController::index()` listing branch

Steps:
1. `Article::getAll()` loads published articles (id/title/thumbnail).
2. `NewsAPIService::fetch('us','technology')` loads external headlines for the page.
3. Language selection:
   - reads `$_GET['lang']` (defaults to `en`)
   - if logged in, loads subscription via `User::getSubscription` to compute available languages (`TranslationService::getAvailableLangsForPlan`).
4. If selected language != `en`, translates:
   - local articles via `TranslationService::translateArticle(articleId, lang, plan)` (cached in `translations`)
   - external headlines via `translateText`.
5. Renders `Views/Articles/list.php`.

## 5) Single article flow (gating + translation + interactions)
Route:
- `page=article&id=<id>` → `ArticleController::index()` single branch

Steps:
1. Loads article via `Article::getById(id)`.
2. Access control:
   - if `visibility=subscribed`:
     - require login
     - require active subscription (not expired)
     - if `required_plan_id` is set: compare required plan price to user plan price
3. If blocked:
   - creates `$displayArticle` with injected warning HTML
4. If allowed:
   - compute article original language code
   - allow translation if user selects different `lang`
5. Loads approved comments:
   - `Comment::getByArticleId(id)`
6. Loads like/save state + like count:
   - `ArticleInteraction::isLiked`, `isSaved`, `getLikeCount`
7. Computes `$canDownloadPdf` based on subscription plan price threshold (>= 29.99).
8. Computes previous/next navigation ids via `Article::getAdjacentIds(id, userMaxPrice)`.
9. Renders `Views/Articles/Single.php`.

## 6) Like/save interactions
Routes:
- `page=article_like` → `ArticleInteractionController::like()`
- `page=article_save` → `ArticleInteractionController::save()`

Steps:
1. Requires `$_SESSION['user_id']`.
2. Reads `$_POST['article_id']`.
3. Calls:
   - `ArticleInteraction::toggleLike(userId, articleId)` or
   - `ArticleInteraction::toggleSave(userId, articleId)`
4. Returns JSON.

## 7) Comments flow
Routes:
- User submits comment:
  - `page=comment_store` → `CommentController::store()`
- Admin moderation:
  - `page=admin_comments` → list
  - approve/reject/delete endpoints

Steps (user):
1. Requires login.
2. Validates POST content.
3. `Comment::add(userId, articleId, content)` inserts comment (default `pending`).
4. Redirects back to article.

Steps (admin):
- updates comment status to `approved` or `flagged`, or deletes.

## 8) Subscription flow
Routes:
- `page=plans` → view `Views/Subscription/Plans.php`
- `page=subscribe` → `SubscriptionController::subscribe()` JSON endpoint
- `page=cancel_subscription` → `SubscriptionController::cancel()`

Steps:
1. Plans page shows available plans (admin can manage via admin routes).
2. Subscribe endpoint:
   - validates login
   - validates plan exists
   - upserts into `subscriptions` with expiry = now + duration.
3. Cancel endpoint:
   - sets `expires_at = NOW()` and `auto_renew = 0`.

## 9) Games flow
Route:
- `page=games` → Router dispatches to:
  - hub: `GamesController::index()`
  - play: `GamesController::play(game)`
  - API endpoints: save/reset/load

Progress persistence:
- state is stored in `user_game_progress` as JSON text.
