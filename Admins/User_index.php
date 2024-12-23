<?php
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in admin's username
$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username FROM admin WHERE id = $admin_id";
$result_admin = mysqli_query($conn, $sql_admin);
$admin = mysqli_fetch_assoc($result_admin);

// Set the account image based on the username
$account_image = 'account.jpg'; // Default image
if ($admin['username'] == 'Danielle Mae') {
    $account_image = 'dani.jpg';
} elseif ($admin['username'] == 'Abegail') {
    $account_image = 'abby.jpg';
} elseif ($admin['username'] == 'Maria Luzviminda') {
    $account_image = 'Luzvie.jpg';
}

// Fetch all users from the database
$sql_users = "SELECT * FROM user";
$result_users = mysqli_query($conn, $sql_users);
$users = mysqli_fetch_all($result_users, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link rel="stylesheet" href="Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="menubar">
        <div class="account">
            <img src="<?php echo $account_image; ?>" alt="Account Image">
            <div class="username"><?php echo $admin['username']; ?></div>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="Admin_index.php"><i class="fas fa-user"></i> Admins</a></li>
            <li><a href="User_index.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="user">
        <h2>Users List</h2>
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
                            <img src="uploads/<?php echo $user['profpicture']; ?>" width="100">
                        </td>
                        <td>
                            <a href="update.php?id=<?php echo $user['id']; ?>"><i class="fas fa-edit action-icon"></i> Edit</a> |
                            <a href="delete.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><i class="fas fa-trash-alt action-icon"></i> Delete</a>
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