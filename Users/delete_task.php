<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];

    $sql = "DELETE FROM tasks WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: Dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
