<?php
include 'db.php';

$id = $_GET['id']; // Get the user ID from the URL
$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result); // Fetch the user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form action="update_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>
        <label for="profile_image">Profile Image:</label>
        <input type="file" name="profile_image"><br><br>
        <img src="uploads/<?php echo $user['profile_image']; ?>" width="100"><br><br>
        <input type="submit" value="Update User">
    </form>
</body>
</html>
