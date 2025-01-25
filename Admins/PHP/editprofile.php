<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/editprofile.php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: Error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_SESSION['admin_id'];
    $profile_image = $_FILES['profile_image']['name'];

    $target_dir = "../../profile/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE admin SET pic = '$profile_image' WHERE id = $admin_id";

        if (mysqli_query($conn, $sql)) {
            // Update session variable
            $_SESSION['pic'] = $profile_image;

            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error updating profile picture: " . mysqli_error($conn);
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="editprofile.php" method="post" enctype="multipart/form-data">
            <label for="profile_image">New Profile Image:</label>
            <input type="file" name="profile_image" required>
            <input type="submit" value="Update Profile">
        </form>
    </div>
</body>

</html>