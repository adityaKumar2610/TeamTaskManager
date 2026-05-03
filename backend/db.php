<?php
$host = "localhost";
$user = "u294201679_Divyanshu";
$pass = "Divyanshu@123#12";
$db   = "u294201679_TeamTask";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

session_start();
?>