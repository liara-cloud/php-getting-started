<?php
require 'vendor/autoload.php';

use Medoo\Medoo;

if (
    trim(getenv('DB_HOST')) !== "" &&
    trim(getenv('DB_NAME')) !== "" &&
    trim(getenv('DB_USER')) !== "" &&
    trim(getenv('DB_PASS')) !== ""
) {
    $database = new Medoo([
        'database_type' => 'mysql',
        'server' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'database_name' => getenv('DB_DATABASE'),
        'username' => getenv('DB_USERNAME'),
        'password' => getenv('DB_PASSWORD'),
        'charset' => 'utf8'
    ]);
} else {
    echo "Exited. Please add DB ENVs.";
    exit(0);
}
