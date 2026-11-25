<?php
// Enable MySQLi exceptions for clean error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$db   = 'ebrow';
$user = 'root';
$pass = '';

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Log the actual error internally; never expose credentials to users
    error_log($e->getMessage());
    exit('Database connection failed.');
}