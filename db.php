<?php
$host = 'localhost';
$db   = 'ecommerce';  // your database name
$user = 'root';       // your MySQL username
$pass = '';           // your MySQL password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Log the error instead of throwing a generic exception in production
     error_log("Database connection failed: " . $e->getMessage());
     die("Database connection failed. Please try again later.");
}
?>