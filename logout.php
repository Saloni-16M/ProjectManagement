<?php
session_start();
require_once __DIR__ . '/config.php'; // Correct the path
require_once __DIR__ . '/models/User.php'; // Correct the path

$userModel = new User($pdo); // Create User object with the PDO instance

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    
    // Log logout action
    $userModel->logAction($userId, 'User logged out.');

    // Unset session and destroy it
    unset($_SESSION['user']);
    session_destroy();
    header('Location: index.php'); // Redirect to the login page
    exit(); // Stop execution
} else {
    header('Location: index.php'); // Redirect to login if no user is logged in
    exit();
}
?>
