<?php
// Local-dev router for PHP's built-in server, mirroring this app's root-level
// front controller (index.php). Serves real static files directly and routes
// everything else through index.php. For local verification only.
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$path = __DIR__ . $uri;
if ($uri !== '/' && file_exists($path) && !is_dir($path)) {
    return false;
}
require __DIR__ . '/index.php';
