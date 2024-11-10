<?php
class ProjectFile {
    private $conn;
    private $table_name = "project_files";

    public $project_id;
    public $file_path;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function save() {
        $query = "INSERT INTO " . $this->table_name . " (project_id, file_path) VALUES (:project_id, :file_path)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':project_id', $this->project_id);
        $stmt->bindParam(':file_path', $this->file_path);

        return $stmt->execute();
    }
}
?>
