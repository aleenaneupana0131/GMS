<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$dbname = 'assmt';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4");
    $pdo->exec("USE `$dbname` ");

    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        photo VARCHAR(255) DEFAULT 'default.png'
    ) ENGINE=InnoDB;");

    $checkAdmin = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($checkAdmin == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)")->execute([$hash]);
    }

    $uploadDir = __DIR__ . '/../public/uploads';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

} catch (PDOException $e) { die("Database Error: " . $e->getMessage()); }

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);