<?php

session_start();
if (isset($_SESSION['admin_id'])) 
{
    header("Location: dashboard.php");
    exit();
}

include '../configs/user-process.php';

$user = new Users();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $adminData = $user->Login($username, $password, 'admin');
    if ($adminData) 
    {
        $_SESSION['admin_id'] = $adminData['id'];
        header("Location: dashboard.php");
        exit();
    }
    else 
    {
        $message = "<p style='color: red;'>Invalid username or password.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/form.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, #ff7e5f, #feb47b, #fd3a69, #a635df);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Gradient animation background */
        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Login Container */
        .container {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .container h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #a635df;
        }

        /* Form Elements */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 12px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #a635df;
        }

        /* Submit Button */
        input[type="submit"] {
            background: linear-gradient(to right, #ff7e5f, #a635df);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #a635df, #ff7e5f);
        }

        /* Responsive */
        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
    </style>
</head>

<body>
    <?php if (!empty($message)) echo $message; ?>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Back to <a href="../../index.php">Home</a></p>
    </div>
</body>

</html>