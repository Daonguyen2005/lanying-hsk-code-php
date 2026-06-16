<?php
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if (file_exists(__DIR__ . $path) && is_file(__DIR__ . $path)) {
    return false; // serve the requested resource as-is
}
$_GET['url'] = ltrim($path, '/');
require_once __DIR__ . '/index.php';
