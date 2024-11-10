<?php
class ProjectMember {
    private $conn;
    private $table_name = "project_members";

    public $project_id;
    public $student_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (project_id, student_id) VALUES (:project_id, :student_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':student_id', $this->student_id);

        return $stmt->execute();
    }
}
?>
