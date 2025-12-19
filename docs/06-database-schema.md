# Database Schema (from `news_dump.sql`)

This chapter maps the MySQL schema to the PHP code (Models/Controllers).

## Overview
Database name: `News`

Core tables:
- `users` (accounts)
- `articles` (local content)
- `comments` (user comments)
- `article_likes` (likes)
- `saved_articles` (save-for-later)
- `translations` (cached translations per article+language)
- `plans` (subscription plans)
- `subscriptions` (user subscriptions)
- `user_game_progress` (per-user game state)

Additional tables:
- `categories` (present but not populated by default; the app also derives categories from `articles.category`)
- `issues` (present; related UI may exist in `Views/Issues`)

## Table-by-table

## `users`
Columns:
- `id` (PK)
- `username` (unique)
- `email` (unique)
- `password` (hashed)
- `created_at`
- `is_admin` (0/1)
- `status` (`active`/`suspended`)

Relationships:
- `users.id` is referenced by:
  - `article_likes.user_id`
  - `saved_articles.user_id`
  - `comments.user_id`
  - `subscriptions.user_id`
  - `user_game_progress.user_id`

Code mapping:
- **Model**: `src/Models/User.php`
- **Controller**: `src/Controllers/User_C.php` (login/register/admin user management)

## `articles`
Columns (not exhaustive):
- `id` (PK)
- `title`, `content`
- `author`, `category`, `tags`
- `language`
- `thumbnail`
- `created_at`
- `status` (`draft`/`published`/`archived`)
- `scheduled_at`
- `is_featured`
- `visibility` (`public`/`subscribed`)
- `required_plan_id` (nullable)

Relationships:
- `articles.id` is referenced by:
  - `comments.article_id`
  - `article_likes.article_id`
  - `saved_articles.article_id`
  - `translations.article_id`

Code mapping:
- **Model**: `src/Models/Article.php`
- **Controller**: `src/Controllers/Article_C.php`
- **Views**:
  - `Views/Articles/list.php`, `Views/Articles/Single.php`, `Views/Articles/news.php`

Important behavior:
- Subscription gating happens based on:
  - `articles.visibility`
  - `articles.required_plan_id`

## `comments`
Columns:
- `id` (PK)
- `user_id` (FK → `users.id`)
- `article_id` (FK → `articles.id`)
- `content`
- `status` (`pending`/`approved`/`flagged`)
- `created_at`

Code mapping:
- **Model**: `src/Models/Comment.php`
- **Controller**: `src/Controllers/Comment_C.php`
- **Integration point**: `ArticleController::index()` loads approved comments for the single article page.

## `article_likes`
Columns:
- `id` (PK)
- `user_id` (FK)
- `article_id` (FK)
- `created_at`
Constraints:
- Unique `(user_id, article_id)`

Code mapping:
- **Model**: `src/Models/ArticleInteraction.php` (`toggleLike`, `isLiked`, `getLikeCount`)
- **Controller**: `src/Controllers/ArticleInteraction_C.php` (`page=article_like`)
- **Recommendation logic**: `Article::getRecommendations()` uses liked article categories/tags.

## `saved_articles`
Columns:
- `id` (PK)
- `user_id` (FK)
- `article_id` (FK)
- `saved_at`
Constraints:
- Unique `(user_id, article_id)`

Code mapping:
- **Model**: `src/Models/ArticleInteraction.php` (`toggleSave`, `isSaved`, `getSavedArticles`)
- **Controller**: `src/Controllers/ArticleInteraction_C.php` (`page=article_save`)

## `translations`
Columns:
- `id` (PK)
- `article_id` (FK)
- `language`
- `translated_text` (JSON string of `{ title, content }` in most cases)
- `created_at`
Constraints:
- Unique `(article_id, language)`

Code mapping:
- **Service**: `src/Services/Translation_S.php`
  - `translateArticle()` checks this table as a cache before calling the external API.

## `plans`
Columns:
- `id` (PK)
- `name`
- `price`
- `duration_days`
- `features`
- `created_at`
- `level` (present in DB, but current `Subscription` model does not manage it)

Code mapping:
- **Model**: `src/Models/Subscription.php` (plan CRUD)
- **Controller**: `src/Controllers/Subscription_C.php` (admin plan management)

## `subscriptions`
Columns:
- `id` (PK)
- `user_id` (FK)
- `plan` (legacy plan name string)
- `plan_id` (nullable)
- `auto_renew`
- `expires_at`

Code mapping:
- **Controller**: `SubscriptionController::subscribe()` performs an upsert into this table.
- **Model**: `User::getSubscription($userId)` reads active subscription.

Notes:
- Many rows exist in the dump; in practice you generally expect at most one active subscription per user.

## `user_game_progress`
Columns:
- `id` (PK)
- `user_id` (FK)
- `game_id` (string)
- `state` (JSON string)
- `score`
- `updated_at`
Constraints:
- Unique `(user_id, game_id)`

Code mapping:
- **Model**: `src/Models/Game.php` (`saveState`, `getState`)
- **Controller**: `src/Controllers/Games_C.php`

## Code/schema mismatches and caveats
- **Articles language**: DB column default is `'en'`, but code often treats language as human names like `'English'`.
- **Categories table** exists, but categories are also stored directly in `articles.category` and derived via `Article::getAllCategories()`.
- **Plans.level** exists in DB but is not fully used consistently; some access checks compare plan prices.
