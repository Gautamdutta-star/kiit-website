<?php
include("admin_auth.php");

$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv("MYSQLPORT")
);
if($conn->connect_error) die("DB Error: " . $conn->connect_error);

if(!isset($_GET['id'])){
    die("ID not found!");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("UPDATE admission_form SET status='Admission Successful' WHERE id=?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    header("Location: admission_display.php");
    exit();
}else{
    echo "Approve Failed!";
}
?>
