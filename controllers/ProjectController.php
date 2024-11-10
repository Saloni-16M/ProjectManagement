<?php
// controllers/ProjectController.php
// require_once './config.php'; // Make sure this includes session_start()
require_once './models/Project.php';
require_once __DIR__ . '/../config.php';
// session_start();
class ProjectController {
    public function add() {
        // Check if the user is logged in
        if (!isset($_SESSION['user']['id'])) {
            header('Location: index.php?action=login'); // Redirect to login if not logged in
            exit();
        }

        $userId = $_SESSION['user']['id']; // Get user ID from session

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projectName = trim($_POST['project_name']);
            $description = trim($_POST['description']);
            $teamMembers = trim($_POST['teamMembers']); // Ensure this matches the form input name

            if (empty($projectName) || empty($description) || empty($teamMembers)) {
                echo "Project name, description, and team members are required.";
            } else {
                $projectModel = new Project($GLOBALS['pdo']);

                if ($projectModel->addProject($userId, $projectName, $description, $teamMembers)) {
                    header('Location: index.php?action=dashboard'); // Redirect to dashboard
                    exit();
                } else {
                    echo "Failed to add project.";
                }
            }
        }

        // Include your add project view
        include 'views/project_form.php';
    }
}

