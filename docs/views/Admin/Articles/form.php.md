# View Page: `Admin/Articles/form.php`

## Source file
- `src/Views/Admin/Articles/form.php`

## Role of this view
Admin create/edit article form.

This view is used for both creating and editing articles.

## Route / controller relationship
Routes:
- `page=admin_article_create` → `ArticleController::create()`
- `page=admin_article_edit&id=<id>` → `ArticleController::edit()`

Form submissions:
- Create: `page=admin_article_store` → `ArticleController::store()`
- Update: `page=admin_article_update` → `ArticleController::update()`

Authorization:
- `ArticleController::ensureAdmin()` guards the controller methods.

## Variables expected (inputs)
- `$article` (optional): when editing
- `$categories` (array): from `Article::getAllCategories()`
- `$plans` (array): from `Subscription::getAllPlans()`
- `$error` (optional string): displayed when save fails

Session usage:
- Uses `$_SESSION['username']` as default author when creating.

## Form fields (important mapping)
Form posts `multipart/form-data`.

Fields include:
- `id` (hidden, edit only)
- `existing_image` (hidden, edit only)
- `title`
- `author`
- `language` (human-readable names)
- `category`
- `status` (`draft|published|archived`)
- `visibility` (`public|subscribed`)
- `required_plan_id` (only shown when visibility=subscribed)
- `scheduled_at` (datetime-local)
- `description` (textarea)
- `tags`
- `thumbnail` (file upload)
- `is_featured` (checkbox)

Important code relationship:
- `Article::save()` uses `description` as the source for DB column `content`.

## Embedded JS
- `togglePlanSelect(select)` shows/hides the plan dropdown based on visibility.

## Side effects
- None by itself; submit triggers controller which may upload a file and write DB.

## Notable caveats
- No CSRF token included.
- View uses `$_SESSION` directly without ensuring session is started here (it assumes controller/session already exists).
