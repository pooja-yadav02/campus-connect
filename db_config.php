<?php
// db_config.php — Database Connection Configuration

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Change to your MySQL username
define('DB_PASS', '');            // Change to your MySQL password
define('DB_NAME', 'user_db');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die(json_encode([
        'status'  => 'error',
        'message' => 'Database connection failed: ' . mysqli_connect_error()
    ]));
}

mysqli_set_charset($conn, 'utf8mb4');
?>
