<?php
include 'db.php';
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
$sql_pending_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'pending'";
$result_pending_tasks_count = mysqli_query($conn, $sql_pending_tasks_count);
$pending_tasks_count = mysqli_fetch_assoc($result_pending_tasks_count)['count'];

$sql_in_progress_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'in_progress'";
$result_in_progress_tasks_count = mysqli_query($conn, $sql_in_progress_tasks_count);
$in_progress_tasks_count = mysqli_fetch_assoc($result_in_progress_tasks_count)['count'];

$sql_completed_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE user_id = $user_id AND status = 'completed'";
$result_completed_tasks_count = mysqli_query($conn, $sql_completed_tasks_count);
$completed_tasks_count = mysqli_fetch_assoc($result_completed_tasks_count)['count'];

// Fetch tasks that are due in the next 3 days until the due date and are not completed
$sql_tasks_due_soon = "SELECT * FROM tasks WHERE user_id = $user_id AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND status != 'completed'";
$result_tasks_due_soon = mysqli_query($conn, $sql_tasks_due_soon);
$tasks_due_soon = mysqli_fetch_all($result_tasks_due_soon, MYSQLI_ASSOC);
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
            <li><a href="Dashboard.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>
        <div class="stats">
            <div class="stat">
                <h3><?php echo $pending_tasks_count; ?></h3>
                <p>Pending Tasks</p>
            </div>
            <div class="stat">
                <h3><?php echo $in_progress_tasks_count; ?></h3>
                <p>In Progress Tasks</p>
            </div>
            <div class="stat">
                <h3><?php echo $completed_tasks_count; ?></h3>
                <p>Completed Tasks</p>
            </div>
        </div>

        <section class="tasks">
            <h2>Tasks Due Soon</h2>
            <ul>
                <?php foreach ($tasks_due_soon as $task) : ?>
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
</body>

</html>