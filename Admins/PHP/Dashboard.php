<?php
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in admin's username
$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username FROM admin WHERE id = $admin_id";
$result_admin = mysqli_query($conn, $sql_admin);
$admin = mysqli_fetch_assoc($result_admin);

// Set the account image based on the username
$account_image = '../pics/account.jpg'; // Default image
if ($admin['username'] == 'Danielle Mae') {
    $account_image = '../pics/dani.jpg';
} elseif ($admin['username'] == 'Abegail') {
    $account_image = '../pics/abby.jpg';
} elseif ($admin['username'] == 'Maria Luzviminda') {
    $account_image = '../pics/Luzvie.jpg';
}

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

// Fetch recent activities of all users
$sql_recent_activities = "SELECT username, activity, created_at FROM activities ORDER BY created_at DESC LIMIT 10";
$result_recent_activities = mysqli_query($conn, $sql_recent_activities);
$recent_activities = mysqli_fetch_all($result_recent_activities, MYSQLI_ASSOC);

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
            <img src="<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="Admin_index.php"><i class="fas fa-user"></i> Admins</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Welcome, <?php echo $admin['username']; ?>!</h2>
        <div class="stats">
            <div class="stat">
                <h3><?php echo $users_count; ?></h3>
                <p>Users</p>
            </div>
            <div class="stat">
                <h3><?php echo $tasks_count; ?></h3>
                <p>Tasks</p>
            </div>
            <div class="stat">
                <h3><?php echo $completed_tasks_count; ?></h3>
                <p>Completed Tasks</p>
            </div>
        </div>
        <div class="recent-activities">
            <h3>Recent User Activities</h3>
            <ul>
                <?php foreach ($recent_activities as $activity) : ?>
                    <li>
                        <p><strong>User:</strong> <?php echo $activity['username']; ?></p>
                        <p><strong>Activity:</strong> <?php echo $activity['activity']; ?></p>
                        <p><strong>Date:</strong> <?php echo $activity['created_at']; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

</body>

</html>
