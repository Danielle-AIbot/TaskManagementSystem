<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Users/Login_process.php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the user from the database
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start a session and redirect to the user dashboard
        session_start();
        $_SESSION['user_id'] = $user['id'];
        header("Location: User_dashboard.php");
        exit();
    } else {
        // Invalid credentials
        echo "Invalid username or password.";
    }
}
