# Views Documentation

Views in `src/Views` are PHP templates that output HTML. They often:
- `include` shared layout partials (`Views/Layout/Header.php`, `Views/Layout/Footer.php`)
- assume certain variables exist (injected by controllers or defined inside the view)
- contain forms/links that target `index.php?page=...` routes

Documentation focus for views:
- variables expected/produced
- which controller/route renders the view
- layout/partial includes
- embedded JS endpoints (AJAX routes)

## Index (generated so far)
Layout:
- `Layout/Header.php.md`
- `Layout/Footer.php.md`

Pages:
- `Home.php.md`

Articles:
- `Articles/list.php.md`
- `Articles/Single.php.md`
- `Articles/news.php.md`
- `Articles/ForYou.php.md`
- `Articles/Search.php.md`

User:
- `User/Login.php.md`
- `User/Registeration.php.md`
- `User/ForgotPassword.php.md`
- `User/account.php.md`
- `User/Profile.php.md`

Admin:
- `Admin/Dashboard.php.md`
- `Admin/Users/index.php.md`
- `Admin/Articles/index.php.md`
- `Admin/Articles/form.php.md`
- `Admin/Plans/index.php.md`
- `Admin/Plans/form.php.md`
- `Admin/Subscriptions/assign.php.md`
- `Admin/Comments/index.php.md`

Subscription:
- `Subscription/Plans.php.md`

Briefing:
- `Briefing/Morning.php.md`
- `Briefing/LiveFeed.php.md`

Games:
- `Games/Index.php.md`
- `Games/Wordle.php.md`
- `Games/Connections.php.md`
- `Games/SpellingBee.php.md`

Issues:
- `Issues/List.php.md`

## Next views to document
Articles:
- `Articles/list.php`, `Articles/Single.php`, `Articles/news.php`, `Articles/ForYou.php`, `Articles/Search.php`

User:
- `User/Login.php`, `User/Registeration.php`, `User/ForgotPassword.php`, `User/account.php`, `User/Profile.php`

Admin:
- `Admin/Dashboard.php` and admin subviews

Subscription:
- `Subscription/Plans.php`

Briefing:
- `Briefing/Morning.php`, `Briefing/LiveFeed.php`

Games:
- `Games/Index.php`, `Games/Wordle.php`, `Games/Connections.php`, `Games/SpellingBee.php`
