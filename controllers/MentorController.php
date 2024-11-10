<?php
// session_start();


// Include your database connection and model
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Project.php';

class MentorController
{
    private $pdo;
    private $projectModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->projectModel = new Project($pdo);
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role_owner'] !== 'faculty') {
            header('Location: login.php');
            exit();
        }

        $mentorId =(int) $_SESSION['user']['id'];
        error_log("Using Mentor ID: $mentorId");
        error_log("Fetching students for Mentor ID: $mentorId");

        // $students = $this->projectModel->getStudentsByMentor($mentorId);
        // error_log("Fetched Students: " . print_r($students, true));

        include __DIR__ . '/../views/mentor_dashboard.php';
    }
}
