<?php
$db_type = getenv('DB_TYPE') ?: 'mysql';
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: ($db_type === 'mysql' ? '3306' : '5432');
$dbname = getenv('DB_NAME') ?: 'misc';
$username = getenv('DB_USER') ?: 'fred';
$password = getenv('DB_PASSWORD') ?: 'zap';

try {
    if ($db_type === 'mysql') {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    } else {
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    }
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>