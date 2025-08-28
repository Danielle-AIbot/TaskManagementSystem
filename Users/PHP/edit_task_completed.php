<?php
require_once '../../admins/configs/db.php';
require_once '../../admins/configs/taskmanager.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$database = new Database();
$conn = $database->connection();
$taskManager = new TaskManager($conn);

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? null;

// Fetch the task details using OOP
$task = null;
if ($task_id) {
    $tasks = $taskManager->getUserTasks($user_id);
    foreach ($tasks as $t) {
        if ($t['id'] == $task_id) {
            $task = $t;
            break;
        }
    }
}

if (!$task) {
    echo "Task not found or you do not have permission.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    // Update the task
    $sql = "UPDATE tasks SET title = ?, description = ?, priority = ?, status = ?, due_date = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$title, $description, $priority, $status, $due_date, $task_id, $user_id]);

    // Handle image upload if status is completed
    if ($status == 'completed' && isset($_FILES['task_image']) && $_FILES['task_image']['error'] == 0) {
        $target_dir = "../../admins/tasks/";
        $target_file = $target_dir . basename($_FILES["task_image"]["name"]);
        if (move_uploaded_file($_FILES["task_image"]["tmp_name"], $target_file)) {
            // Save image filename to DB
            $sql_img = "UPDATE tasks SET completed_image = ? WHERE id = ? AND user_id = ?";
            $stmt_img = $conn->prepare($sql_img);
            $stmt_img->execute([basename($_FILES["task_image"]["name"]), $task_id, $user_id]);
        }
    }

    header("Location: completed_task.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <main>
        <section class="edit-task">
            <h2>Edit Task</h2>
            <form action="edit_task_completed.php?id=<?php echo $task_id; ?>" method="post" enctype="multipart/form-data">
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
                <select name="priority" required>
                    <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                    <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
                </select>
                <select name="status" required>
                    <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
                <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                <div id="upload-image-section" <?php if ($task['status'] != 'completed') echo 'style="display: none;"'; ?>>
                    <label for="task_image">Upload Image:</label>
                    <input type="file" name="task_image" id="task_image" accept="image/*">
                </div>
                <input type="submit" value="Update Task">
                <input type="button" value="Cancel" onclick="window.history.back(); return false;">
            </form>
        </section>
    </main>
</body>
</html>