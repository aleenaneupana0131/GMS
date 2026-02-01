<?php
// Database Configuration
$host = '127.0.0.1';
$db   = 'gym_db';
$user = 'root';     // Default XAMPP user
$pass = '';         // Default XAMPP password (leave empty)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>