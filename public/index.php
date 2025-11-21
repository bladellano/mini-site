<?php

require_once __DIR__.'/../vendor/autoload.php';

$routes = require_once __DIR__.'/../routes/web.php';

$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

$base_path = dirname($script_name);

if ($base_path === '/') {
    $base_path = '';
}

$request_path = str_replace($base_path, '', $request_uri);
$request_path = parse_url($request_path, PHP_URL_PATH);


if (isset($routes[$request_path])) {
    $handler = $routes[$request_path];
    $controllerName = $handler[0];
    $methodName = $handler[1];

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    echo "404 Not Found";
}