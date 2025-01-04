<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    $sql = "UPDATE tasks SET title='$title', description='$description', status='$status', priority='$priority' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
