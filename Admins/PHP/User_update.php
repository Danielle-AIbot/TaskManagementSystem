<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/User_update.php
include '../../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE user SET username = '$username', password = '$password' WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        header("Location: User_index.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // Fetch the user details to pre-fill the form
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="../CSS/Form.css">
</head>

<body>
    <div class="container">
        <h2>Edit User</h2>
        <form action="User_update.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>