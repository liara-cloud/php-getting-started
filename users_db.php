<?php

// IN DEVELOPMENT MODE, USE THIS
$env = parse_ini_file('.env');
$servername = $env['DB_HOST']; 
$username   = $env['DB_USER'];
$password   = $env['DB_PASS'];
$dbname     = $env['DB_NAME'];
$port       = $env['DB_PORT'];

// // IN PRODUCTION MODE, USE THIS
// $servername = getenv('DB_HOST');
// $username   = getenv('DB_USER'); 
// $password   = getenv('DB_PASS'); 
// $dbname     = getenv('DB_NAME'); 
// $port       = getenv('DB_PORT'); 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

// Execute create table query
if ($conn->query($sql) === TRUE) {
    // echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
