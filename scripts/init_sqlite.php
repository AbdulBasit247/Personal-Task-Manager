<?php
// initialize sqlite schema
$config = require __DIR__ . '/../config.php';
$path = $config['SQLITE_PATH'];
if (!file_exists(dirname($path))) mkdir(dirname($path), 0777, true);
$pdo = new PDO('sqlite:' . $path);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sql = file_get_contents(__DIR__ . '/../init.sql');
// Adjust MySQL-specific parts: remove AUTO_INCREMENT and ENUM convert to TEXT with defaults
$sql = str_replace('AUTO_INCREMENT', '', $sql);
$sql = preg_replace("/ENUM\('low','medium','high'\) DEFAULT 'medium'/", "TEXT DEFAULT 'medium'", $sql);
$pdo->exec($sql);
echo "SQLite DB initialized at $path\n";
