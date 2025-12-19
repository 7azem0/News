# View Page: `Admin/Plans/form.php`

## Source file
- `src/Views/Admin/Plans/form.php`

## Role of this view
Admin create/edit subscription plan form.

## Route / controller relationship
Routes:
- `page=admin_plan_create` → `SubscriptionController::create_plan()`
- `page=admin_plan_edit&id=<id>` → `SubscriptionController::edit_plan()`

Form submissions:
- Create: `page=admin_plan_store` → `SubscriptionController::store_plan()`
- Update: `page=admin_plan_update` → `SubscriptionController::update_plan()`

## Variables expected
- `$plan` (optional): existing plan when editing

## Form fields
- `id` (hidden, edit only)
- `name`
- `price`
- `duration_days`
- `features`

Notes:
- Label says "Features (JSON or Comma Separated)" but code stores it as text.

## Side effects
- None.
