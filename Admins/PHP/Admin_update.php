<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/Admin_update.php
include '../../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['id'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_image = $_FILES['pic']['name'];

    $target_dir = "../../profile/";
    $target_file = $target_dir . basename($_FILES["pic"]["name"]);

    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
        $sql = "UPDATE admin SET username = '$username', password = '$password', pic = '$profile_image' WHERE id = $admin_id";

        if (mysqli_query($conn, $sql)) {
            header("Location: Admin_index.php");
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    // Fetch the admin details to pre-fill the form
    $admin_id = $_GET['id'];
    $sql = "SELECT * FROM admin WHERE id = $admin_id";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Admin</title>
    <link rel="stylesheet" href="../CSS/form.css">
</head>

<body>
    <div class="container">
        <h2>Update Admin</h2>
        <form action="Admin_update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="pic">Profile Image:</label>
            <input type="file" name="pic" required>
            <input type="submit" value="Update Admin">
        </form>
    </div>
</body>

</html>