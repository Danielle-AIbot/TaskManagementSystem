<?php
require_once '../configs/db.php';
include '../configs/taskmanager.php';
include '../configs/dashboard.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$conn = $database->connection();

$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username, profpic as pic FROM users WHERE id = ?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->execute([$admin_id]);
$admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

$account_image = !empty($admin['pic']) ? $admin['pic'] : 'default.jpg';

$dashboard = new Dashboard($conn, $admin_id);

$taskManager = new TaskManager($conn);

// Only get completed tasks
$completed_tasks = $taskManager->getCompletedTasksByAdmin($admin_id);
$tasks_count = $taskManager->getTasksCount();
$completed_tasks_count = $taskManager->getCompletedTasksCount();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Completed Tasks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* BACKGROUND */
        body {
            background: url('../../image.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 40px 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            backdrop-filter: blur(6px);
            background-color: rgba(255, 255, 255, 0.2);
            z-index: 0;
        }

        /* SIDEBAR MENU */
        .menubar {
            width: 250px;
            min-height: 90vh;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px 20px;
            margin-right: 30px;
            position: relative;
            z-index: 1;
        }

        .menubar .account {
            text-align: center;
            margin-bottom: 30px;
        }

        .menubar .account img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #a635df;
            margin-bottom: 10px;
        }

        .menubar .username {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .menubar .icon-btn {
            color: #a635df;
            font-size: 16px;
            text-decoration: none;
        }

        .menubar ul {
            list-style: none;
        }

        .menubar ul li {
            margin: 15px 0;
        }

        .menubar ul li a {
            text-decoration: none;
            color: #444;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            transition: background 0.3s;
        }

        .menubar ul li a:hover {
            background-color: #f0e5ff;
            color: #a635df;
        }

        /* DASHBOARD MAIN AREA */
        .dashboard {
            flex: 1;
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .dashboard h2 {
            font-size: 28px;
            color: #a635df;
            margin-bottom: 30px;
        }

        /* STAT CARDS */
        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat {
            flex: 1 1 200px;
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat h3 {
            font-size: 32px;
            color: #333;
        }

        .stat p {
            color: #666;
            margin: 10px 0;
        }

        .stat .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            border-radius: 20px;
            background: linear-gradient(to right, #ff416c, #a635df);
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .stat .btn:hover {
            background: linear-gradient(to right, #a635df, #ff416c);
        }

        /* RECENT ACTIVITIES */
        .tasks h2 {
            font-size: 22px;
            color: #a635df;
            margin-bottom: 20px;
        }

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

        /* RESPONSIVE QUERIES */
        @media (max-width: 1024px) {
            .menubar {
                padding: 25px 20px;
            }

            .dashboard {
                padding: 30px 25px;
            }

            .stat h3 {
                font-size: 26px;
            }

            .stat .btn {
                font-size: 13px;
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

            .stats {
                flex-direction: column;
            }

            .stat {
                width: 100%;
            }
        }

        @media (max-width: 480px) {

            .dashboard h2,
            .recent-activities h2 {
                font-size: 22px;
            }

            .stat h3 {
                font-size: 24px;
            }

            .stat p {
                font-size: 13px;
            }

            .stat .btn {
                width: 100%;
                padding: 10px;
                font-size: 13px;
            }

            .recent-activities li {
                padding: 10px 15px;
            }

            .recent-activities li p {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <div class="menubar">
        <div class="account">
            <img src="../../profile/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
            <a href="EditProfile.php" class="icon-btn"><i class="fas fa-user-edit"></i></a>
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
                <h3><?php echo $dashboard->users_count; ?></h3>
                <p><?php echo $dashboard->user['role'] === 'admin' ? 'Students' : 'Your Account'; ?></p>
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
            <h2>Completed Tasks Assigned by You</h2>
            <section class="tasks">
                <ul>
                    <?php foreach ($completed_tasks as $task) : ?>
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