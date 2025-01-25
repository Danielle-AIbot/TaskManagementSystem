<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/dashboard.php
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
            <img src="../../Profile/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
            <a href="editprofile.php" class="icon-btn"><i class="fas fa-user-edit"></i></a>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Students</a></li>
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

        <section class="recent-activities">
            <h2>Recent Activities</h2>
            <ul>
                <?php foreach ($recent_activities as $activity) : ?>
                    <li>
                        <p>Username:<strong><?php echo $activity['username']; ?>
                    <p>Activity:</strong> <?php echo $activity['activity']; ?>
                            <p>Time:<em>(<?php echo $activity['created_at']; ?>)</em>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>

</html>