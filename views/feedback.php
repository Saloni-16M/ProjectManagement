<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Project Feedback</title>
</head>
<body>
<div class="container mt-5">
    <h2>Provide Feedback</h2>
    <form action="index.php?action=feedback&id=<?= $_GET['id'] ?>" method="post">
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="remark_type">Remark Type</label>
            <select class="form-control" id="remark_type" name="remark_type" required>
                <option value="positive">Positive</option>
                <option value="negative">Negative</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
