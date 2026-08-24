<?php
include("admin_auth.php");

$url = getenv('MYSQL_URL');
$db = parse_url($url);

$host = $db['host'];
$user = 'root';
$password = urldecode($db['pass']);
$database = ltrim($db['path'], '/');
$port = $db['port'] ?? 3306;

$conn = new mysqli($host, $user, $password, $database, $port);
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
