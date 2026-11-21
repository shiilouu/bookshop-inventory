<?php
/**
 * File:    config/db.php
 * Owner:   Lead Dev
 * Purpose: Shared MySQL connection used by every page.
 *
 * How to use (put this at the top of any page that needs the database):
 *     require_once __DIR__ . '/../config/db.php';        // from a root or 1-level folder file
 *     require_once __DIR__ . '/../../config/db.php';     // from modules/<folder>/ files
 *
 * Then use $conn with prepared statements, e.g.:
 *     $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
 *     $stmt->bind_param("i", $id);
 *     $stmt->execute();
 *
 * Real database details live in config/config.local.php (NOT uploaded to GitHub).
 */

$localConfig = __DIR__ . '/config.local.php';

if (!file_exists($localConfig)) {
    die('Missing config/config.local.php. Copy config/config.local.example.php, rename it to config.local.php, and fill in your database details.');
}

$db = require $localConfig;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name'], $db['port'] ?? 3306);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Database connection failed. Please check config/config.local.php.');
}
