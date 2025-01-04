<?php
session_start();
session_destroy();
header("Location: ../../Welcome_to_the_system.html");
exit();
