<?php
session_start();
require_once __DIR__ . '/../config.php';
 // Ensure this points to your DB connection file
require_once '../models/Project.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $remarks = $_POST['remarks'];

    // Instantiate the Project model with the PDO connection
    $projectModel = new Project($pdo);

    // Update the project with new remarks
    try {
        $projectModel->addRemarks($projectId, $remarks);
        $_SESSION['success'] = "Remarks added successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to add remarks: " . $e->getMessage();
    }

    header("Location: ../views/mentor_dashboard.php?status=approved");
    exit();
}
