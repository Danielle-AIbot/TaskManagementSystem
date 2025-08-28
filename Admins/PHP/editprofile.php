<?php
require_once '../configs/db.php';
require_once '../configs/crud.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

$crud = new crud();
$user_id = $_SESSION['admin_id'];
$user = $crud->getUserById($user_id);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $user['role']; // Don't allow role change here for security

    // Handle profile image upload if needed
    $profile_image = $user['profpic'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../Profile/";
        $profile_image = basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $profile_image);
    }

    if ($crud->U($user_id, $username, $email, $profile_image, $role)) {
        header("Location: tasks.php");
        exit();
    } else {
        $error = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
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
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 3px solid #a635df;
            text-align: center;
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
        input[type="email"],
        input[type="password"],
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
        <h2>Edit Profile</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?php if (!empty($user['profpic'])): ?>
                <img src="../../profile/<?php echo htmlspecialchars($user['profpic']); ?>" class='profilepic' width="100" alt="Profile Image">
            <?php endif; ?>
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label>Profile Image:</label>
            <input type="file" name="image">
            <input type="submit" value="Update Profile">
        </form>
        <p><a href="user_index.php">Return</a></p>
    </div>
</body>

</html>