<?php
class ProjectStatus {
    private $conn;
    private $table_name = "project_statuses";

    public $project_id;
    public $remarks;
    public $approved;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (project_id, remarks, approved) VALUES (:project_id, :remarks, :approved)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':remarks', $this->remarks);
        $stmt->bindParam(':approved', $this->approved);

        return $stmt->execute();
    }
}
?>
