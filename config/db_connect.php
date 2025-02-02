<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer dependencies

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load(); // Load environment variables

// Retrieve database credentials from .env
$host = $_ENV['DB_HOST'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage(), 3, __DIR__ . '/../logs/db_errors.log');
    die('Database connection failed. Please try again later.');
}
?>
