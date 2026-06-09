<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "bproject_db";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

require_once __DIR__ . '/app.php';
?>