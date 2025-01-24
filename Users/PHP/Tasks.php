<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/Tasks.php
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
            <li><a href="Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="Tasks.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="Logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>My Tasks</h2>
        <form method="get" action="tasks.php">
            <input type="text" name="search" placeholder="Search tasks..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>
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
                        <a href="edit_task_tasks.php?id=<?php echo $task['id']; ?>" class="btn">Edit</a>
                        <a href="delete_task_tasks.php?id=<?php echo $task['id']; ?>" class="btn">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
</body>

</html>