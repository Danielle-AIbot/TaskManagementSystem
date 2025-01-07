<?php
include '../../db.php';

$id = $_GET['id'];
$sql = "DELETE FROM user WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: Admin_index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
