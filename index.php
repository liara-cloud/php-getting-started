<?php

if (php_sapi_name() === 'cli') {
    echo "Exited. run with webserver (Apache, Nginx, ...) or php built-in webserver";
    exit(0);
}

session_start();
require_once 'config/db.php';

$routes = explode('?', $_SERVER['REQUEST_URI']);

switch ($routes[0]) {
    case '/':
        require_once('views/home.php');
        break;

    case '/about':
        require_once('views/about.php');
        break;

    default:
        header("HTTP/1.1 404 Not found");
        require_once('views/404.php');
        break;
}
