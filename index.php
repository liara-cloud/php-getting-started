<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function OpenConnection()
{
    $serverName = getenv('DB_HOST') . ',' . getenv('DB_PORT'); 
    $connectionOptions = array(
        "Database" => getenv('DB_DATABASE'), 
        "Uid" => getenv('DB_USERNAME'),      
        "PWD" => getenv('DB_PASSWORD'),      
        "Encrypt" => "yes",                  
        "TrustServerCertificate" => "yes"    
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    return $conn;
}

$conn = OpenConnection();
if ($conn) {
    echo "Connected successfully!";
} else {
    echo "Failed to connect!";
}
?>
