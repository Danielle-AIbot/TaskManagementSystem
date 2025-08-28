<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\Users\PHP\Pending_task.php
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

$account_image = !empty($user['profpic']) ? $user['profpic'] : 'account.jpg';

// Use TaskManager for all task queries
$taskManager = new TaskManager($conn);

// Stats
$pending_tasks_count = count($taskManager->getUserTasksBySearch($user_id, '', 'pending'));
$completed_tasks_count = count($taskManager->getUserTasksBySearch($user_id, '', 'completed'));

// Pending tasks (arranged by priority)
$pending_tasks = $taskManager->getUserTasksBySearch($user_id, '', 'pending');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pending Tasks</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* TASK LIST */
        .tasks ul {
            list-style: none;
            padding: 0;
        }

        .tasks li {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            color: #333;
        }

        .tasks li p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .tasks li a {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #a635df;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.3s ease;
        }

        .tasks li a:hover {
            background: #ff416c;
        }

        /* RESPONSIVENESS */
        @media (max-width: 1024px) {
            .menubar {
                padding: 25px 20px;
            }

            .dashboard {
                padding: 30px 25px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                align-items: center;
                padding: 20px;
            }

            .menubar {
                width: 100%;
                margin-bottom: 20px;
            }

            .dashboard {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .dashboard h2 {
                font-size: 22px;
            }

            .tasks li {
                padding: 10px 15px;
            }

            .tasks li p {
                font-size: 13px;
            }

            .tasks li a {
                width: 100%;
                padding: 10px;
                font-size: 13px;
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
        <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
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
            <h2>Pending Tasks</h2>
            <ul>
                <?php foreach ($pending_tasks as $task) : ?>
                    <li>
                        <h3><?php echo htmlspecialchars($task['title']); ?></h3>
                        <p><?php echo htmlspecialchars($task['description']); ?></p>
                        <p><strong>Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                        <p><strong>Due Date:</strong> <?php echo htmlspecialchars($task['due_date']); ?></p>
                        <a href="edit_task_pending.php?id=<?php echo $task['id']; ?>">Edit</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>

</html>