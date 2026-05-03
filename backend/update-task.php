<?php
include "db.php";
include "functions.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

$task_id = clean($_POST['task_id']);
$status = clean($_POST['status']);

mysqli_query($conn,"
    UPDATE tasks
    SET status='$status'
    WHERE id='$task_id'
");

if ($_SESSION['role'] == "Member") {
    header("Location: ../member/my-tasks.php");
} else {
    header("Location: ../admin/tasks.php");
}
exit();
?>