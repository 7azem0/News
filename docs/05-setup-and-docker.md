# Setup & Docker

This chapter documents how to run the project locally using the provided Docker environment.

## What you need
- Docker Desktop (Windows/macOS) or Docker Engine (Linux)
- Docker Compose (usually included with Docker Desktop)

## Services defined in `docker-compose.yml`
The project defines three containers:

### 1) `php-apache` (the web app)
- **Image/build**: built from `Dockerfile` (`php:8.2-apache`)
- **Port mapping**: `8081:80`
  - Access the app at: `http://localhost:8081/`
- **Volume**: `./src:/var/www/html`
  - This mounts your local `src/` into the container so edits reflect immediately.

### 2) `db` (MySQL)
- **Image**: `mysql:8.0`
- **Port mapping**: `3307:3306`
  - Host port `3307` → container port `3306`
- **Volume**: `db_data:/var/lib/mysql` (persistent database storage)
- **Default credentials (from compose)**:
  - root password: `root`
  - database: `News`
  - user: `Rain`
  - password: `Sunny`

### 3) `libretranslate` (translation API)
- **Image**: `libretranslate/libretranslate:latest`
- **Port mapping**: `5000:5000`
- **Languages configured**: `LT_LOAD_ONLY=en,ar,fr,es,de,zh,ja,ko,ru,it,pt,hi,tr,nl,sv,fa`

## Step-by-step: run the project

### Step 1: Build and start containers
Use one of the following from the project root:
- `docker-compose up -d --build`
- `docker compose up -d --build`

### Step 2: Import the database
This project ships with `news_dump.sql`.

A typical import flow (as documented in the repository README) is:
1. `docker cp news_dump.sql mysql_db:/tmp/news_dump.sql`
2. `docker exec -it mysql_db bash`
3. `mysql -u root -p` (password: `root`)
4. `DROP DATABASE IF EXISTS News;`
5. `CREATE DATABASE News;`
6. `USE News;`
7. `SOURCE /tmp/news_dump.sql;`

## Environment variables actually used by the code
This matters because different parts of the app read different env var names.

### Database env vars used by `Database` (authoritative)
In `src/Config/DataBase_Connection.php`, the `Database` constructor reads:
- `DB_HOST`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

**Important mismatch**: `docker-compose.yml` sets `MYSQL_HOST`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DB` for the PHP container.
- These **are not the same variable names** as `DB_HOST` / `DB_USER` / etc.
- Result: unless you set `DB_*` env vars, the PHP code falls back to defaults (`db`, `News`, `Rain`, `Sunny`).
- Because the defaults match the compose `db` service, it often still works, but if you change credentials in compose you must update `DB_*` env vars or the PHP defaults.

### News API
In `src/Services/NewsAPI_S.php`:
- `NEWS_API_KEY` is read from environment (fallback to a hardcoded key).

### Translation API
In `src/Services/Translation_S.php`:
- `LIBRE_TRANSLATE_URL` is read from environment (fallback to `https://libretranslate.com/translate`).

## Dockerfile notes
The `Dockerfile`:
- enables `mysqli` and `pdo_mysql`
- copies `src/` into `/var/www/html/`

In practice, `docker-compose.yml` also mounts `./src` into `/var/www/html`, so local edits override the copied image files.

## Common startup checks
- If you get DB connection errors:
  - verify MySQL container is healthy and the credentials match `DB_*` env vars or the PHP defaults.
  - verify you imported `news_dump.sql`.
- If translation fails:
  - verify LibreTranslate container is running
  - verify `LIBRE_TRANSLATE_URL=http://libretranslate:5000/translate`.
- If NewsAPI calls fail:
  - verify `NEWS_API_KEY` is set and valid.
