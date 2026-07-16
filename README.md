# Birthday Jedrick — Deploy Guide

This is a small PHP project (static front-end + `save_wish.php`) that saves wishes to a MySQL database (or a local `wishes.json` fallback if the DB is unavailable).

Quick local run (XAMPP):

1. Copy the project into your web root (e.g. `C:\xampp\htdocs\BORING`).
2. Start Apache and MySQL via the XAMPP Control Panel.
3. Import `wishes.sql` into phpMyAdmin (creates `birthday_jedrick.wishes`).
4. Open `http://localhost/BORING` in your browser.

Quick run with PHP built-in server:

```powershell
cd C:\xampp\htdocs\BORING
c:\xampp\php\php.exe -S 127.0.0.1:8000
# then open http://127.0.0.1:8000
```

Configuration:

- The app reads DB configuration from environment variables: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`.
- For local convenience there is a `.env.example` you can copy to `.env` and edit. The app will load `.env` automatically for local dev.

Deploying live:

- Shared PHP hosts (cPanel, 000webhost, InfinityFree): upload the repo contents to the public_html or provided folder, import `wishes.sql`, and set DB credentials in `db.php` or use an `.env` file if your host allows it.

- Render.com (recommended simple deploy):
  1. Create a new Web Service on Render, connect your GitHub repo.
  2. Set the `Start Command` to `php -S 0.0.0.0:10000` and set the `PORT` environment variable to `10000` (Render provides a port env automatically).
  3. In Render's Environment settings, set `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME` to your MySQL add-on details (or use an external managed DB).

Notes:

- GitHub Pages cannot run PHP. Use a PHP-capable host when you want server-side saves.
- The project writes `wishes.json` when the DB is unavailable — check that file if you expect saved wishes but don't see DB rows.

If you tell me which hosting provider you prefer, I can create step-by-step deployment instructions or a GitHub Action to deploy via FTP.
