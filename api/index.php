<?php

$publicPath = realpath(__DIR__ . '/../public');
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$file = realpath($publicPath . $uri);

// Layani file statis dari public/ kalau ada
if (
    $uri !== '/' &&
    $file &&
    str_starts_with($file, $publicPath) &&
    is_file($file)
) {
    $mime = mime_content_type($file) ?: 'application/octet-stream';
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

// Semua request lain masuk ke Laravel
require $publicPath . '/index.php';