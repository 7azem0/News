# Route Table (`page` → handler)

This table is derived from `src/Core/Router.php` (the `switch (strtolower($page))` statement).

## Public pages (no login required)
Defined in `Router::route` as:
- `""`
- `"index"`
- `"home"`
- `"login"`
- `"register"`
- `"check_username"`
- `"check_email"`
- `"forgot-password"`

If a request targets any other page without `$_SESSION['user_id']`, the router redirects to:
- `index.php?page=login&redirect=<requested_page>`

## Page routes

## Home
- **`page=""|"index"|"home"`**
  - **Handler**: include `src/Views/Home.php`

## Articles / News
- **`page="article"`**
  - **Handler**: `ArticleController::index()`
  - **Controller**: `src/Controllers/Article_C.php`
- **`page="article_download_pdf"`**
  - **Handler**: `ArticleController::downloadPdf()`
- **`page="news"`**
  - **Handler**: `ArticleController::news($_GET['country'] ?? 'us', $_GET['category'] ?? 'technology')`

## Search & personalization
- **`page="search"`**
  - **Handler**: `SearchController::index()`
- **`page="for_you"`**
  - **Handler**: `ForYouController::index()`
- **`page="morning"`**
  - **Handler**: `BriefingController::morning()`
- **`page="live"`**
  - **Handler**: `BriefingController::live()`
- **`page="ajax_live"`**
  - **Handler**: `BriefingController::fetchLiveJson()`

## User & auth
- **`page="login"`** → `UserController::login()`
- **`page="register"`** → `UserController::register()`
- **`page="check_username"`** → `UserController::ajax_check_username()` (JSON)
- **`page="check_email"`** → `UserController::ajax_check_email()` (JSON)
- **`page="forgot-password"`** → `UserController::forgotPassword()`
- **`page="account"`** → `UserController::account()`
- **`page="profile"`** → include `src/Views/User/Profile.php`
- **`page="logout"`** → `UserController::logout()`

## Admin panel
- **`page="admin"`** → `AdminController::index()`

### Admin: Articles CRUD
- `admin_articles` → `ArticleController::admin_index()`
- `admin_article_create` → `ArticleController::create()`
- `admin_article_store` → `ArticleController::store()`
- `admin_article_edit` → `ArticleController::edit()`
- `admin_article_update` → `ArticleController::update()`
- `admin_article_delete` → `ArticleController::destroy()`

### Admin: Users
- `admin_users` → `UserController::admin_index()`
- `admin_user_toggle` → `UserController::toggle_status()`
- `admin_user_promote` → `UserController::promote()`
- `admin_user_delete` → `UserController::destroy()`

### Admin: Plans/subscriptions
- `admin_plans` → `SubscriptionController::admin_plans()`
- `admin_plan_create` → `SubscriptionController::create_plan()`
- `admin_plan_store` → `SubscriptionController::store_plan()`
- `admin_plan_edit` → `SubscriptionController::edit_plan()`
- `admin_plan_update` → `SubscriptionController::update_plan()`
- `admin_plan_delete` → `SubscriptionController::destroy_plan()`
- `admin_subscription_assign` → `SubscriptionController::assign()`
- `admin_subscription_store_assignment` → `SubscriptionController::store_assignment()`

### Admin: Comments moderation
- `admin_comments` → `CommentController::admin_index()`
- `admin_comment_approve` → `CommentController::approve()`
- `admin_comment_reject` → `CommentController::reject()`
- `admin_comment_delete` → `CommentController::destroy()`

## Games
- **`page="games"`**
  - **Handler**: `GamesController` with sub-routing based on `$_GET['action']` and `$_GET['game']`.

## Comments & article interactions
- `comment_store` → `CommentController::store()`
- `article_like` → `ArticleInteractionController::like()`
- `article_save` → `ArticleInteractionController::save()`

## Subscription
- `subscribe` → `SubscriptionController::subscribe()`
- `cancel_subscription` → `SubscriptionController::cancel()`
- `plans` → include `src/Views/Subscription/Plans.php`

## Fallback
- Any other `page` value → HTTP 404 + `"Page not found"`
