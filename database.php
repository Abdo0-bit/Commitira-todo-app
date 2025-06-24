<?php
$host = 'localhost';
$db = 'YOUR_DB_NAME';
$username = 'YOUR_DB_USERNAME';
$password = 'YOUR_DB_PASSWORD';

try {
    $conn = mysqli_connect($host, $username, $password, $db);
} catch (mysqli_sql_exception $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>