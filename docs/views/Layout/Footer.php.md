# View Partial: `Layout/Footer.php`

## Source file
- `src/Views/Layout/Footer.php`

## Role of this view
This is the closing layout partial. It:
- closes `<main>` opened by `Layout/Header.php`
- prints a simple footer with current year
- closes `</body>` and `</html>`

## Who includes it
Typically included at the bottom of page views, for example:
- `Views/Home.php`
- article pages (`Views/Articles/...`)

## Line-by-line (block-by-block)

### Lines 1–6: close document
- **Line 1**: closes `<main>`.
- **Lines 2–4**: footer markup and dynamic year via `date('Y')`.
- **Lines 5–6**: closes body and HTML tags.

## Output contract
- Must match a previously included `Layout/Header.php`.
