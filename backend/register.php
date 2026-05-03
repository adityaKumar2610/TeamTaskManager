<?php
include "db.php";
include "functions.php";

$name = clean($_POST['name']);
$email = clean($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

if(mysqli_num_rows($check) > 0){
    echo "Email already exists";
    exit();
}

$sql = "INSERT INTO users(name,email,password,role)
VALUES('$name','$email','$password','Member')";

if(mysqli_query($conn,$sql)){
    echo "Registration successful";
}else{
    echo "Registration failed";
}
?>