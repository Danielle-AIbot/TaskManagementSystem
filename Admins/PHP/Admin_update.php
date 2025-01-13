<?php
include '../../db.php';

$id = $_GET['id']; // Get the admin ID from the URL
$sql = "SELECT * FROM admin WHERE id = $id";
$result = mysqli_query($conn, $sql);
$admin = mysqli_fetch_assoc($result); // Fetch the admin data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Admin</title>
    <link rel="stylesheet" href="../CSS/Form.css">
</head>

<body>
    <div class="container">
        <h2>Edit Admin</h2>
        <form action="admin_update_process.php" method="post">
            <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $admin['username']; ?>" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>