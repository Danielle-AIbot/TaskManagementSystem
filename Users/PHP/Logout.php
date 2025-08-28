<?php

require_once '../../admins/configs/user-process.php';
session_start();

$users = new Users();
$users->Logout();
// Redirect to login page after logout
header("Location: ../../index.php");