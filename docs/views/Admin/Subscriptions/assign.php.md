# View Page: `Admin/Subscriptions/assign.php`

## Source file
- `src/Views/Admin/Subscriptions/assign.php`

## Role of this view
Admin page for manually assigning a subscription plan to a user.

## Route / controller relationship
Route:
- `page=admin_subscription_assign` → `SubscriptionController::assign()`

Form submission:
- `page=admin_subscription_store_assignment` → `SubscriptionController::store_assignment()`

Authorization:
- `SubscriptionController::ensureAdmin()`.

## Variables expected
- `$users`: list from `User::getAllUsers()`
- `$plans`: list from `Subscription::getAllPlans()`

## Form fields
- `user_id`
- `plan_id`
- `duration_days` (override)

## Side effects
- None directly.
