<?php
include '../../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    $sql_admin = "SELECT * FROM admin WHERE username = '$username'";
    $result_admin = mysqli_query($conn, $sql_admin);
    $admin = mysqli_fetch_assoc($result_admin);

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
    }
    if ($admin && password_verify($password, $admin['password'])) {
        session_start();
        $_SESSION['admin_id'] = $admin['id'];

        // Log the login action
        $admin_id = $admin['id'];
        $activity = "Logged in";
        $sql_activity = "INSERT INTO activities (admin_id, username, activity, created_at) VALUES ($admin_id, '$username', '$activity', NOW())";
        mysqli_query($conn, $sql_activity);

        header("Location: ../../admins/php/dashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="../CSS/form.css">
</head>

<body>
    <div class="container">
        <h2>User Login</h2>
        <form action="Login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>