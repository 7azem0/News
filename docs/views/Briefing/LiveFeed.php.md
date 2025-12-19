# View Page: `Briefing/LiveFeed.php`

## Source file
- `src/Views/Briefing/LiveFeed.php`

## Role of this view
Renders the live briefing feed with:
- initial breaking news items
- periodic AJAX polling to refresh the timeline

## Route / controller relationship
Routes:
- `page=live` → `BriefingController::live()` includes this view and provides initial `$liveNews`
- `page=ajax_live` → `BriefingController::fetchLiveJson()` returns JSON used by the polling script

## Layout includes
- Includes `Views/Layout/Header.php`
- Includes `Views/Layout/Footer.php`

## Variables expected (inputs)
From `BriefingController::live()`:
- `$liveNews`: array of external breaking news items.

Expected keys include:
- `url` (unique-ish id source)
- `publishedAt`
- `title`
- `description`
- `urlToImage` (optional)

## Embedded JS endpoints
- Polls: `fetch('index.php?page=ajax_live')` every 10 seconds.
- Expects JSON shape: `{ success: true, news: [...] }`.

## Line-by-line (block-by-block)

### Lines 1–45: header + initial timeline rendering
- Iterates `$liveNews` and renders timeline items.
- Uses `md5($item['url'])` for `data-id`.

### Lines 46–75: CSS
- Pulse indicator and highlight animation.

### Lines 76–166: polling script
- `fetchUpdates()` calls the JSON endpoint.
- `updateTimeline(news)`:
  - keeps only top 7
  - adds new entries by generating an id from URL and checking if it already exists in DOM
- `renderItem(item, id)` constructs and prepends a new timeline element.

### Line 168: footer include

## Side effects
- Client-side polling traffic.

## Notable caveats
- The initial DOM uses `md5(url)` as `data-id` but the JS uses `btoa(url)` to generate ids.
  - This means the JS may treat already-rendered items as “new” and duplicate them.
