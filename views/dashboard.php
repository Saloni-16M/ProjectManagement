<?php
session_start();

// Check if the form has been submitted
if (isset($_SESSION['submission_status'])) {
    $submission_status = $_SESSION['submission_status'];
    unset($_SESSION['submission_status']); // Clear the status after using it
} else {
    $submission_status = null;
}

// Retrieve user and projects data from the session
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
$projects = isset($_SESSION['projects']) ? $_SESSION['projects'] : []; // Get projects from session

if (!$user) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome to the Dashboard</h1>
        <p>Hello, <?php echo htmlspecialchars($user['username']); ?>!</p>
        <p>Your role: <?php echo htmlspecialchars($user['role_owner']); ?></p>

        <h2>Your Projects</h2>
        <?php if (empty($projects)): ?>
            <p>No projects found.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Project Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Submission Status</th>
                        <th>Remarks</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                        <tr>
                            <td><?= htmlspecialchars($project['project_title']) ?></td>
                            <td><?= htmlspecialchars($project['project_description']) ?></td>
                            <td><?= htmlspecialchars($project['status']) ?></td>
                            <td><?= htmlspecialchars($project['created_at']) ?></td>
                            <td>
    <?php if ($submission_status): ?>
        <span class="text-success">Submission successful!</span>
    <?php else: ?>
        <?php if ($project['submission_status'] === 'submitted'): ?>
            <span class="text-success">Final Submitted</span>
            <?php if (!$project['submission_activated']): ?>
                <span class="text-warning"> (Awaiting Mentor Activation)</span>
            <?php else: ?>
                <form action="/ProjectManager/views/submit_files.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['project_id']); ?>">
                        
                        <div class="mb-3">
                            <label for="github_link" class="form-label">GitHub Repository Link</label>
                            <input type="url" class="form-control" name="github_link" id="github_link" required>
                        </div>

                        <div class="mb-3">
                            <label for="report_link" class="form-label">Report (PDF)</label>
                            <input type="file" class="form-control" name="report_link" id="report_link" accept="application/pdf" required>
                        </div>

                        <div class="mb-3">
                            <label for="code_link" class="form-label">Code (PDF)</label>
                            <input type="file" class="form-control" name="code_link" id="code_link" accept="application/pdf" required>
                        </div>

                        <div class="mb-3">
                            <label for="ppt_link" class="form-label">Presentation (PDF)</label>
                            <input type="file" class="form-control" name="ppt_link" id="ppt_link" accept="application/pdf" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Files</button>
                    </form>
            <?php endif; ?>
        <?php else: ?>
            <?php if ($project['status'] === 'approved'): ?>
                <h5>Submit Project Files</h5>
                <?php if ($project['submission_status'] === 'evaluated'): ?>
                    <p>You cannot submit again as your project has been evaluated.</p>
                <?php else: ?>
                    <form action="/ProjectManager/views/submit_files.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="project_id" value="<?= htmlspecialchars($project['project_id']); ?>">
                        
                        <div class="mb-3">
                            <label for="github_link" class="form-label">GitHub Repository Link</label>
                            <input type="url" class="form-control" name="github_link" id="github_link" required>
                        </div>

                        <div class="mb-3">
                            <label for="report_link" class="form-label">Report (PDF)</label>
                            <input type="file" class="form-control" name="report_link" id="report_link" accept="application/pdf" required>
                        </div>

                        <div class="mb-3">
                            <label for="code_link" class="form-label">Code (PDF)</label>
                            <input type="file" class="form-control" name="code_link" id="code_link" accept="application/pdf" required>
                        </div>

                        <div class="mb-3">
                            <label for="ppt_link" class="form-label">Presentation (PDF)</label>
                            <input type="file" class="form-control" name="ppt_link" id="ppt_link" accept="application/pdf" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Files</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <p>Not approved yet.</p>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</td>

                            <td><?= htmlspecialchars($project['remarks'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($project['marks'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Add Project Button -->
        <a href="index.php?action=project_form" class="btn btn-primary mt-3">Add Project</a>
        <a href="/ProjectManager/logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>
</body>
</html>
