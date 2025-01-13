<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/edit_task_form.php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $sql = "SELECT * FROM tasks WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $task = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../CSS/form.css">
    <link rel="stylesheet" href="../CSS/Style.css">
</head>

<body>
    <header>
        <h1>Edit Task</h1>
    </header>

    <main>
        <section class="edit-task">
            <h2>Edit Task</h2>
            <form action="edit_task_process.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
                <textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
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
                <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
                <input type="submit" value="Update Task">
            </form>
        </section>
    </main>
</body>

</html>