<?php
class Project
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function addProject($userId, $projectName, $description, $teamMembers)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO projects (user_id, project_title, project_description, team_members) VALUES (:user_id, :project_name, :description, :team_members)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':project_name', $projectName);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':team_members', $teamMembers); // Corrected this line
            return $stmt->execute(); // Returns true on success
        } catch (PDOException $e) {
            error_log("Failed to add project: " . $e->getMessage());
            return false;
        }
    }


    public function getProjects($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjectsForMentor($mentorId)
    {
        $stmt = $this->db->prepare("SELECT p.* FROM projects p 
                                     JOIN student_mentor sm ON p.user_id = sm.student_id 
                                     WHERE sm.mentor_id = :mentor_id");
        $stmt->bindParam(':mentor_id', $mentorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateProjectStatus($projectId, $status)
    {
        try {
            $stmt = $this->db->prepare("UPDATE projects SET status = :status WHERE project_id = :project_id");
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':project_id', $projectId);
            return $stmt->execute(); // Returns true on success
        } catch (PDOException $e) {
            error_log("Failed to update project status: " . $e->getMessage());
            return false;
        }
    }

    public function getStudentsByMentor($mentorId)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT u.user_id, u.username, 
                       p.project_id, p.project_title, p.project_description, 
                       p.team_members, p.status, p.locked_at, 
                       p.github_link, p.code_link, p.report_link, p.ppt_link,p.submission_status
                FROM users u
                JOIN student_mentor sm ON u.user_id = sm.student_id
                LEFT JOIN projects p ON u.user_id = p.user_id
                WHERE sm.mentor_id = :mentorId
            ");

            $stmt->execute(['mentorId' => $mentorId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Log the result
            error_log("Complete Query Result: " . print_r($result, true));

            $students = [];
            foreach ($result as $row) {
                $userId = $row['user_id'];
                if (!isset($students[$userId])) {
                    $students[$userId] = [
                        'user_id' => $userId,
                        'username' => $row['username'],
                        'projects' => []
                    ];
                }
                if ($row['project_id']) {
                    $students[$userId]['projects'][] = [
                        'project_id' => $row['project_id'],
                        'project_title' => $row['project_title'],
                        'project_description' => $row['project_description'],
                        'team_members' => $row['team_members'],
                        'status' => $row['status'],
                        'locked_at' => $row['locked_at'],
                        'github_link' => $row['github_link'],
                        'code_link' => $row['code_link'],
                        'ppt_link' => $row['ppt_link'],
                        'report_link' => $row['report_link'],
                        'submission_status' => isset($row['submission_status']) ? $row['submission_status'] : null
                    ];
                }
            }

            return $students;
        } catch (Exception $e) {
            error_log("Error fetching students and projects: " . $e->getMessage());
            return [];
        }
    }

    public function submitProjectFiles($projectId, $githubLink, $reportLink, $codeLink, $pptLink)
    {
        $sql = "UPDATE projects 
                SET github_link = :github_link, report_link = :report_link, code_link = :code_link, ppt_link = :ppt_link 
                WHERE project_id = :project_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':github_link', $githubLink);
        $stmt->bindParam(':report_link', $reportLink);
        $stmt->bindParam(':code_link', $codeLink);
        $stmt->bindParam(':ppt_link', $pptLink);
        $stmt->bindParam(':project_id', $projectId);
        return $stmt->execute();
    }
    public function updateSubmissionStatus($projectId, $status, $activated) {
        $query = "UPDATE projects SET submission_status = :status, submission_activated = :activated WHERE project_id = :project_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':activated', $activated);
        $stmt->bindParam(':project_id', $projectId);
    
        return $stmt->execute();
    }
    


   


    // public function updateProjectEvaluation($projectId, $remarks, $marks) {
    //     $stmt = $this->db->prepare("UPDATE projects SET remarks = ?, marks = ?, submission_status = 'evaluated' WHERE project_id = ?");
    //     return $stmt->execute([$remarks, $marks, $projectId]);
    // }
    // models/Project.php
public function updateProjectEvaluation($projectId, $remarks, $marks, $submissionActivated) {
    $sql = "UPDATE projects SET remarks = :remarks, marks = :marks, submission_activated = :submission_activated WHERE project_id = :project_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'remarks' => $remarks,
        'marks' => $marks,
        'submission_activated' => $submissionActivated,
        'project_id' => $projectId
    ]);
}
public function addRemarks($projectId, $remarks) {
    $sql = "UPDATE projects SET remarks = :remarks WHERE project_id = :project_id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['remarks' => $remarks, 'project_id' => $projectId]);
}
}

