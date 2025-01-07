<?php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Error.php");
    exit();
}

// Fetch the logged-in user's username and profile picture
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, profpicture FROM user WHERE id = $user_id";
$result_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result_user);

// Set the account image from the profile picture provided by the user
$account_image = !empty($user['profpicture']) ? $user['profpicture'] : '../../admins/pics/account.jpg'; // Default image if no profile picture is provided

// Handle search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql_tasks = "SELECT * FROM tasks WHERE user_id = $user_id AND (title LIKE '%$search_query%' OR description LIKE '%$search_query%') ORDER BY FIELD(priority, 'High', 'Medium', 'Low')";
} else {
    $sql_tasks = "SELECT * FROM tasks WHERE user_id = $user_id ORDER BY FIELD(priority, 'High', 'Medium', 'Low')";
}
$result_tasks = mysqli_query($conn, $sql_tasks);
$tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="../CSS/Index.css">
</head>

<body>
    <div class="menubar">
        <div class="account">
            <img src="../Uploads/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $user['username']; ?></div>
            <a href="../HTML/EditProfile.html" class="icon-btn"><i class="fas fa-user-edit"></i></a>
        </div>
        <ul>
            <li><a href="1.0.Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="2.0.Tasks.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="3.0.Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>My Tasks</h2>
        <form method="get" action="tasks.php">
            <input type="text" name="search" placeholder="Search tasks..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>
        <button id="add-task-btn" class="btn">Add Task</button>
        <div class="tasks">
            <?php foreach ($tasks as $task) : ?>
                <div class="task-item">
                    <div class="task-info">
                        <h3><?php echo $task['title']; ?></h3>
                        <p><?php echo $task['description']; ?></p>
                        <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                        <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
                    </div>
                    <div class="task-actions">
                        <a href="2.1.edit_task.php" class="btn">Edit</a>
                        <a href="2.2.delete_task.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal for Add Task -->
    <div id="add-task-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Task</h2>
            <form action="2.3.add_task.php" method="post">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <input type="date" name="due_date" required>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="submit" value="Add Task">
            </form>
        </div>
    </div>

    <script src="../JS/modal.js"></script>
</body>

</html>