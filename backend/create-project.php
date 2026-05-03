<?php
include "db.php";
include "functions.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../index.html");
    exit();
}

$project_name = clean($_POST['project_name']);
$description = clean($_POST['description']);
$created_by = $_SESSION['user_id'];

$sql = "INSERT INTO projects(project_name, description, created_by)
        VALUES('$project_name', '$description', '$created_by')";

if (mysqli_query($conn, $sql)) {
    header("Location: ../admin/projects.php");
    exit();
} else {
    echo "Project creation failed";
}
?>