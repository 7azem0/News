# Model file: `Category.php` (placeholder)

## Source file
- `src/Models/Category.php`

## Current state
This file is empty (0 bytes).

## Implications
- The database contains a `categories` table, but current code derives categories primarily from `articles.category` and from a standard hardcoded list (see `Article::getAllCategories()`).

## Recommendation (documentation only)
If you later implement category CRUD or a normalized article-category relation, this file would be a natural place for that model.
