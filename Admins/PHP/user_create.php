<?php
require_once '../configs/db.php';
include '../configs/crud.php';

$crud = new crud();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_image = $_FILES['image']['name']; // <-- changed here
    $role = $_POST['role']; // Default role for new users

    $target_dir = "../../users/uploads/";
    $target_file = $target_dir . basename($profile_image);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) { // <-- changed here
        if ($crud->C($username, $email, $password, $profile_image, $role)) {
            header("Location: user_index.php");
            exit();
        } else {
            echo "Error: Could not create user.";
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
    <title>Add User</title>
    <link rel="stylesheet" href="../css/form.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('../../image.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            position: relative;
        }


        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            backdrop-filter: blur(6px);
            background-color: rgba(255, 255, 255, 0.2);
            /* Optional tint */
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            color: #a635df;
            font-size: 28px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        label {
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        textarea,
        input[type="date"],
        select {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: border 0.3s ease;
            width: 100%;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #a635df;
            outline: none;
        }

        input[type="submit"] {
            padding: 12px;
            background: linear-gradient(to right, #ff416c, #a635df);
            color: white;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #a635df, #ff416c);
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            h2 {
                font-size: 24px;
            }

            input[type="submit"] {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add User</h2>
        <form action="user_create.php" method="post" enctype="multipart/form-data">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <label for="image">Profile Image:</label>
            <input type="file" name="image" required>
            <input type="submit" value="Create User">
        </form>
        <p><a href="user_index.php">Return</a></p>
    </div>
</body>

</html>