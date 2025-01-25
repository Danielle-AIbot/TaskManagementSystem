<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/upload_tasks.php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $task_id = $_POST['task_id'];
    $task_image = $_FILES['task_image']['name'];

    $target_dir = "../../admins/tasks/";
    $target_file = $target_dir . basename($_FILES["task_image"]["name"]);

    if (move_uploaded_file($_FILES["task_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE tasks SET completed_image = '$task_image' WHERE id = $task_id AND user_id = $user_id";

        if (mysqli_query($conn, $sql)) {
            // Log the task completion action
            $username = $_SESSION['user_username'];
            $activity = "Task Successfully Completed";
            $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
            mysqli_query($conn, $sql_activity);

            header("Location: completed_task.php");
            exit();
        } else {
            echo "Error updating the task: " . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="../CSS/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Upload Task</h2>
        <form action="upload_tasks.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="task_id" value="<?php echo $_GET['id']; ?>">
            <label for="task_image">Task Completed:</label>
            <input type="file" name="task_image" required>
            <input type="submit" value="Upload Task">
        </form>
    </div>
</body>

</html>