<?php
include "db.php";
include "functions.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$project_id = clean($_POST['project_id']);
$assigned_to = clean($_POST['assigned_to']);
$title = clean($_POST['title']);
$description = clean($_POST['description']);
$due_date = clean($_POST['due_date']);

$sql = "INSERT INTO tasks(project_id,assigned_to,title,description,due_date)
        VALUES('$project_id','$assigned_to','$title','$description','$due_date')";

if(mysqli_query($conn,$sql)){
    header("Location: ../admin/tasks.php");
    exit();
}else{
    echo "Task creation failed";
}
?>