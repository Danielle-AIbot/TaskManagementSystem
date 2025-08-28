<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\Users\PHP\tasks.php
require_once '../../admins/configs/db.php';
require_once '../../Admins/configs/taskmanager.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->connection();

// Fetch the logged-in user's info
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, profpic FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$account_image = !empty($user['profpic']) ? $user['profpic'] : 'account.jpg';

// Handle search query
$search_query = "";
$taskManager = new TaskManager($conn);

if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $tasks = $taskManager->getUserTasksBySearch($user_id, $search_query);
} else {
    $tasks = $taskManager->getUserTasks($user_id);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tasks</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="menubar">
        <div class="account">
            <img src="../../profile/<?php echo htmlspecialchars($account_image); ?>" alt="Account Image">
            <div class="username"><?php echo htmlspecialchars($user['username']); ?></div>
            <a href="editprofile.php" class="icon-btn"><i class="fas fa-user-edit"></i></a>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="tasks.php"><i class="fas fa-users"></i> Tasks</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>My Tasks</h2>
        <div class="tasks">
            <button>
                <a href="create_task.php" class="btn">Create New Task</a>
            </button>
            <?php foreach ($tasks as $task) : ?>
                <div class="task-item">
                    <div class="task-info">
                        <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p><strong>Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                        <p><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?></p>
                        <p><strong>Assigned By:</strong>
                            <?php
                            echo ($task['assigned_by'] == $user_id) ? 'Me' : htmlspecialchars($task['assigned_by_name']);
                            ?>
                        </p>
                    </div>
                    <div class="task-actions">
                        <a href="edit_task_tasks.php?id=<?php echo $task['id']; ?>" class="btn">Edit</a>
                        <a href="delete_task_tasks.php?id=<?php echo $task['id']; ?>" class="btn">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>