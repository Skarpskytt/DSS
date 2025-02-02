<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

echo "Database Host: " . $_ENV['DB_HOST'] . "<br>";
echo "Database Name: " . $_ENV['DB_NAME'] . "<br>";
echo "Database User: " . $_ENV['DB_USER'] . "<br>";
?>
