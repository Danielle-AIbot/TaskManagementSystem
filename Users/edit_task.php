<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tasks WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $task = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $sql = "UPDATE tasks SET title = '$title', description = '$description', priority = '$priority', status = '$status' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Edit Task</h1>
    </header>

    <main>
        <section class="edit-task">
            <h2>Edit Task</h2>
            <form action="edit_task.php" method="post">
                <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                <input type="text" name="title" value="<?php echo $task['title']; ?>" required>
                <textarea name="description" required><?php echo $task['description']; ?></textarea>
                <select name="priority" required>
                    <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                    <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
                </select>
                <select name="status" required>
                    <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                </select>
                <input type="submit" value="Update Task">
            </form>
        </section>
    </main>
</body>

</html>