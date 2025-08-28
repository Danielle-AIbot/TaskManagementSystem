<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\Users\PHP\completed_task.php
require_once '../../Admins/configs/db.php';
require_once '../../Admins/configs/taskmanager.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$db = new Database();
$conn = $db->connection();

// Fetch the logged-in user's info
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, profpic FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

$account_image = !empty($user['profpic']) ? $user['profpic'] : 'default.jpg';

// Use TaskManager for all task queries
$taskManager = new TaskManager($conn);

// Stats
$pending_tasks_count = count($taskManager->getUserTasksBySearch($user_id, '', 'pending'));
$completed_tasks_count = count($taskManager->getUserTasksBySearch($user_id, '', 'completed'));

// Completed tasks (arranged by priority)
$completed_tasks = $taskManager->getUserTasksBySearch($user_id, '', 'completed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Completed Tasks</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        

        .tasks ul {
            list-style: none;
        }

        .tasks li {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        .tasks h3 {
            color: #a635df;
            margin-bottom: 5px;
        }

        .tasks p {
            margin: 5px 0;
        }

        .tasks img {
            margin-top: 10px;
            max-width: 100px;
            border-radius: 10px;
        }

        .tasks a {
            display: inline-block;
            margin-top: 10px;
            color: #fff;
            background-color: #a635df;
            padding: 8px 15px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                padding: 20px 10px;
            }

            .menubar {
                width: 100%;
                margin-bottom: 20px;
            }

            .dashboard {
                width: 100%;
            }

            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="menubar">
        <div class="account">
            <img src="../uploads/<?php echo htmlspecialchars($account_image); ?>" alt="Account Image">
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
        <h2>Welcome <?php echo htmlspecialchars($user['username']); ?>!</h2>
        <div class="stats">
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
            <h2>Completed Tasks</h2>
            <ul>
                <?php foreach ($completed_tasks as $task) : ?>
                    <li>
                        <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p><strong>Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                        <p><strong>Completed Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?></p>
                        <p><strong>Assigned By:</strong> <?php echo htmlspecialchars($task['assigned_by']); ?></p>
                        <?php if (!empty($task['completed_image'])) : ?>
                            <p><img src="../../admins/tasks/<?php echo htmlspecialchars($task['completed_image']); ?>" alt="Task File" width="100"></p>
                        <?php else : ?>
                            <p>No image uploaded for this task.</p>
                        <?php endif; ?>
                        <a href="upload_tasks.php?id=<?php echo $task['id']; ?>">Upload</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>

</html>