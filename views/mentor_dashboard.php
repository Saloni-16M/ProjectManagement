<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role_owner'] !== 'faculty') {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

// Initialize $students to avoid undefined variable error
$students = isset($_SESSION['students']) ? $_SESSION['students'] : []; // Get students from session

// Debug: Check the session data for students
error_log("Students in session: " . print_r($_SESSION['students'], true));

if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error']);
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['status']) && $_GET['status'] === 'approved'): ?>
    <div class="alert alert-success">
        Project approved successfully!
    </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h1>Your Students</h1>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <h2><?php echo htmlspecialchars($student['username']); ?>'s Projects:</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Project Title</th>
                            <th>Project Description</th>
                            <th>Team Members</th>
                            <th>Status</th>
                            <th>Locked At</th>
                            <th>GitHub Link</th>
                            <th>Report PDF</th>
                            <th>Code PDF</th>
                            <th>Submission Type</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($student['projects'])): ?>
                            <?php foreach ($student['projects'] as $project): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($project['project_title']); ?></td>
                                    <td><?php echo htmlspecialchars($project['project_description']); ?></td>
                                    <td><?php echo htmlspecialchars($project['team_members']); ?></td>
                                    <td><?php echo htmlspecialchars($project['status'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($project['locked_at'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if (!empty($project['github_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($project['github_link']); ?>" target="_blank" class="btn btn-link">View GitHub</a>
                                        <?php else: ?>
                                            <span class="text-danger">No GitHub link</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($project['report_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($project['report_link']); ?>" target="_blank" class="btn btn-link">View Report PDF</a>
                                        <?php else: ?>
                                            <span class="text-danger">No report PDF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($project['code_link'])): ?>
                                            <a href="<?php echo htmlspecialchars($project['code_link']); ?>" target="_blank" class="btn btn-link">View Code PDF</a>
                                        <?php else: ?>
                                            <span class="text-danger">No code PDF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($project['submission_status'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php
                                        $submissionType = $project['submission_status'] ?? null;
                                        $projectStatus = $project['status'] ?? null;

                                        if ($submissionType === 'submitted' && $projectStatus === 'approved'): ?>
                                            <form method="POST" action="/ProjectManager/controllers/evaluate_project.php">
                                                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['project_id']); ?>">
                                                <div class="mb-2">
                                                    <input type="text" name="remarks" placeholder="Enter remarks" class="form-control" required>
                                                </div>
                                                <div class="mb-2">
                                                    <input type="number" name="marks" placeholder="Enter marks" class="form-control" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label>
                                                        <input type="checkbox" name="submission_activated" value="1">
                                                        Activate Submission
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Submit Evaluation</button>
                                            </form>

                                        <?php else: ?>
                                            <span class="text-muted">Cannot evaluate yet.</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if ($project['status'] !== 'approved'): ?>
                                            <form method="POST" action="/ProjectManager/views/approve_project.php">
                                                <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['project_id']); ?>">
                                                <button type="submit" class="btn btn-success">Approve Project</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No projects assigned.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No students assigned.</p>
        <?php endif; ?>

    </div>

    <a href="/ProjectManager/logout.php" class="btn btn-danger mt-3">Logout</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>