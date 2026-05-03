<?php
include "db.php";
include "functions.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$name = clean($_POST['name']);
$email = clean($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

if (mysqli_num_rows($check) > 0) {
    echo "
    <script>
        alert('Email already exists!');
        window.location='../admin/members.php';
    </script>";
    exit();
}

$sql = "INSERT INTO users(name,email,password,role)
        VALUES('$name','$email','$password','Member')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../admin/members.php");
    exit();
} else {
    echo "Failed to add member";
}
?>