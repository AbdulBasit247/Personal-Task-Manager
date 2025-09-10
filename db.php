<?php
// db.php - returns $pdo
$config = require __DIR__ . '/config.php';

if ($config['DB_ENGINE'] === 'sqlite') {
    $path = $config['SQLITE_PATH'];
    if (!file_exists(dirname($path))) mkdir(dirname($path), 0777, true);
    $pdo = new PDO('sqlite:' . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} else {
    $dsn = "mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
return $pdo;
