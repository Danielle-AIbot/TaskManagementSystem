<?php
include '../../db.php';

$id = $_GET['id']; // Get the user ID from the URL
$sql = "SELECT * FROM user WHERE id = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result); // Fetch the user data
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
        <form action="user_update_process.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Update">
        </form>
    </div>
</body>

</html>