<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AstroTask</title>
    <link rel="stylesheet" href="/task_management_system/Users/CSS/Style.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #ff7e5f, #feb47b, #fd3a69, #8e44ad);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
        }

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

        .header {
            text-align: center;
            color: #fff;
            margin-bottom: 40px;
            margin-right: 100px;
            font-size: 30px;
        }

        main {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            max-width: 450px;
            padding: 40px;
            text-align: center;
        }

        .hero h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 15px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(90deg, #ff9966, #ff5e62);
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: linear-gradient(90deg, #ff5e62, #ff9966);
        }


        .admin {
            font-size: 12px;
            color: #888;
            text-decoration: none;
        }

        .admin:hover {
            color: #ff5e62;
        }

        @media (max-width: 1024px) {
            .buttons {
                gap: 12px;
            }

            h2 {
                text-align: center;
                font-size: 26px;
            }

            .btn {
                font-size: 15px;
                padding: 11px 18px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
                align-items: center;
                padding: 20px;
            }

            h2 {
                text-align: center;
                font-size: 24px;
            }

            .buttons {
                width: 100%;
                align-items: center;
                gap: 12px;
            }

            .btn {
                width: 90%;
                font-size: 14px;
                padding: 10px 16px;
            }
        }

        @media (max-width: 480px) {
            h2 {
                text-align: center;
                font-size: 22px;
                margin-bottom: 15px;
            }

            .btn {
                width: 100%;
                padding: 10px 15px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <section class="header">
        <h1>Task Management System</h1>
        <p>Tasks. Deadlines. Teamwork—Simplified.</p>
    </section>

    <main>
        <section class="hero">
            <h2>Get Organized. Get Things Done.</h2>
            <p>Helps you stay on top of your tasks, boost your productivity, and achieve your goals.</p>
            <div class="buttons">
                <a href="/2nd_year/task_management_system/Users/php/Login.php" class="btn">Login</a>
            </div>
            <p>
                <a href="/2nd_year/task_management_system/Admins/php/Login.php" class="admin">Are you an admin?</a>
            </p>
        </section>
    </main>
</body>

</html>