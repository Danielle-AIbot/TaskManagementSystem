<?php
require_once '../../admins/configs/db.php';
require_once '../../admins/configs/taskmanager.php';
session_start();

$database = new Database();
$conn = $database->connection();

$user_id = $_SESSION['user_id'] ?? null;
$task_id = $_GET['id'] ?? null;

// Get username safely
if (!isset($_SESSION['username'])) {
    $stmt_user = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $user_row = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $username = $user_row ? $user_row['username'] : '';
} else {
    $username = $_SESSION['username'];
}

if ($task_id && $user_id) {
    $taskManager = new TaskManager($conn);

    // Only delete if the task is self-assigned (created by user)
    $taskManager->deleteTaskById($task_id, $user_id);

    // Log the task deletion action
    $activity = "Task Deleted";
    $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql_activity);
    $stmt->execute([$user_id, $username, $activity]);
}

header("Location: tasks.php");
exit();