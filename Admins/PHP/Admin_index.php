<?php
// filepath: /c:/wamp64/www/2nd_year/Task_Management_System/Admins/PHP/Admin_index.php
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

// Set the account image from the profile picture provided by the user
$account_image = !empty($admin['pic']) ? $admin['pic'] : 'default.jpg'; // Default image if no profile picture is provided

// Fetch all admins from the database
$sql_admins = "SELECT * FROM admin";
$result_admins = mysqli_query($conn, $sql_admins);
$admins = mysqli_fetch_all($result_admins, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <link rel="stylesheet" href="../CSS/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="menubar">
        <div class="account">
            <img src="../../Profile/<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="Admin_index.php"><i class="fas fa-user"></i> Admins</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="dashboard">
        <h2>Admin List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($admins)) : ?>
                <?php foreach ($admins as $admin) : ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><?php echo $admin['username']; ?></td>
                        <td>
                            <a href="admin_update.php?id=<?php echo $admin['id']; ?>"><i class="fas fa-edit action-icon"></i> Edit</a> |
                            <a href="admin_delete.php?id=<?php echo $admin['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fas fa-trash-alt action-icon"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No admin found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>

</body>

</html>