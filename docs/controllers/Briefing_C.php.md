# Controller: `BriefingController`

## Source file
- `src/Controllers/Briefing_C.php`

## Role of this controller
`BriefingController` powers two briefing experiences:
- **Morning briefing**: curated local featured articles + global headlines
- **Live briefing**: continuously refreshed breaking news feed

It also exposes a JSON endpoint used for AJAX polling.

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=morning` → `BriefingController::morning()`
- `page=live` → `BriefingController::live()`
- `page=ajax_live` → `BriefingController::fetchLiveJson()`

## Dependencies
### Required files
- `src/Models/Article.php`
- `src/Services/NewsAPI_S.php`

### Model/service methods expected
- `Article::getFeatured($limit)`
- `NewsAPIService::getTopHeadlines($category, $country, $limit)`
- `NewsAPIService::getBreakingNews($limit)`

### Views included
- `src/Views/Briefing/Morning.php`
- `src/Views/Briefing/LiveFeed.php`

## Line-by-line (block-by-block)

### Lines 1–4: bootstrap
- Requires Article model and NewsAPI service.

### Lines 5–12: class state + constructor
- Declares `$articleModel` and `$newsService`.
- Constructor instantiates both.

### Lines 14–25: `morning()`
- Fetches:
  - `$featured`: top local featured articles (`getFeatured(3)`).
  - `$globalHeadlines`: external top headlines (`general`, `us`, limit 5).
- Includes the Morning briefing view.

Variables exported to the view:
- `$featured`, `$globalHeadlines`

### Lines 27–35: `live()`
- Fetches `$liveNews` from external API (limit 7).
- Includes `Views/Briefing/LiveFeed.php`.

Variables exported to the view:
- `$liveNews`

### Lines 37–50: `fetchLiveJson()` (AJAX endpoint)
- Sets JSON content type.
- In a try/catch:
  - fetches breaking news
  - returns JSON `{ success: true, news: [...] }`
- On exception:
  - returns HTTP 500 + JSON `{ success: false, error: "..." }`
- Always `exit`s.

## Relationships (cross-references)
- `page=ajax_live` is intended to be called by client-side JS on the Live briefing page.
- Both `live()` and `fetchLiveJson()` depend on the same service method (`getBreakingNews(7)`).

## Side effects
- External API calls to NewsAPI.
- JSON output for the AJAX endpoint.

## Inputs / Outputs contract
- **morning/live**: output HTML views.
- **fetchLiveJson**: outputs JSON and HTTP status codes.
