# View Page: `User/Profile.php`

## Source file
- `src/Views/User/Profile.php`

## Role of this view
Displays a logged-in user’s profile dashboard with tabs:
- liked articles
- saved articles
- settings (inline profile/password update form)

This view contains substantial controller-like logic:
- it starts the session
- enforces authentication
- loads models and queries DB
- handles settings form submission

## Route / controller relationship
Route:
- `page=profile` → Router includes `Views/User/Profile.php` directly.

So there is **no controller** for this view; the view acts as its own controller.

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Dependencies (models used inside the view)
- `Models/User.php`
- `Models/ArticleInteraction.php`

## Variables and state
### Session
- Requires `$_SESSION['user_id']`.
- Updates `$_SESSION['username']` if profile changes are saved.

### Query params
- `tab` controls which tab is displayed:
  - `liked` (default)
  - `saved`
  - `settings`

### Data loaded by this view
- `$user` from `User::getUserById(user_id)`
- `$likedArticles` from `ArticleInteraction::getLikedArticles(user_id)`
- `$savedArticles` from `ArticleInteraction::getSavedArticles(user_id)`

## Endpoints/links
- Tab links:
  - `?page=profile&tab=liked`
  - `?page=profile&tab=saved`
  - `?page=profile&tab=settings`

- Article links:
  - `?page=article&id=<id>`

- Settings form posts to:
  - `?page=profile&tab=settings` (POST)

## Line-by-line (block-by-block)

### Lines 1–10: session + auth gate
- Starts session.
- If not logged in, redirects to `index.php?page=login`.

### Lines 12–23: model loading + DB queries
- Loads `User` and `ArticleInteraction` models.
- Fetches user record and liked/saved articles.
- Determines `$activeTab` from query string.

### Lines 24–25: layout include

### Lines 27–54: page header + tabs
- Shows welcome message using `$user['username']`.
- Renders tab navigation.

### Lines 55–93: liked tab
- If `$activeTab === 'liked'`:
  - renders grid of liked articles
  - uses `liked_at` timestamp
  - shows empty state CTA to browse articles

### Lines 95–133: saved tab
- If `$activeTab === 'saved'`:
  - renders saved articles grid
  - uses `saved_at` timestamp
  - shows empty state CTA

### Lines 135–262: settings tab
- If `$activeTab === 'settings'`:
  - defines `$error` and `$success`
  - on POST:
    - verifies `current_password` against DB hash
    - optionally updates username/email via `User::updateProfile`
    - optionally updates password via `User::updatePassword`
    - updates session username
    - refreshes `$user` after success
  - prints alerts
  - prints a settings form (profile + password + current password required)

### Lines 263–266: footer include

## Side effects
- Performs DB reads/writes directly.
- Updates session state.

## Notable caveats
- No CSRF protection on the settings POST.
- Duplicates logic that also exists in `UserController::account()`.
