<?php
define('DB_HOST', 'localhost'); define('DB_NAME', 'appinterco_shopping');
define('DB_USER', 'root'); define('DB_PASS', '');
// สำหรับ Gmail App Password
define('SMTP_HOST', 'smtp.gmail.com'); define('SMTP_USER', 'your@gmail.com');
define('SMTP_PASS', 'your-app-password'); define('SMTP_PORT', 587);

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) { die("Connection failed"); }

/*
// New Connect DB
<?php

session_start();
$host = 'localhost';
$db   = 'ecommerce_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>
*/

?>