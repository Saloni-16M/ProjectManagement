<?php
$host = 'localhost';
$dbname = 'project_portal';
$user = 'root';
$pass = '';
$port = 3307;

try {
    // Include port in the DSN string
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
