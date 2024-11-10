<?php
class LoginLog {
    private $conn;
    private $table_name = "login_logs";

    public $user_id;
    public $ip_address;
    public $login_time;
    public $logout_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function logLogin() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, ip_address, login_time) VALUES (:user_id, :ip_address, NOW())";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':ip_address', $this->ip_address);
        $stmt->execute();
    }

    public function logLogout() {
        $query = "UPDATE " . $this->table_name . " SET logout_time = NOW() WHERE user_id = :user_id AND logout_time IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
    }
}
?>
