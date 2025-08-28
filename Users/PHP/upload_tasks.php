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

$task_id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $task_id = $_POST['task_id'];
    $description = $_POST['description'];
    $task_image = $_FILES['task_image']['name'];

    $target_dir = "../../admins/tasks/";
    $target_file = $target_dir . basename($_FILES["task_image"]["name"]);

    if (move_uploaded_file($_FILES["task_image"]["tmp_name"], $target_file)) {
        // OOP: Update the task using TaskManager
        $sql = "UPDATE tasks SET description = ?, completed_image = ?, status = 'completed' WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$description, $task_image, $task_id, $user_id]);

        // Log the task completion action (OOP style)
        if (!isset($_SESSION['username'])) {
            $stmt_user = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $stmt_user->execute([$user_id]);
            $user_row = $stmt_user->fetch(PDO::FETCH_ASSOC);
            $username = $user_row ? $user_row['username'] : '';
        } else {
            $username = $_SESSION['username'];
        }
        $activity = "Task Successfully Completed";
        $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES (?, ?, ?, NOW())";
        $stmt_activity = $conn->prepare($sql_activity);
        $stmt_activity->execute([$user_id, $username, $activity]);

        header("Location: completed_task.php");
        exit();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Completed</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Complete Task</h2>
        <form action="upload_tasks.php?id=<?php echo $task_id; ?>" method="post" enctype="multipart/form-data">
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            <label for="task_image">Task Image:</label>
            <input type="file" name="task_image" required>
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
            <input type="submit" value="Complete Task">
        </form>
    </div>
</body>
</html>