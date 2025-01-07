<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];

    $sql = "DELETE FROM tasks WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Log the delete task action
        $activity = "Deleted a task: $title";
        $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
        mysqli_query($conn, $sql_activity);

        header("Location: 1.0.Dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
