<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_tasks = "SELECT * FROM tasks WHERE user_id = $user_id";
$result_tasks = mysqli_query($conn, $sql_tasks);
$tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);

// Fetch the logged-in user's username and profile picture
$sql_user = "SELECT username, profpicture FROM user WHERE id = $user_id";
$result_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result_user);

// Set the account image from the profile picture provided by the user
$account_image = !empty($user['profpicture']) ? $user['profpicture'] : 'account.jpg'; // Default image if no profile picture is provided
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <div class="menubar">
        <div class="account">
            <img src="Uploads/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $user['username']; ?></div>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="tasks.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <button id="add-task-btn" class="btn">Add Task</button>

        <section class="tasks">
            <h2>My Tasks</h2>
            <ul>
                <?php foreach ($tasks as $task) : ?>
                    <li>
                        <h3><?php echo $task['title']; ?></h3>
                        <p><?php echo $task['description']; ?></p>
                        <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                        <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>">Edit</a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>

    <!-- Modal for Add Task -->
    <div id="add-task-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Task</h2>
            <form action="add_task.php" method="post">
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

    <script src="modal.js"></script>
</body>

</html>