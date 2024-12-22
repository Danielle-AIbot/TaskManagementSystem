<?php

    include 'db.php';

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($password, PASSWORD_DEFAULT);
        $profile_image = $_FILES['profile_image']['name'];

        $target_dir = "Uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

        if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file))
        {
            $sql = "INSERT INTO user (username, email, password, profpicture) VALUES ('$username', '$email', '$password', '$profile_image')";

            if(mysqli_query($conn, $sql))
            {
                header("Location: User.html");
                exit(); 
            }
            else
            {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
        else
        {
            echo "Sorry, there was an error uploading your file.";
        }
    }
?>