# View Page: `Briefing/Morning.php`

## Source file
- `src/Views/Briefing/Morning.php`

## Role of this view
Renders the "The Morning" editorial briefing page:
- top featured local story
- global roundup list of external headlines

This is a standalone HTML document (does not use shared layout header/footer).

## Route / controller relationship
Route:
- `page=morning` → `BriefingController::morning()` includes this view.

## Variables expected (inputs)
From `BriefingController::morning()`:
- `$featured`: array of local featured articles (from `Article::getFeatured(3)`)
- `$globalHeadlines`: array of external headlines (from `NewsAPIService::getTopHeadlines(...)`)

Expected keys:
- For `$featured` items: `id`, `title`, `description`, `author`, `thumbnail`
- For `$globalHeadlines` items: `title`, `description`, `url`, `urlToImage`

## Links/routes referenced
- Back to home: `index.php?page=Home`
- Top story click: `?page=article&id=<id>`
- Global headlines open external URLs (`target="_blank"`).

## Line-by-line (block-by-block)

### Lines 1–52: document head + styling
- Full HTML document with inline editorial styling.

### Lines 54–76: header / hero
- Fixed back link.
- Editorial header with date and title.

### Lines 77–103: top story
- Uses `$featured[0]` if present.
- Clickable container navigates to article page.

### Lines 104–127: global roundup
- Iterates `$globalHeadlines`.
- Each item links to external news.

### Lines 129–136: footer quote

## Side effects
- None.
