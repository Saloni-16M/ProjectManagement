<?php
// session_start(); // Ensure session is started at the beginning

require_once 'config.php'; // Ensure this file contains the PDO setup
require_once 'controllers/UserController.php';
require_once 'controllers/ProjectController.php';
require_once 'controllers/MentorController.php';

// Get the action from the URL, default to 'login'
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$userController = new UserController($pdo);
$projectController = new ProjectController($pdo);
$mentorController = new MentorController($pdo);

// Switch case to handle different actions
switch ($action) {
    case 'dashboard':
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login'); // Redirect to login if not logged in
            exit();
        }
        include 'views/dashboard.php'; // Include the dashboard view
        break;
    
    case 'login':
        $userController->login(); // Call the login method with the required $pdo
        break;
    
    case 'project_form':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login'); // Redirect to login if not logged in
            exit();
        }
        include 'views/project_form.php'; // Call the add method to show the project form
        break;

    case 'mentor_dashboard':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login'); // Redirect to login if not logged in
            exit();
        }
        include 'views/mentor_dashboard.php'; // Show the mentor dashboard
        break;
    
    case 'feedback':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login'); // Redirect to login if not logged in
            exit();
        }
        // $mentorController->feedback(); // Call the feedback method
        break;
    
    default:
        $userController->login(); // Default action is to show the login page
        break;
}
?>
