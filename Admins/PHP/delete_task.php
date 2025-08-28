<?php
require_once '../configs/db.php';

$id = $_GET['id'];
$sql = "DELETE FROM tasks WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: completed_tasks.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
