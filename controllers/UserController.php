<?php
// controllers/UserController.php

require_once __DIR__ . '/../config.php'; // Include the PDO connection
require_once __DIR__ . '/../models/User.php'; // Include the User model
require_once __DIR__ . '/../models/Project.php'; // Include the Project model

class UserController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function login()
    {
        session_start(); // Start the session

        $userModel = new User($this->pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? ''; // Retrieve username
            $password = $_POST['password'] ?? ''; // Retrieve password

            $user = $userModel->validateUser($username, $password);

            if ($user) {
                // Successful login: set session variables
                $_SESSION['user'] = [
                    'id' => $user['user_id'],
                    'username' => $user['username'],
                    'role_owner' => $user['role_owner']
                ];

                // Update login info: last login time and IP address
                $lastLogin = date('Y-m-d H:i:s');
                $loginIp = $_SERVER['REMOTE_ADDR'];
                $userModel->updateLoginInfo($_SESSION['user']['id'], $lastLogin, $loginIp);
                $userModel->logAction($_SESSION['user']['id'], 'User logged in from IP: ' . $loginIp); // Log login action

                // Fetch projects for the logged-in user
                $projectModel = new Project($this->pdo);
                $projects = $projectModel->getProjects($_SESSION['user']['id']);
                $_SESSION['projects'] = $projects; // Store projects in session

                // If the user is a faculty, fetch their students and projects
                if ($_SESSION['user']['role_owner'] === 'faculty') {
                    $students = $projectModel->getStudentsByMentor($_SESSION['user']['id']);
                    $_SESSION['students'] = $students; // Store students (with projects) in session
                }

                // Redirect based on user role
                if ($_SESSION['user']['role_owner'] === 'faculty') {
                    header('Location: views/mentor_dashboard.php'); // Redirect to mentor dashboard
                } else {
                    header('Location: index.php?action=dashboard'); // Redirect to student dashboard
                }
                exit();
            } else {
                $errorMessage = "Invalid username or password."; // Handle error
            }
        }

        // Load the login view
        include __DIR__ . '/../views/login.php';
    }
}
?>
