<?php
$host = 'localhost'; // Local server
$user = 'root'; // Default user for XAMPP
$password = ''; // Password for MySQL (leave empty if not set)
$database = 'task_manager'; // Database name

try {
    // Establish connection to MySQL
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
