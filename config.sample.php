<?php
// Copy this file to config.php and edit
return [
    'DB_ENGINE' => 'mysql', // 'mysql' or 'sqlite'
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'task_manager',
    'DB_USER' => 'root',
    'DB_PASS' => '',
    'SQLITE_PATH' => __DIR__ . '/data/sqlite.db',
    'SITE_URL' => 'http://localhost/task_manager', // change when running in built-in server: http://localhost:8000
    // Mail config: if empty, password reset will store tokens in DB and show message (useful for testing)
    'MAIL_FROM' => '',
];
