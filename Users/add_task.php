<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO tasks (title, description, priority, status, user_id) VALUES ('$title', '$description', '$priority', 'Pending', $user_id)";

    if (mysqli_query($conn, $sql)) {
        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
