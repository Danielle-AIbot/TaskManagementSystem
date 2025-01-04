<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM tasks WHERE id = $id";
$result = mysqli_query($conn, $sql);
$task = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../CSS/Style.css">
</head>

<body>
    <div class="container">
        <h2>Edit Task</h2>
        <form action="update_process.php" method="post">
            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $task['title']; ?>" required><br><br>
            <label for="description">Description:</label>
            <textarea name="description" required><?php echo $task['description']; ?></textarea><br><br>
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="in_progress" <?php if ($task['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select><br><br>
            <label for="priority">Priority:</label>
            <select name="priority" required>
                <option value="low" <?php if ($task['priority'] == 'low') echo 'selected'; ?>>Low</option>
                <option value="medium" <?php if ($task['priority'] == 'medium') echo 'selected'; ?>>Medium</option>
                <option value="high" <?php if ($task['priority'] == 'high') echo 'selected'; ?>>High</option>
            </select><br><br>
            <input type="submit" value="Update Task">
        </form>
    </div>
</body>

</html>