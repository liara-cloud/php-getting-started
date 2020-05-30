<?php
require 'vendor/autoload.php';

use Medoo\Medoo;
 
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => getenv('DB_NAME'),
    'port' => getenv('DB_PORT'),
    'server' => getenv('DB_HOST'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => 'utf8'
]);