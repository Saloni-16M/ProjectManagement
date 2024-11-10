<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php'); // Redirect if not logged in
    exit();
}

// Include your Project model
require_once __DIR__ . '/../models/Project.php';

$projectModel = new Project($GLOBALS['pdo']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = trim($_POST['project_name']);
    $description = trim($_POST['description']);
    $teamMembers = trim($_POST['teamMembers']); // Capture team members

    
    if (empty($projectName) || empty($description) || empty($teamMembers)) {
        $error = "All fields are required.";
    } else {
        if ($projectModel->addProject($_SESSION['user']['id'], $projectName, $description, $teamMembers)) {
            header('Location: index.php?action=dashboard');
            exit();
        } else {
            $error = "Failed to add the project.";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Add New Project</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?action=project_form">
            <div class="form-group">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control" id="project_name" name="project_name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="teamMembers">Team Members</label> <!-- Ensure the label matches the input name -->
                <textarea class="form-control" id="teamMembers" name="teamMembers" rows="2" required></textarea>
            </div>

          
            <button type="submit" class="btn btn-primary">Add Project</button>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>