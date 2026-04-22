<?php

$publicPath = realpath(__DIR__ . '/../public');
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$file = realpath($publicPath . $uri);

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

// Normalisasi agar Laravel tidak menganggap aplikasi berjalan di subpath /api
$_SERVER['SCRIPT_FILENAME'] = $publicPath . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = $publicPath;

require $publicPath . '/index.php';