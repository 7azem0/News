# View Page: `Subscription/Plans.php`

## Source file
- `src/Views/Subscription/Plans.php`

## Role of this view
Displays the subscription plans UI for logged-in users:
- shows the user’s current plan (if active)
- lists all available plans
- allows switching plans / updating auto-renew
- allows cancelling subscription

This file contains significant controller-like logic (it loads models and performs DB reads).

## Route / controller relationship
Route:
- `page=plans` → Router includes `Views/Subscription/Plans.php` directly.

So there is **no controller** for this view; the view acts as a controller.

## Layout / document structure
This view is inconsistent about layout usage:
- **Line 1**: attempts to include a layout header (`Layout/Header.php`) but the include path is malformed.
- It then outputs a full standalone `<!DOCTYPE html> ... </html>` document.

Implication:
- Depending on PHP include resolution, the page may end up with duplicated `<html>` / `<head>` tags or may fail to include header.

## Authentication behavior
- If `$_SESSION['user_id']` is missing:
  - redirects to `index.php?page=Login`

## Dependencies (models used inside the view)
- `Models/User.php` (to get current subscription)
- `Models/Subscription.php` (to list plans)

## Variables/state produced by this view
- `$subscription`: from `User::getSubscription(userId)`
- `$allPlans`: from `Subscription::getAllPlans()`
- `$isActive`: computed by checking `expires_at > now`

## Forms and endpoints
### Subscribe/update plan
- Form id `subscriptionForm`
- `method="POST"`
- `action="index.php?page=subscribe"`
- Fields:
  - `plan_id` (radio)
  - `autoRenew` (checkbox)

JS intercepts submission and sends via fetch:
- `POST index.php?page=subscribe` (FormData)
- expects JSON `{ success: true }` or `{ error: ... }`

Cross-reference:
- `SubscriptionController::subscribe()`.

### Cancel subscription
- `POST index.php?page=cancel_subscription`
- confirmation prompt

Cross-reference:
- `SubscriptionController::cancel()`.

## Embedded JavaScript
The script includes:
- a search toggle popover handler (expects `.search-toggle` and `#searchPopover` from the shared header)
- `initSubscriptionForm()` for AJAX form submission
- plan selection UI (`.selected` class toggling)

## Line-by-line (block-by-block)

### Lines 1–20: header include attempt + model loading + auth gate
- Includes header using `include(__DIR__ . '../../Layout/Header.php')`.
  - This path is missing a slash (`/`) between `__DIR__` and `../../...`.
- Loads models.
- Enforces login.
- Loads current subscription and plans.

### Lines 22–276: HTML page rendering
- Renders current plan banner when `$isActive`.
- Renders plan cards with radio inputs.
- Renders auto-renew checkbox.
- Renders subscribe button.
- Renders cancel subscription form when active.

### Lines 277–412: JS behavior
- Intercepts subscribe form submit and uses fetch.
- Applies plan selection highlighting.

## Side effects
- Performs DB reads directly in the view.
- Performs fetch requests to subscription endpoint.

## Notable caveats
- No CSRF protection on subscribe/cancel forms.
- Layout include path and duplicated HTML document structure may cause rendering issues.
