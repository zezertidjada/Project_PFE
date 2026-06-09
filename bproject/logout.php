<?php
session_start();
require_once __DIR__ . '/config/app.php';

$_SESSION = [];
session_destroy();
header('Location: ' . bp_url('login.php'));
exit();
