<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update query
    $sql = "UPDATE admin SET username='$username', password='$password' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: Admin_index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
