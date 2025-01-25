<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/tasks.php
include '../../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in admin's username and profile picture
$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username, pic FROM admin WHERE id = $admin_id";
$result_admin = mysqli_query($conn, $sql_admin);
$admin = mysqli_fetch_assoc($result_admin);

// Set the account image from the profile picture provided by the user
$account_image = !empty($admin['pic']) ? $admin['pic'] : 'default.jpg'; // Default image if no profile picture is provided

// Fetch statistics
$sql_users_count = "SELECT COUNT(*) AS count FROM user";
$result_users_count = mysqli_query($conn, $sql_users_count);
$users_count = mysqli_fetch_assoc($result_users_count)['count'];

$sql_tasks_count = "SELECT COUNT(*) AS count FROM tasks";
$result_tasks_count = mysqli_query($conn, $sql_tasks_count);
$tasks_count = mysqli_fetch_assoc($result_tasks_count)['count'];

$sql_completed_tasks_count = "SELECT COUNT(*) AS count FROM tasks WHERE status = 'completed'";
$result_completed_tasks_count = mysqli_query($conn, $sql_completed_tasks_count);
$completed_tasks_count = mysqli_fetch_assoc($result_completed_tasks_count)['count'];

// Fetch tasks assigned by the admin
$sql_tasks_assigned = "SELECT t.*, u.username AS assigned_to FROM tasks t JOIN user u ON t.user_id = u.id WHERE t.assigned_by = $admin_id";
$result_tasks_assigned = mysqli_query($conn, $sql_tasks_assigned);
$tasks_assigned = mysqli_fetch_all($result_tasks_assigned, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="menubar">
        <div class="account">
            <img src="../../Profile/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
            <a href="EditProfile.php" class="icon-btn"><i class="fas fa-user-edit"></i></a>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
        <div class="stats">
            <div class="stat">
                <h3><?php echo $users_count; ?></h3>
                <p>Students</p>
            </div>
            <div class="stat">
                <h3><?php echo $tasks_count; ?></h3>
                <p>Tasks</p>
                <a href="tasks.php" class="btn">Show Tasks</a>
            </div>
            <div class="stat">
                <h3><?php echo $completed_tasks_count; ?></h3>
                <p>Approved Tasks</p>
                <a href="completed_tasks.php" class="btn">Show Completed Tasks</a>
            </div>
        </div>
        <section class="tasks">
            <h2>Tasks Assigned by You</h2>
            <ul>
                <?php foreach ($tasks_assigned as $task) : ?>
                    <li>
                        <h3><?php echo $task['title']; ?></h3>
                        <p><?php echo $task['description']; ?></p>
                        <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                        <p><strong>Due Date:</strong> <?php echo $task['due_date']; ?></p>
                        <p><strong>Assigned To:</strong> <?php echo $task['assigned_to']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>

</body>

</html>