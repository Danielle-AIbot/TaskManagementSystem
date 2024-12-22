<?php

include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";

    if (mysqli_query($conn, $sql)) {
        // Get the ID of the newly created admin
        $admin_id = mysqli_insert_id($conn);
        // Store the admin's ID in the session
        $_SESSION['admin_id'] = $admin_id;
        header("Location: Admin_index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
