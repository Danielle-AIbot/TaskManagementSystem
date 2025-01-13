<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO tasks (title, description, priority, status, due_date, user_id) VALUES ('$title', '$description', '$priority', 'Pending', '$due_date', $user_id)";

    if (mysqli_query($conn, $sql)) {
        // Log the add task action
        $activity = "Added a task: $title";
        $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
        mysqli_query($conn, $sql_activity);

        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
