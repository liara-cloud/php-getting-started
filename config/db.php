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
        'database_name' => getenv('DB_NAME'),
        'port' => getenv('DB_PORT'),
        'server' => getenv('DB_HOST'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'charset' => 'utf8'
    ]);
} else {
    echo "Exited. Please add DB ENVs.";
    exit(0);
}
