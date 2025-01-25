<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/User_index.php
include '../../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in admin's username and profile picture
$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username, pic FROM admin WHERE id = $admin_id";
$result_admin = mysqli_query($conn, $sql_admin);
$admin = mysqli_fetch_assoc($result_admin);

// Set the account image from the profile picture provided by the admin
$account_image = !empty($admin['pic']) ? $admin['pic'] : 'default.jpg'; // Default image if no profile picture is provided

// Fetch all users from the database
$sql_users = "SELECT * FROM user";
$result_users = mysqli_query($conn, $sql_users);
$users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users Management</title>
    <link rel="stylesheet" href="../CSS/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="menubar">
        <div class="account">
            <img src="../../Profile/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
            <a href="EditProfile.php" class="icon-btn"><i class="fas fa-user-edit"></i></a>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Students</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="user">
        <h2>Users Management</h2>
        <button onclick="window.location.href='assign_task.php'" class="btn">Assign Task</button>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Profile Image</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <img src="../../Users/Uploads/<?php echo $user['profpicture']; ?>" width="100">
                        </td>
                        <td>
                            <a href="user_update.php?id=<?php echo $user['id']; ?>"><i class="fas fa-edit action-icon"></i> Edit</a> |
                            <a href="user_delete.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fas fa-trash-alt action-icon"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>

</html>