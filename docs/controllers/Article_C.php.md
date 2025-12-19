# Controller: `ArticleController`

## Source file
- `src/Controllers/Article_C.php`

## Role of this controller
`ArticleController` is the main content controller for articles and news. It handles:
- listing local articles
- showing a single article (with translation, comments, like/save interactions, navigation)
- external NewsAPI headlines with DB fallback
- PDF “download” (HTML output) gated by subscription plan
- admin CRUD for articles (create/edit/delete)

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=article` → `ArticleController::index()`
- `page=news` → `ArticleController::news($_GET['country'] ?? 'us', $_GET['category'] ?? 'technology')`
- `page=article_download_pdf` → `ArticleController::downloadPdf()`

Admin routes:
- `page=admin_articles` → `ArticleController::admin_index()`
- `page=admin_article_create` → `ArticleController::create()`
- `page=admin_article_store` → `ArticleController::store()`
- `page=admin_article_edit` → `ArticleController::edit()`
- `page=admin_article_update` → `ArticleController::update()`
- `page=admin_article_delete` → `ArticleController::destroy()`

## Dependencies

### Required files (top of file)
- `src/Models/Article.php`
- `src/Services/NewsAPI_S.php`
- `src/Services/Translation_S.php`
- `src/Models/User.php`

### Additional required files (loaded inside methods)
- `src/Models/Subscription.php` (for plan checks and admin forms)
- `src/Models/Comment.php` (load approved comments)
- `src/Models/ArticleInteraction.php` (like/save + like count)
- `src/Services/PdfGenerator.php` (PDF HTML generation)

### Services used
- `NewsAPIService` (external news headlines)
- `TranslationService` (translate article content + translate news snippets)

### Session dependencies
- `$_SESSION['user_id']` (logged-in user id)
- `$_SESSION['is_admin']` (admin authorization)

### Views included
A constant is defined:
- `VIEWS_PATH = src/Views/Articles/`

User-facing views:
- `Views/Articles/list.php`
- `Views/Articles/news.php`
- `Views/Articles/Single.php`

Admin views:
- `Views/Admin/Articles/index.php`
- `Views/Admin/Articles/form.php`

## Line-by-line (block-by-block)

## File header / bootstrap
### Lines 1–4: session startup
- Starts PHP.
- Ensures session exists early. This is required because many code paths read `$_SESSION['user_id']`.

### Lines 6–12: dependencies + view path constant
- Loads models/services used throughout the controller.
- Defines `VIEWS_PATH` to avoid repeating the articles view folder path.

---

## Class: `ArticleController`
### Lines 13–25: properties + constructor
- Declares typed properties for core collaborators:
  - `$articleModel` (`Article`)
  - `$translator` (`TranslationService`)
  - `$userModel` (`User`)
  - `$newsService` (`NewsAPIService`)
- Constructor instantiates those collaborators per request.

---

## Method: `news($country, $category)`
### Lines 30–39: external news page with fallback
- **Line 31**: fetches from `NewsAPIService::fetch`.
- **Lines 33–36**: if API yields nothing, fallback to DB (`Article::fetchNews`).
- **Line 38**: includes `Views/Articles/news.php`.

Relationships:
- Route `page=news` provides `country` and `category` via query string.

---

## Method: `index()`
This method is dual-purpose:
- if `$_GET['id']` is missing → list page
- if `$_GET['id']` exists → single article page

### Lines 45–90: listing branch (no `id`)
#### Lines 46–49: fetch local articles + external news widget
- Loads local articles from DB.
- Also fetches news headlines from API.

#### Lines 50–67: determine language options based on subscription plan
- Determines selected language (`$_GET['lang']`, defaults to `en`).
- If user is logged in, fetches subscription from `User::getSubscription`.
- Calls `TranslationService::getAvailableLangsForPlan($plan)`.
- Ensures `en` is always available.

#### Lines 68–86: translate if requested
- If selected language is not English:
  - translate each article using `TranslationService::translateArticle(articleId, lang, plan)`
  - translate each news item title/description using `TranslationService::translateText`
- Exceptions are swallowed (fallback to original language).

#### Lines 88–90: render list
- Includes `Views/Articles/list.php`.
- `return` ensures the single-article branch is not executed.

Variables exported to the view scope:
- `$articles`, `$news`, `$selectedLang`, `$availableLangs`, `$plan` (and possibly `$subscription`).

### Lines 92–264: single article branch (`id` exists)

#### Lines 92–100: fetch article or 404
- Casts `$_GET['id']` to int.
- Loads article via `Article::getById`.
- Responds with 404 if missing.

#### Lines 102–144: subscription-based visibility checks
- Initializes access-control flags.
- If article `visibility` is `subscribed`:
  - if not logged in → deny
  - else load subscription and verify:
    - subscription exists
    - not expired
  - optional: if article requires a specific plan (`required_plan_id`), compare plan prices and deny if user plan is cheaper.

#### Lines 145–194: build `$displayArticle` and apply translation (if allowed)
- If access denied:
  - marks `is_blocked` and replaces article content with a warning HTML block + link to plans.
- If access granted:
  - builds language dropdown availability and determines the article’s original language code.
  - selects language from `$_GET['lang']` (defaults to original language).
  - translates only if user selects a different language than the original.
  - logs translation errors.

#### Lines 196–213: ensure language dropdown variables exist
- Defensive defaults:
  - if `$availableLangs` not set, fallback to English only.
- Builds (another) `$languageCodeMap` and computes `$articleLangCode`.
- Computes `$selectedLang` if missing.

Note:
- There are two language-code maps in this method; documentation preserves that behavior.

#### Lines 214–218: comments
- Loads `Comment` model.
- Fetches comments for article (`Comment::getByArticleId`).

#### Lines 219–235: like/save status + like count
- Initializes booleans and count.
- If logged in:
  - checks `isLiked`, `isSaved`, and `getLikeCount`.
- If not logged in:
  - still fetches like count.

#### Lines 236–245: PDF download eligibility
- Computes `$canDownloadPdf`.
- Requires user to be logged in and have a subscription.
- Loads `Subscription` model and checks plan price threshold (`>= 29.99`).

#### Lines 247–262: adjacent article navigation
- Computes `$userMaxPrice` (plan price) if user has an active subscription.
- Calls `Article::getAdjacentIds($id, $userMaxPrice)`.
- Exports `$prevArticleId` and `$nextArticleId`.

#### Line 263: render
- Includes `Views/Articles/Single.php`.

Variables exported to the view scope include:
- `$displayArticle`, `$article`, `$availableLangs`, `$selectedLang`, `$comments`
- `$isLiked`, `$isSaved`, `$likeCount`, `$canDownloadPdf`
- `$prevArticleId`, `$nextArticleId`

---

## Method: `downloadPdf()`
### Lines 266–315: subscription-gated PDF HTML output
- Ensures session.
- Redirects unauthenticated users to login.
- Fetches article by id.
- Verifies active subscription and plan price >= 29.99.
- Calls `PdfGenerator::generateArticlePdf($article)` and echoes HTML.

Relationship:
- Route `page=article_download_pdf` is typically linked from the single article page.

---

## Admin methods
### Lines 319–325: `ensureAdmin()`
- Starts session if needed.
- Requires `$_SESSION['is_admin'] == 1`.
- Uses `redirect('?page=Home')`.

Dependency note:
- `redirect()` is defined in `Core/Helpers.php`, which is included from `index.php`.

### Lines 327–331: `admin_index()`
- Lists articles for admin dashboard.

### Lines 333–341: `create()`
- Loads categories and subscription plans for form dropdowns.
- Includes admin form view.

### Lines 343–375: `store()`
- Reads `$_POST` as `$data`.
- Normalizes `scheduled_at`.
- Handles thumbnail upload into `Assets/Uploads/`.
- Calls `Article::save($data)`.
- On failure, re-renders form with error.

### Lines 377–393: `edit()`
- Loads article by id.
- Loads categories and plans.
- Includes admin form.

### Lines 395–431: `update()`
- Similar to `store()`, but keeps the existing image if no new upload.
- Calls `Article::save($data)` for update.

### Lines 433–438: `destroy()`
- Deletes article by id and redirects to admin articles.

## Side effects summary
- Reads/writes session.
- Sends redirects via `header()` and `redirect()`.
- Performs DB reads/writes through models.
- Performs external API calls (NewsAPI + translation).
- Handles file uploads (thumbnail) and writes to disk.

## Inputs / Outputs contract (summary)
- **Inputs**:
  - Query params: `id`, `lang`, `country`, `category`
  - Form posts: admin article create/update payload + `$_FILES['thumbnail']`
- **Outputs**:
  - HTML views (`list.php`, `news.php`, `Single.php`)
  - HTML output intended for PDF printing (via `downloadPdf()`)
  - Redirects for access control and admin actions
