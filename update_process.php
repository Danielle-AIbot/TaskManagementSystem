<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    
    $profile_image = $_FILES['profile_image']['name'];
    $target_file = "";

    // If a new image is uploaded, handle the upload
    if ($profile_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    }

    // Update query based on whether a new image is uploaded or not
    if ($profile_image) {
        $sql = "UPDATE users SET name='$name', email='$email', profile_image='$profile_image' WHERE id=$id";
    } else {
        $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
