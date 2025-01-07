<?php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $profile_image = $_FILES['profile_image']['name'];

    $target_dir = "../Uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE user SET profpicture = '$profile_image' WHERE id = $user_id";

        if (mysqli_query($conn, $sql)) {
            // Log the profile picture update action
            $username = $_SESSION['username'];
            $activity = "Updated profile picture";
            $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
            mysqli_query($conn, $sql_activity);

            // Update session variable
            $_SESSION['profpicture'] = $profile_image;

            header("Location: Dashboard.php");
            exit();
        } else {
            echo "Error updating profile picture: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
