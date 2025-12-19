# View Partial: `Layout/Header.php`

## Source file
- `src/Views/Layout/Header.php`

## Role of this view
This is a **layout header partial**. It:
- starts/ensures a PHP session exists
- determines login state and current username
- loads current subscription (DB query) when logged in
- outputs the HTML `<head>`, site header, navigation, and global JS for UI toggles
- opens the main page container (`<main>`) (closed by `Layout/Footer.php`)

## Who includes it
Typically included at the top of page views, for example:
- `Views/Home.php`
- article pages (`Views/Articles/...`)
- user pages (`Views/User/...`)

## Global state and dependencies

### Session dependencies
- Reads:
  - `$_SESSION['user_id']` → `$isLoggedIn`
  - `$_SESSION['username']` → `$username`
  - `$_SESSION['is_admin']` → shows admin link

### Model dependencies
- Requires `src/Models/User.php` if class `User` is not already loaded.
- When logged in, it creates `new User()` and calls:
  - `User::getSubscription($_SESSION['user_id'])`

### Query-string dependencies
- Uses `$_GET['page']` to set the `<body class="...">`.

### Routes referenced by links
- Home: `index.php?page=Home`
- Articles: `index.php?page=article`
- World News: `index.php?page=news`
- Games: `index.php?page=games`
- For You: `index.php?page=for_you`

Auth:
- Login: `index.php?page=Login`
- Profile: `?page=profile`
- Plans: `index.php?page=plans`
- Admin: `?page=admin`
- Logout: `?page=logout`

### Embedded JavaScript endpoints
- Subscription form submits to `fetch('?page=subscribe', { method: 'POST', body: formData })`
  - Cross-reference: `SubscriptionController::subscribe()` returns JSON.

- Search popover submits GET to `index.php?page=search&q=...`
  - Cross-reference: `SearchController::index()` renders search results.

## Line-by-line (block-by-block)

### Lines 1–18: PHP bootstrap and user context
- **Lines 1–4**: ensures `session_start()` has run.
- **Lines 6–8**: loads `User` model if missing.
- **Lines 10–17**: derives:
  - `$isLoggedIn`
  - `$username`
  - `$subscription` (loaded only when logged in)

Design implication:
- Every page that includes this header will run at least one DB query (`getSubscription`) when logged in.

### Lines 19–40: HTML document start + CSS includes
- Defines HTML5 skeleton (`<!DOCTYPE html>`, `<html>`, `<head>`, meta tags).
- Includes external Google Fonts.
- Includes CSS assets under `Assets/CSS/`.
- Opens `<body>` and sets its class to the current page name.

### Lines 41–95: site header markup + navigation
- Branding with title link to home.
- Displays today’s date via `date('l, F j, Y')`.
- Main nav links.
- Conditional account dropdown:
  - when logged in, shows subscription label and menu items.
  - shows admin panel link only for admin users.
  - when logged out, shows Log In link.
- Search UI:
  - a "Search" toggle opens a popover containing a GET form.

### Lines 96–273: inline JS for UI toggles
Contains three major JS behaviors:

1) Search popover toggle:
- toggles `#searchPopover` visibility
- focuses input when shown
- closes when clicking outside

2) Profile dropdown toggle:
- toggles `#profileMenu` on clicking `#profileToggle`

3) (Partially unused here) subscription toggle logic:
- defines `initSubscriptionToggle()` for elements like:
  - `#subscriptionBtn`, `#manageSubscriptionBtn`, `#subscriptionOptions`, `#subscriptionForm`, `#subscribeBtn`
- Note: in this file snapshot, `initSubscriptionToggle()` is defined but not invoked.

Global click handler closes open dropdowns.

### Lines 275–277: open main content area
- Outputs `<main>`.

## Variables this file defines (exported to the including view)
Because this file is `include`d, it executes in the caller’s scope and defines:
- `$isLoggedIn`
- `$username`
- `$subscription`

Page views can rely on these if needed.

## Output contract
- This partial outputs:
  - document `<head>`
  - header + nav
  - opens `<main>`
- It must be paired with `Layout/Footer.php` to close the HTML document.

## Notable caveats
- Path casing: this repo uses `Views/Layout/...`, but some pages include `layout/Header.php` (lowercase). This can fail on Linux.
