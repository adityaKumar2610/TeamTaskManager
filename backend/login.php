<?php
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($sql)==1){
    $user = mysqli_fetch_assoc($sql);

    if(password_verify($password,$user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        echo "success";
    }else{
        echo "Wrong password";
    }
}else{
    echo "User not found";
}
?>