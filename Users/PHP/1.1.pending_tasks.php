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
$account_image = !empty($user['profpicture']) ? $user['profpicture'] : 'account.jpg'; // Default image if no profile picture is provided

// Fetch pending tasks and arrange them by priority
$sql_pending_tasks = "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'pending' ORDER BY FIELD(priority, 'High', 'Medium', 'Low')";
$result_pending_tasks = mysqli_query($conn, $sql_pending_tasks);
$pending_tasks = mysqli_fetch_all($result_pending_tasks, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pending Tasks</title>
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
        <h2>Pending Tasks</h2>
        <ul>
            <?php foreach ($pending_tasks as $task) : ?>
                <li class="task-container">
                    <h3><?php echo $task['title']; ?></h3>
                    <p><?php echo $task['description']; ?></p>
                    <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                    <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                    <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
                    <a href="2.1.edit_task.php">Edit</a>
                    <a href="2.2.delete_task.php" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>