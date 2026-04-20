<?php
// Centralized database configuration for Admin Panel
$host = 'localhost';
$port = '3306';
$dbname = 'dbronnie';
$username = 'root';
$password = 'Youth2025';

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Database credentials configured for production use with dedicated MySQL user.
