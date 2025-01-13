<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/PHP/edit_task_process.php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);

    $sql = "UPDATE tasks SET title = '$title', description = '$description', priority = '$priority', status = '$status', due_date = '$due_date' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Log the edit task action
        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $activity = "Edited a task: $title";
        $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
        mysqli_query($conn, $sql_activity);

        header("Location: tasks.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
