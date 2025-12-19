# Controller: `SubscriptionController`

## Source file
- `src/Controllers/Subscription_C.php`

## Role of this controller
`SubscriptionController` handles:
- creating/updating a user’s subscription (JSON endpoint)
- canceling a subscription
- admin plan management (CRUD)
- admin manual subscription assignment to users

## Routes that reach this controller
From `src/Core/Router.php` (see `docs/04-route-table.md`):
- `page=subscribe` → `SubscriptionController::subscribe()`
- `page=cancel_subscription` → `SubscriptionController::cancel()`

Admin routes:
- `page=admin_plans` → `admin_plans()`
- `page=admin_plan_create` → `create_plan()`
- `page=admin_plan_store` → `store_plan()`
- `page=admin_plan_edit` → `edit_plan()`
- `page=admin_plan_update` → `update_plan()`
- `page=admin_plan_delete` → `destroy_plan()`
- `page=admin_subscription_assign` → `assign()`
- `page=admin_subscription_store_assignment` → `store_assignment()`

## Dependencies
### Required files
- `src/Models/User.php` (required at file load)

### Additional required files (loaded inside methods)
- `src/Models/Subscription.php`

### Implicit/global dependencies
This controller calls `redirect(...)` but does not `require` `Core/Helpers.php`.
- In normal app flow, `index.php` includes `Core/Helpers.php` before routing.

### Session dependencies
- `$_SESSION['user_id']`
- `$_SESSION['is_admin']`

### Views included
Admin views:
- `Views/Admin/Plans/index.php`
- `Views/Admin/Plans/form.php`
- `Views/Admin/Subscriptions/assign.php`

## Line-by-line (block-by-block)

## File header
### Lines 1–6: session + dependency
- Starts PHP.
- Starts session.
- Requires `Models/User.php`.

---

## Method: `subscribe()`
### Lines 9–66: JSON subscription creation/update

#### Lines 11–17: JSON response + authentication
- Forces JSON content type.
- Returns 401 JSON error if user not logged in.

#### Lines 19–23: method guard
- Only allows POST.
- Returns 405 JSON error for other methods.

#### Lines 25–36: validate plan
- Reads `plan_id` and `autoRenew` from POST.
- Loads `Subscription` model and fetches plan data.
- Returns 400 JSON error if plan invalid.

#### Lines 38–65: write subscription row
- Gets DB connection via `Database`.
- Uses `INSERT ... ON DUPLICATE KEY UPDATE` to upsert subscription for user.
- Uses plan duration days to compute `expires_at`.
- Returns JSON success or JSON 500 on DB error.

Relationships:
- This endpoint is likely called by JavaScript on the Plans page (`Views/Subscription/Plans.php`).

---

## Method: `cancel()`
### Lines 68–89: cancel subscription
- Ensures session.
- Redirects to login if not logged in.
- Updates subscription:
  - sets `auto_renew = 0`
  - sets `expires_at = NOW()` (immediate expiry)
- Redirects to `?page=plans`.

---

## Admin authorization helper
### Lines 93–99: `ensureAdmin()`
- Starts session.
- Requires `$_SESSION['is_admin'] == 1`.
- Redirects home if not admin.

---

## Admin: Plan management
### `admin_plans()` (lines 101–107)
- Loads all plans and includes admin listing view.

### `create_plan()` (lines 109–112)
- Includes form view for creating a plan.

### `store_plan()` (lines 114–128)
- Reads POST fields into `$data` and calls `Subscription::savePlan($data)`.
- Redirects to admin plans list.

### `edit_plan()` (lines 130–137)
- Loads plan by id and includes form view.

### `update_plan()` (lines 139–154)
- Reads POST, calls `savePlan` (update path), redirects.

### `destroy_plan()` (lines 156–163)
- Deletes plan by POST id, redirects.

---

## Admin: Manual assignment
### `assign()` (lines 167–179)
- Loads all users and all plans.
- Includes assignment view.

### `store_assignment()` (lines 181–211)
- Reads user id, plan id, duration.
- Fetches plan name (legacy `plan` column) from `Subscription` model.
- Upserts into `subscriptions` with a custom duration.
- Redirects back to admin users list.

## Side effects summary
- JSON output + HTTP status codes (`subscribe`).
- DB writes to `subscriptions`.
- Redirects for cancel/admin flows.

## Inputs / Outputs contract
- **subscribe**:
  - Input: POST `plan_id`, optional `autoRenew`
  - Output: JSON `{ success: true }` or JSON error
- **cancel**:
  - Input: session only
  - Output: redirect to plans
- **admin plan management**:
  - Input: POST plan fields
  - Output: redirects or admin views
