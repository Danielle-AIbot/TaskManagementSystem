<?php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in user's username and profile picture
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, profpicture FROM user WHERE id = $user_id";
$result_user = mysqli_query($conn, $sql_user);
$user = mysqli_fetch_assoc($result_user);

// Set the account image from the profile picture provided by the user
$account_image = !empty($user['profpicture']) ? $user['profpicture'] : 'account.jpg'; // Default image if no profile picture is provided

// Fetch in-progress tasks and arrange them by priority
$sql_completed_tasks = "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'completed' ORDER BY FIELD(priority, 'High', 'Medium', 'Low')";
$result_completed_tasks = mysqli_query($conn, $sql_completed_tasks);
$completed_tasks = mysqli_fetch_all($result_completed_tasks, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>In Progress Tasks</title>
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
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="tasks.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Completed Tasks</h2>
        <ul>
            <?php foreach ($completed_tasks as $task) : ?>
                <li class="task-container">
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
    </div>
</body>

</html>