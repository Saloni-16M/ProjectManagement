<?php
class User {
    private $db;

    public function __construct($pdo) {
        // Assign the PDO instance to the $db property
        $this->db = $pdo;
    }

    public function validateUser($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Verify password using MD5
        if ($user && md5($password) === $user['password']) {
            return $user; // Return the entire user record including user_id
        }
        return false;
    }
    
    public function getUserRoles($user_id) {
        // Fetch roles for the user
        $stmt = $this->db->prepare("SELECT roles.role_description FROM user_roles JOIN roles ON user_roles.role_id = roles.role_id WHERE user_roles.user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return all roles for the user
    }

    public function updateLoginInfo($userId, $lastLogin, $loginIp) {
        $stmt = $this->db->prepare("UPDATE users SET last_login = :last_login, login_ip = :login_ip WHERE user_id = :user_id");
        $stmt->bindParam(':last_login', $lastLogin);
        $stmt->bindParam(':login_ip', $loginIp);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute(); // Returns true on success
    }
    // models/User.php

public function updateLogoutInfo($userId, $lastLogout) {
    $stmt = $this->db->prepare("UPDATE users SET last_logout = :last_logout WHERE user_id = :user_id");
    $stmt->bindParam(':last_logout', $lastLogout);
    $stmt->bindParam(':user_id', $userId);
    return $stmt->execute(); // Returns true on success
}
// In models/User.php
// In models/User.php
public function logAction($userId, $action) {
    $stmt = $this->db->prepare("INSERT INTO audit_log (user_id, action, action_time) VALUES (:user_id, :action, NOW())");
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':action', $action);
    return $stmt->execute(); // Returns true on success
}


}
?>
