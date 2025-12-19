# View Page: `Admin/Plans/index.php`

## Source file
- `src/Views/Admin/Plans/index.php`

## Role of this view
Admin subscription plan list page.

## Route / controller relationship
Route:
- `page=admin_plans` → `SubscriptionController::admin_plans()`

Authorization:
- `SubscriptionController::ensureAdmin()`.

## Variables expected
- `$plans`: array from `Subscription::getAllPlans()`.

## Links/forms
- Back to dashboard: `index.php?page=admin`
- Create plan: `index.php?page=admin_plan_create`
- Edit plan: `index.php?page=admin_plan_edit&id=<id>`
- Delete plan:
  - `POST index.php?page=admin_plan_delete`
  - field: `id`

## Side effects
- None.
