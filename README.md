# Personal Task Manager (PHP + MySQL)

## Overview
Simple Personal Task Manager web app using PHP (PDO) and MySQL. Supports user registration/login, password reset tokens, task CRUD, filtering, sorting, and a modern responsive UI.

## Run with XAMPP (recommended)
1. Copy the project folder into `C:/xampp/htdocs/` (Windows) or the `htdocs` equivalent.
2. Create a MySQL database (e.g., `task_manager`) and import `init.sql` using phpMyAdmin or `mysql` CLI.
3. Copy `config.sample.php` to `config.php` and set your DB credentials and site URL.
4. Start Apache & MySQL in XAMPP and visit `http://localhost/task_manager/`.

## Run without XAMPP (built-in PHP server + SQLite fallback)
This project expects MySQL but includes instructions to use SQLite if you don't have MySQL.
- To use SQLite, copy `config.sample.php` to `config.php` and set `DB_ENGINE = 'sqlite'` and `SQLITE_PATH = __DIR__ . '/data/sqlite.db'`.
- Initialize DB: `php scripts/init_sqlite.php`
- Run built-in server: `php -S localhost:8000`
- Visit http://localhost:8000

## Files
- `index.php` - Dashboard / tasks UI (requires login)
- `login.php`, `register.php`, `logout.php`
- `reset_request.php`, `reset_password.php` - password reset flow (requires mail config)
- `api.php` - AJAX endpoints for tasks
- `db.php` - database connection (PDO)
- `config.sample.php` - sample config (copy to config.php)
- `init.sql` - SQL for MySQL initialization
- `assets/` - CSS and JS

## Email / Password Reset
Password reset uses PHP `mail()` by default. Configure SMTP or use an external SMTP mailer library (PHPMailer). For testing, you can view generated tokens in `password_resets` table.

## Security notes
- Passwords hashed with `password_hash()` (bcrypt).
- Prepared statements (PDO) used to prevent SQL injection.
- For production, force HTTPS and secure session settings.

Enjoy!
