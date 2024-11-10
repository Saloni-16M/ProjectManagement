<?php
class Feedback {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFeedback($project_id, $user_id, $comment, $remark_type) {
        $stmt = $this->pdo->prepare('INSERT INTO project_feedback (project_id, user_id, comment, remark_type) VALUES (?, ?, ?, ?)');
        $stmt->execute([$project_id, $user_id, $comment, $remark_type]);
    }
}
?>
