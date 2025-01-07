<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_image = $_FILES['profile_image']['name'];

    $target_dir = "../Uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO user (username, email, password, profpicture) VALUES ('$username', '$email', '$password', '$profile_image')";

        if (mysqli_query($conn, $sql)) {
            // Log the signup action
            $user_id = mysqli_insert_id($conn);
            $activity = "Signed up";
            $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
            mysqli_query($conn, $sql_activity);

            // Set session variables
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;

            header("Location: Dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
