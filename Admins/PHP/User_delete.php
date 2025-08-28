<?php
// filepath: c:\wamp64\www\2nd_year\Task_Management_System\Admins\PHP\User_delete.php
require_once '../configs/crud.php';

$crud = new crud();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($crud->D($id)) {
        header("Location: user_index.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID specified.";
}
?>