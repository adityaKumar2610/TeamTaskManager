<?php
include "backend/db.php";
include "backend/db.php";
include "includes/navbar.php";

if(!isset($_SESSION['user_id'])){
    header("Location:index.html");
    exit();
}

if($_SESSION['role']=="Admin"){
    header("Location:admin/dashboard.php");
}else{
    header("Location:member/dashboard.php");
}
exit();
?>