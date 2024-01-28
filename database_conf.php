<?php

// IN DEVELOPMENT MODE, USE --> $env 
// $env = parse_ini_file('.env');

$servername = getenv('DB_HOST'); // $env['DB_HOST']; 
$username   = getenv('DB_USER'); // $env['DB_USER'];
$password   = getenv('DB_PASS'); // $env['DB_PASS'];
$dbname     = getenv('DB_NAME'); // $env['DB_NAME'];
$port       = getenv('DB_PORT'); // $env['DB_PORT'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute create table query
if ($conn->query($sql) === TRUE) {
    // echo "Table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
?>
