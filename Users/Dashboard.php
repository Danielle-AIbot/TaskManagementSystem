<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_tasks = "SELECT * FROM tasks WHERE user_id = $user_id";
$result_tasks = mysqli_query($conn, $sql_tasks);
$tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Dashboard</h1>
        <a href="Logout.php">Logout</a>
    </header>

    <main>
        <section class="add-task">
            <h2>Add Task</h2>
            <form action="add_task.php" method="post">
                <input type="text" name="title" placeholder="Task Title" required>
                <textarea name="description" placeholder="Task Description" required></textarea>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="submit" value="Add Task">
            </form>
        </section>

        <section class="tasks">
            <h2>My Tasks</h2>
            <ul>
                <?php foreach ($tasks as $task) : ?>
                    <li>
                        <h3><?php echo $task['title']; ?></h3>
                        <p><?php echo $task['description']; ?></p>
                        <p><strong>Priority:</strong> <?php echo $task['priority']; ?></p>
                        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>">Edit</a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>

</html>