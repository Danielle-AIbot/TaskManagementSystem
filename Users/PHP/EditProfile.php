<?php
include '../../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Error.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $profile_image = $_FILES['profile_image']['name'];

    $target_dir = "../Uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        $sql = "UPDATE user SET profpicture = '$profile_image' WHERE id = $user_id";

        if (mysqli_query($conn, $sql)) {
            // Log the profile picture update action
            $username = $_SESSION['username'];
            $activity = "Updated profile picture";
            $sql_activity = "INSERT INTO activities (user_id, username, activity, created_at) VALUES ($user_id, '$username', '$activity', NOW())";
            mysqli_query($conn, $sql_activity);

            // Update session variable
            $_SESSION['profpicture'] = $profile_image;

            header("Location: Dashboard.php");
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
        <form action="EditProfile.php" method="post" enctype="multipart/form-data">
            <label for="profile_image">New Profile Image:</label>
            <input type="file" name="profile_image" required>
            <input type="submit" value="Update Profile">
        </form>
    </div>
</body>

</html>