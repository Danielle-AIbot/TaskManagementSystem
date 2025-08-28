<?php
require_once '../configs/db.php';
include '../configs/crud.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../index.php");
    exit();
}

$crud = new crud();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Fetch the current user to get image and role (since not updated here)
    $user = null;
    $users = $crud->R();
    foreach ($users as $u) {
        if ($u['id'] == $id) {
            $user = $u;
            break;
        }
    }
    if (!$user) {
        echo "User not found.";
        exit();
    }
    $image = $user['profpic'];
    $role = $user['role'];

    // Update user (add password update if your table supports it)
    $crud->U($id, $name, $email, $image, $role);

    // If you want to update password, add a method in crud for password update

    header("Location: user_index.php");
    exit();
} else {
    // Fetch the user details to pre-fill the form
    $id = $_GET['id'];
    $user = null;
    $users = $crud->R();
    foreach ($users as $u) {
        if ($u['id'] == $id) {
            $user = $u;
            break;
        }
    }
    if (!$user) {
        echo "User not found.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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
        input[type="email"],
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
        <h2>Edit User</h2>
        <form action="User_update.php" method="post">

            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <label for="username">Username:</label>

            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <label for="email">Email:</label>

            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="password">Password:</label>

            <input type="password" name="password" required>
            <input type="submit" value="Update">
        </form>
        <p><a href="user_index.php">Return</a></p>
    </div>
</body>

</html>