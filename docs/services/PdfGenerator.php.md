# Service: `PdfGenerator`

## Source file
- `src/Services/PdfGenerator.php`

## Role of this service
`PdfGenerator` generates an **HTML document** suitable for printing/saving as PDF via the browser print dialog.

It does not generate a binary PDF server-side.

## Who calls it
- `ArticleController::downloadPdf()`

## Line-by-line (block-by-block)

### Lines 1–13: entry method
- Defines `PdfGenerator` class.
- `generateArticlePdf($article)` is a public static method:
  - calls `createHtmlContent($article)`
  - returns HTML string

### Lines 15–135: `createHtmlContent($article)`
- Escapes article fields with `htmlspecialchars`:
  - title, author, thumbnail
- Formats date using `date('F j, Y', ...)`.
- Converts newlines to `<br>` using `nl2br`.
- Builds a full HTML document string:
  - styles tuned for printing
  - optional thumbnail image block
  - footer
  - floating "print instructions" box
  - JS auto-calls `window.print()` after 500ms

## Side effects
- None (pure string generation).

## Inputs / Outputs contract
- Input: `$article` associative array (expected keys: `title`, `author`, `publishedAt`, `content`, `thumbnail`).
- Output: HTML string.
