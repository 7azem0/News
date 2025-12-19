# Service: `TranslationService`

## Source file
- `src/Services/Translation_S.php`

## Role of this service
`TranslationService` integrates with a LibreTranslate-compatible API to:
- translate short text snippets (`translateText`)
- translate full articles (`translateArticle`) with DB caching
- compute which languages are available per subscription plan (`getAvailableLangsForPlan`)

## Environment variables
- `LIBRE_TRANSLATE_URL`
  - If not set, defaults to `https://libretranslate.com/translate`.

## Tables used
- `translations` (cache)
- `articles` (source text)

## Who calls it
- `ArticleController` (list translations + single-article translations)
- `Translation_C.php` script

## Line-by-line (block-by-block)

### Lines 3–36: class state + DB connection + API URL
- Defines `$allLangs` language code → name map.
- Creates DB connection (`Database` class is assumed to be loaded elsewhere).
- Reads `LIBRE_TRANSLATE_URL` env var.

### Lines 39–103: `translateText(text, targetLang, sourceLang, plan)`
- Validates text not empty.
- Validates `targetLang` exists in `$allLangs`.
- Builds JSON request body:
  - `q`, `source`, `target`, `format=text`
  - optional `api_key` if `$this->apiKey` is set (note: current constructor does not set it).
- Uses cURL POST to `$apiUrl`.
- Validates:
  - no cURL error
  - HTTP code < 400
  - JSON is valid
  - no `error` in response
- Extracts translated text from `translatedText` (LibreTranslate) or `translated_text`.

### Lines 105–129: `getAvailableLangsForPlan(plan)`
- Maps plan names to allowed language sets:
  - `basic`: Arabic + English
  - `plus`: Arabic + several major languages + English
  - `pro` or `sunless`: all languages
  - default: English only

Relationship:
- Controllers use this to limit UI dropdown options.

### Lines 131–184: `translateArticle(articleId, targetLang, plan)`
- Computes allowed langs using `getAvailableLangsForPlan`.
- Checks DB cache (`translations` table) for `(article_id, language)`.
  - If cached JSON with `{title, content}`, returns it.
- Otherwise:
  - loads article title/content/language from `articles`.
  - maps article language (human name) to language code.
  - translates title and content via `translateText`.
  - stores translation JSON into `translations.translated_text` using upsert.

## Side effects
- External HTTP requests.
- Writes translation cache rows to DB.

## Inputs / Outputs contract
- `translateText` returns a string.
- `translateArticle` returns an array `['title' => ..., 'content' => ...]`.
