<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: /bproject/login.php");
exit();
?>