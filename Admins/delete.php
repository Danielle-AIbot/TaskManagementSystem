<?php
include 'db.php';

$id = $_GET['id']; // Get the user ID from the URL
$sql = "DELETE FROM admin WHERE id = $id";
$sql = "DELETE FROM user WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: Admin_index.php"); // Redirect to index after deletion
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>
