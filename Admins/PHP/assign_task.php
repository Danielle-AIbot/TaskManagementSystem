<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/assign_task.php
include '../../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $user_id = $_POST['user_id'];
    $admin_id = $_SESSION['admin_id'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, priority, status, due_date, user_id, assigned_by, created_at, updated_at) VALUES (?, ?, ?, 'new', ?, ?, ?, NOW(), NOW())");

    // Bind the parameters to the SQL query
    $stmt->bind_param("ssssii", $title, $description, $priority, $due_date, $user_id, $admin_id);

    // Execute the statement
    if ($stmt->execute()) {
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
    <title>Assign Task</title>
    <link rel="stylesheet" href="../CSS/form.css">
</head>

<body>
    <main>
        <section class="assign-task">
            <h2>Assign Task to User</h2>
            <form action="assign_task.php" method="post">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <input type="date" name="due_date" required>
                <select name="user_id" required>
                    <?php
                    // Fetch users from the database
                    $sql_users = "SELECT id, username FROM user";
                    $result_users = mysqli_query($conn, $sql_users);
                    while ($user = mysqli_fetch_assoc($result_users)) {
                        echo "<option value='" . $user['id'] . "'>" . $user['username'] . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="Assign Task">
            </form>
        </section>
    </main>
</body>

</html>