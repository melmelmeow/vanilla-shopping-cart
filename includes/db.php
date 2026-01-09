<?php
// DB config: prefer environment variables (works with Lando). Falls back to local defaults.
$DB_HOST = "database";
$DB_NAME = "lamp";
$DB_USER = "lamp";
$DB_PASS = "lamp";

// When running inside Lando the container host is usually "database" and Lando exposes a
// `LANDO` environment variable. If detected, prefer container host defaults unless overridden.
if (getenv('LANDO')) {
    $DB_HOST = getenv('DB_HOST') ?: 'database';
}

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die('Database connection error: ' . $e->getMessage());
}
?>