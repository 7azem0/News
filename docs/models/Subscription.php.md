# Model: `Subscription`

## Source file
- `src/Models/Subscription.php`

## Role of this model
Encapsulates subscription plan (not subscription assignment) persistence:
- list plans
- get plan by id
- create/update plan
- delete plan

## Tables used
- `plans`

## Who calls it
- `SubscriptionController` (admin plan management)
- `ArticleController` (plan gating by required plan price)

## Line-by-line (block-by-block)

### Lines 1–10: bootstrap + connection
- Requires DB connection and opens PDO connection.

### Lines 12–17: `getAllPlans()`
- Returns all plans ordered by price.

### Lines 19–23: `getPlanById($id)`
- Fetches a single plan record.

### Lines 25–51: `savePlan($data)`
- If `id` is present, UPDATE.
- Else INSERT.

### Lines 53–56: `deletePlan($id)`
- Deletes plan.

## Notes
- The DB schema includes `plans.level`, but this model does not set it; `level` may remain default.
- Subscription assignment to users is done directly by controllers writing into the `subscriptions` table.
