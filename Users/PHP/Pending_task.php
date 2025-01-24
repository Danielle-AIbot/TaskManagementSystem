<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/Pending_task.php
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

// Fetch statistics
$sql_new_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'new_task'";
$result_new_tasks_count = mysqli_query($conn, $sql_new_tasks_count);
$new_tasks_count = mysqli_fetch_assoc($result_new_tasks_count)['count'];

$sql_pending_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'pending'";
$result_pending_tasks_count = mysqli_query($conn, $sql_pending_tasks_count);
$pending_tasks_count = mysqli_fetch_assoc($result_pending_tasks_count)['count'];

$sql_completed_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'completed'";
$result_completed_tasks_count = mysqli_query($conn, $sql_completed_tasks_count);
$completed_tasks_count = mysqli_fetch_assoc($result_completed_tasks_count)['count'];

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
    <link rel="stylesheet" href="../CSS/index.css">
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
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <div class="stats">
            <div class="stat">
                <h3><?php echo $new_tasks_count; ?></h3>
                <p>New Tasks</p><br>
                <a href="new_task.php" class="btn">Show New Tasks</a>
            </div>
            <div class="stat">
                <h3><?php echo $pending_tasks_count; ?></h3>
                <p>Pending Tasks</p><br>
                <a href="pending_task.php" class="btn">Show Pending Tasks</a>
            </div>
            <div class="stat">
                <h3><?php echo $completed_tasks_count; ?></h3>
                <p>Completed Tasks</p><br>
                <a href="completed_task.php" class="btn">Show Completed Tasks</a>
            </div>
        </div>

        <section class="tasks">
            <h2>Pending Tasks</h2>
            <ul>
                <?php foreach ($pending_tasks as $task) : ?>
                    <li>
                        <h3><?php echo $task['title']; ?></h3>
                        <p><?php echo $task['description']; ?></p>
                        <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                        <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" >Edit</a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>

</html>