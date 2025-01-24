<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/edit_task.php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$task_id = $_GET['id'];

// Fetch the task details
$sql_task = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql_task);
$stmt->bind_param("ii", $task_id, $_SESSION['user_id']);
$stmt->execute();
$result_task = $stmt->get_result();
$task = $result_task->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, priority = ?, status = ?, due_date = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssssi", $title, $description, $priority, $status, $due_date, $task_id, $_SESSION['user_id']);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if the status is 'completed' and an image is uploaded
        if ($status == 'completed' && isset($_FILES['task_image']) && $_FILES['task_image']['error'] == 0) {
            $target_dir = "../../admins/tasks/";
            $target_file = $target_dir . basename($_FILES["task_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["task_image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["task_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["task_image"]["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($_FILES["task_image"]["name"])) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
        header("Location: tasks.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="../CSS/form.css">
</head>

<body>
    <main>
        <section class="edit-task">
            <h2>Edit Task</h2>
            <form action="edit_task_tasks.php?id=<?php echo $task_id; ?>" method="post" enctype="multipart/form-data">
                <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
                <select name="priority" required>
                    <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                    <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                    <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
                </select>
                <select name="status" required>
                    <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
                <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                <div id="upload-image-section" <?php if ($task['status'] != 'completed') echo 'style="display: none;"'; ?>>
                    <label for="task_image">Upload Image:</label>
                    <input type="file" name="task_image" id="task_image" accept="image/*">
                </div>
                <input type="submit" value="Update Task">
                <input type="submit" value="Cancel" onclick="window.history.back(); return false;">
            </form>
        </section>
    </main>
</body>

</html>