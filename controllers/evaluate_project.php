// evaluate_project.php
<?php
// controllers/evaluate_project.php
session_start();
require_once __DIR__ . '/../config.php';

require_once '../models/Project.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $remarks = $_POST['remarks'];
    $marks = $_POST['marks'];
    $submissionActivated = isset($_POST['submission_activated']) ? 1 : 0;

    // Instantiate the Project model
    $projectModel = new Project($pdo);

    // Update the project evaluation
    try {
        $projectModel->updateProjectEvaluation($projectId, $remarks, $marks, $submissionActivated);
        $_SESSION['success'] = "Project evaluation submitted successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to evaluate project: " . $e->getMessage();
    }

    header("Location: ../views/mentor_dashboard.php?status=approved");
    exit();
}

?>
