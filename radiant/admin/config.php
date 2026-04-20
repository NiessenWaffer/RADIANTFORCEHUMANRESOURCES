<?php
// Centralized database configuration for Admin Panel
$host = 'localhost';
$port = '3306';
$dbname = 'dbronnie';
$username = 'root';
$password = ''; // Laragon MySQL has no password by default

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    echo 'Database connection failed';
    exit;
}

// Database credentials configured for production use with dedicated MySQL user.
