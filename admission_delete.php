<?php
include("admin_auth.php");

$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    getenv('MYSQLPORT')
);
if($conn->connect_error) die("DB Error: " . $conn->connect_error);

if(!isset($_GET['id'])){
    die("ID not found!");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM admission_form WHERE id=?");
$stmt->bind_param("i",$id);

if($stmt->execute()){
    header("Location: admission_delete_list.php");
    exit();
} else {
    echo "Delete Failed!";
}

$conn->close();
?>
