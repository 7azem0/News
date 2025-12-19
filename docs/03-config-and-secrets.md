# Configuration & Secrets

## File: `src/Config/DataBase_Connection.php`

### Role of this file
Defines the `Database` class used by Models/Services to get a PDO connection.

### Who calls it
- Included by `src/index.php`.
- Required directly by models (e.g. `Models/User.php`).

### Line-by-line (block-by-block)

#### Lines 1–2: guard against redeclaring `Database`
- **Line 1**: starts PHP.
- **Line 2**: checks if `Database` class already exists before defining it.

#### Lines 3–15: class properties + constructor
- **Line 3**: starts class definition.
- **Lines 4–7**: define default DB host/name/user/pass.
  - Defaults target Docker compose (`host = "db"`).
- **Line 8**: `public $conn;` holds the PDO connection.
- **Lines 10–15**: constructor overrides defaults with environment variables:
  - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`

#### Lines 17–32: `connect()`
- **Line 17**: declares the method.
- **Line 18**: initializes `$this->conn`.
- **Lines 20–27**: attempts to create PDO with UTF-8 and enables exceptions.
- **Lines 28–30**: on failure, stops execution with `die(...)`.
- **Line 31**: returns PDO connection.

### Relationships (cross-references)
- Models typically do: `new Database(); $db->connect();`.

### Important notes
- Environment variable override is good for deployment.
- `die()` is acceptable for local dev but harsh for production; a global error handler would be more graceful.

---

## File: `src/Config/API.php`

### Role of this file
Intended to provide NewsAPI configuration (API key + base URL).

### What the code currently does (and why it matters)
- **Line 2** includes `__DIR__ . '/../config/API.php'`.
  - Because Windows paths are case-insensitive, `Config` vs `config` may resolve to the same folder.
  - This can accidentally include itself and cause recursion, depending on runtime/include_path.
- **Lines 6–9** return a hardcoded configuration array.

### Security note (critical)
The file contains a **hardcoded NewsAPI key**.
- This is a secret and should normally be provided via environment variables (or a private, untracked config file).
- For documentation, we treat this key as sensitive and recommend rotating it if it was committed publicly.

### Relationships (cross-references)
- `Services/NewsAPI_S.php` likely consumes this config (documented later).

---

## Recommended runtime configuration (documented behavior)
Use environment variables (preferred):
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `LIBRE_TRANSLATE_URL` (used by `TranslationService` in `Services/Translation_S.php`)

