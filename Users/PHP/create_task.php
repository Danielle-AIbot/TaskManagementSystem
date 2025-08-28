<?php
require_once '../../admins/configs/db.php';
require_once '../../admins/configs/taskmanager.php';
require_once '../../admins/configs/crud.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$database = new Database();
$conn = $database->connection();
$taskManager = new TaskManager($conn);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $user_id = $_SESSION['user_id'];
    $assigned_by = $user_id;

    // Check if due date is in the past
    $today = date('Y-m-d');
    if ($due_date < $today) {
        $message = "<p style='color:red;'>Error: Due date cannot be in the past.</p>";
    } else {
        if ($taskManager->createTask($title, $description, $priority, $due_date, $user_id, $user_id)) {
            header("Location: tasks.php");
            exit();
        } else {
            $message = "<p style='color:red;'>Error: Could not assign task.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Task</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('../../image.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            color: #a635df;
            font-size: 28px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        label {
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        textarea,
        input[type="date"],
        select {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border 0.3s ease;
            width: 100%;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #a635df;
            outline: none;
        }

        input[type="submit"] {
            padding: 12px;
            background: linear-gradient(to right, #ff416c, #a635df);
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #a635df, #ff416c);
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #a635df;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 24px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <?php if (!empty($message)) echo $message; ?>
    <main>
        <section class="container">
            <h2>Create Own Task</h2>
            <form action="create_task.php" method="post">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <input type="date" name="due_date" required>
                <input type="submit" value="Create Task">
            </form>
            <p><a href="tasks.php">Return</a></p>
        </section>
    </main>
</body>

</html>