<?php
session_start();
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Project.php';

$projectModel = new Project($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $githubLink = $_POST['github_link'];
    
    $reportDir = __DIR__ . '/../uploads/reports/';
    $codeDir = __DIR__ . '/../uploads/codes/';
    $pptDir = __DIR__ . '/../uploads/presentations/';
    
    if (!is_dir($reportDir)) mkdir($reportDir, 0777, true);
    if (!is_dir($codeDir)) mkdir($codeDir, 0777, true);
    if (!is_dir($pptDir)) mkdir($pptDir, 0777, true);

    $reportPath = $reportDir . basename($_FILES['report_link']['name']);
    $codePath = $codeDir . basename($_FILES['code_link']['name']);
    $pptPath = $pptDir . basename($_FILES['ppt_link']['name']);

    if (move_uploaded_file($_FILES['report_link']['tmp_name'], $reportPath) &&
        move_uploaded_file($_FILES['code_link']['tmp_name'], $codePath) &&
        move_uploaded_file($_FILES['ppt_link']['tmp_name'], $pptPath)) {
        
        // Here, update the submission status and activated flag in the database
        if ($projectModel->submitProjectFiles($projectId, $githubLink, $reportPath, $codePath, $pptPath)) {
            // Update submission status to 'submitted' and set submission_activated to 0
            $projectModel->updateSubmissionStatus($projectId, 'submitted', 0); // Assume this function now takes the status and activated flag

            $_SESSION['success'] = 'Project files submitted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to save project files. Please try again.';
        }
    } else {
        $_SESSION['error'] = 'File upload failed. Please check the files and try again.';
    }

    header('Location: dashboard.php');
    exit();
} else {
    header('Location: dashboard.php');
    exit();
}
?>
