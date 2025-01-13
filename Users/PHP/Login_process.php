<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];

        // Log the login action
        $user_id = $user['id'];
        $activity = "Logged in";
        $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
        mysqli_query($conn, $sql_activity);

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>