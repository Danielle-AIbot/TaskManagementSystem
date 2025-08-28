<?php

require_once '../configs/user-process.php';
session_start();

$users = new Users();
$users->Logout();
