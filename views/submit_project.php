<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css">
    <title>Submit Project</title>
</head>
<body>
    <h1>Submit Project</h1>
    <form action="../public/index.php?action=submitProject" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Project Title" required>
        <textarea name="description" placeholder="Project Description" required></textarea>
        <select name="mentor_id" required>
            <!-- Populate with mentors from database -->
        </select>
        <input type="file" name="project_file" required>
        <input type="hidden" name="team_members[]" value="1"> <!-- Example member id -->
        <button type="submit">Submit Project</button>
    </form>
</body>
</html>
