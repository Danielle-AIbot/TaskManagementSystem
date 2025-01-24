<?php
session_start();
session_destroy();
header("Location: ../../AstroTask.php");
exit();
