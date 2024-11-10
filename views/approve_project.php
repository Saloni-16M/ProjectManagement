<?php
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['user']) || $_SESSION['user']['role_owner'] !== 'faculty') {
    header('Location: login.php');
    exit();
}

// Include your database connection
require_once __DIR__ . '/../config.php'; // Adjust the path as necessary

// Include your Project model
require_once __DIR__ . '/../models/Project.php';

$projectModel = new Project($pdo); // Pass the $pdo instance

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the project ID from the form submission
    $projectId = $_POST['project_id'];

    // Attempt to approve the project
    if ($projectModel->updateProjectStatus($projectId, 'approved')) {
        // Redirect back to the mentor dashboard with a success message
        header('Location: ./../index.php?action=mentor_dashboard&status=approved');
        exit();
    } else {
        // Handle failure to update the status
        $error = "Failed to approve the project.";
    }
}

// Handle any potential error messages to display on the dashboard
if (isset($error)) {
    $_SESSION['error'] = $error;
    header('Location: index.php?action=mentor_dashboard');
    exit();
}
?>
