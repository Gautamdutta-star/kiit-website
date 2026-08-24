<?php
session_start();

$url = getenv('MYSQL_URL');
$db = parse_url($url);

$host = $db['host'];
$user = 'root';
$password = urldecode($db['pass']);
$database = ltrim($db['path'], '/');
$port = $db['port'] ?? 3306;

$conn = new mysqli($host, $user, $password, $database, $port);
if ($conn->connect_error) die("DB Error: " . $conn->connect_error);

$msg = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = md5($_POST['password']);  // ✅ same as DB stored (MD5)

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username=? AND password=?");
    if(!$stmt) die("Prepare Failed: " . $conn->error);

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $msg = "❌ Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
<style>
body{
    margin:0;
    font-family:"Segoe UI", Arial;
    background:#f3fff7;
    padding:40px;
}
.box{
    width:420px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.10);
    border:1px solid rgba(0,0,0,0.08);
}
h2{
    text-align:center;
    color:#0b7a36;
    margin-bottom:18px;
}
label{
    font-weight:800;
    color:#14532d;
}
input{
    width:100%;
    padding:10px;
    border:1px solid #aaa;
    border-radius:8px;
    margin:8px 0 14px 0;
    outline:none;
}
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    font-weight:900;
    font-size:15px;
    color:white;
    background: linear-gradient(135deg,#0b7a36,#16c35a);
}
button:hover{
    opacity:0.92;
}
.msg{
    text-align:center;
    font-weight:900;
    color:red;
    margin-bottom:10px;
}
</style>
</head>

<body>
<div class="box">
    <h2>Admin Login</h2>

    <?php if($msg!=""){ echo "<div class='msg'>$msg</div>"; } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter Admin Username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter Admin Password" required>

        <button name="login">LOGIN</button>
    </form>
</div>
</body>
</html>
