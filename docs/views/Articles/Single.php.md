# View Page: `Articles/Single.php`

## Source file
- `src/Views/Articles/Single.php`

## Role of this view
Renders the single-article reading experience, including:
- title/author/date header
- optional translation dropdown
- premium gating (blocked vs unblocked rendering)
- like/save buttons (AJAX)
- PDF download button (plan-gated)
- comments list + comment form
- listening mode (client-side TTS using Web Speech API)
- previous/next navigation for listening mode

## Route / controller relationship
Route:
- `page=article&id=<id>` → `ArticleController::index()` single-article branch

This view expects the controller to do all access control, translation selection, and data assembly.

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
### Core article data
- `$article`: original article DB row
- `$displayArticle`: a derived representation of the article for display:
  - may include `is_blocked` boolean
  - may include translated `title`/`content`

### Translation UI
- `$availableLangs`: map language code → label
- `$selectedLang`: selected language code

### Likes/saves
- `$isLiked` (bool)
- `$isSaved` (bool)
- `$likeCount` (int)

### PDF gating
- `$canDownloadPdf` (bool)

### Comments
- `$comments`: list of approved comments (each includes `username`, `created_at`, `content`)

### Navigation
- `$prevArticleId`, `$nextArticleId` (nullable ids)

### Session usage
- Checks `$_SESSION['user_id']` to show comment form vs login prompt.

## Routes / endpoints referenced
### Read/navigation
- Back to list: `?page=article`
- Translate dropdown submits GET to `?page=article&id=<id>&lang=<code>`

### Likes/saves (AJAX)
- POST `index.php?page=article_like` with body `article_id=<id>`
- POST `index.php?page=article_save` with body `article_id=<id>`

Cross-reference:
- `ArticleInteractionController::like()` / `save()`.

### Comments
- POST `index.php?page=comment_store` with fields:
  - `article_id`
  - `content`

Cross-reference:
- `CommentController::store()`.

### PDF download
- Link: `?page=article_download_pdf&id=<id>` opens in new tab.

Cross-reference:
- `ArticleController::downloadPdf()`.

## Line-by-line (block-by-block)

### Lines 1–61: header + title + translation + content
- Includes header.
- Prints title using `$displayArticle['title'] ?? $article['title']`.
- Shows LISTEN button only if not blocked.
- Shows translation dropdown only if not blocked and language variables exist.
- Shows thumbnail image if present and not blocked.
- Shows content:
  - if blocked: prints raw `$displayArticle['content']` (contains prebuilt HTML warning)
  - else: escapes and `nl2br` for safe rendering

### Lines 63–235: like/save + PDF download UI + AJAX JS
- Like button:
  - uses `data-article-id` and `data-liked`
  - updates SVG + count on success
- Save button:
  - uses `data-saved`
  - toggles label and icon color
- PDF button:
  - shown only when `$canDownloadPdf` is true
  - otherwise, if logged in, shows upgrade prompt

### Lines 239–309: comments section
- Shows list of approved comments.
- If logged in:
  - shows success/error banners based on query params (`comment=success`, `error=empty`)
  - shows POST form to `page=comment_store`
- If not logged in:
  - shows login prompt

### Lines 319–548: listening mode (client-side)
- Hidden player UI expands when LISTEN button pressed.
- Uses `window.speechSynthesis` and `SpeechSynthesisUtterance`.
- Supports:
  - play/pause
  - speed selection
  - previous/next article navigation using `prevArticleId`/`nextArticleId` and adds `autoplay=true`
  - language mapping based on `$selectedLang` to match TTS voice language

Important detail:
- The listening mode does not call server-side `TTSService`; it is entirely browser-side.

## Side effects
- Client-side network requests (AJAX likes/saves) and form posts.
- Client-side TTS playback.

## Common pitfalls
- If JSON endpoints return non-JSON (e.g. due to PHP warnings), the JS `.json()` parse will fail.
- Language voice availability depends on the browser and installed voices.
