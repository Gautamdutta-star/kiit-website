<?php
$url = getenv('MYSQL_URL');
$db = parse_url($url);

$host = $db['host'];
$user = 'root';
$password = urldecode($db['pass']);
$database = ltrim($db['path'], '/');
$port = $db['port'] ?? 3306;

$conn = new mysqli($host, $user, $password, $database, $port);
if($conn->connect_error) die("DB Error: " . $conn->connect_error);
$result = null;
$msg = "";
if(isset($_POST['search'])){
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("SELECT student_uid, fullname, status, program, branch, created_at, id
                            FROM admission_form
                            WHERE email=?
                            ORDER BY id DESC");
    if(!$stmt){
        $stmt = $conn->prepare("SELECT student_uid, fullname, status, program, branch, id
                                FROM admission_form
                                WHERE email=?
                                ORDER BY id DESC");
        if(!$stmt) die("Prepare Failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        $msg = "❌ No registration found for this email!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Find Unique IDs</title>
<style>
body{
  margin:0;
  font-family:"Segoe UI", Arial;
  background:#f3fff7;
  padding:30px;
}
.box{
  width:70%;
  margin:auto;
  background:white;
  padding:22px;
  border-radius:14px;
  box-shadow:0 10px 30px rgba(0,0,0,0.10);
  border:1px solid rgba(0,0,0,0.10);
}
h2{
  text-align:center;
  color:#0b7a36;
  font-size:28px;
  font-weight:900;
}
label{
  font-weight:900;
  color:#14532d;
  display:block;
  margin-top:10px;
  margin-bottom:6px;
}
input{
  width:100%;
  padding:10px;
  border-radius:10px;
  border:1px solid #aaa;
  outline:none;
  background:#f9fffb;
  font-size:14px;
}
button{
  margin-top:12px;
  width:100%;
  padding:12px;
  border:none;
  border-radius:12px;
  cursor:pointer;
  font-weight:900;
  background:linear-gradient(135deg,#0b7a36,#16c35a);
  color:white;
  font-size:15px;
}
.msg{
  text-align:center;
  font-weight:900;
  margin-top:12px;
  padding:10px;
  border-radius:12px;
  background:#fff3f3;
  border:1px solid #ffbcbc;
  color:#c62828;
}
table{
  width:100%;
  border-collapse:collapse;
  margin-top:18px;
  font-size:14px;
}
th{
  background:#0b7a36;
  color:white;
  padding:10px;
  text-align:center;
}
td{
  padding:10px;
  border:1px solid #ddd;
  text-align:center;
}
.status{
  font-weight:900;
  padding:6px 12px;
  border-radius:999px;
  display:inline-block;
}
.pending{ background:#fff3cd; color:#8a6d00; border:1px solid #ffeeba; }
.success{ background:#d1e7dd; color:#0f5132; border:1px solid #badbcc; }
.view-btn{
  background:#003a8c;
  color:white;
  padding:6px 12px;
  border-radius:8px;
  text-decoration:none;
  font-weight:900;
}
.view-btn:hover{ opacity:0.9; }
@media(max-width:900px){
  .box{ width:95%; }
}
</style>
</head>
<body>

<div class="box">
  <h2>🔍 Find Your Unique IDs (Email)</h2>
  <form method="POST">
    <label>Enter Your Email</label>
    <input type="email" name="email" placeholder="example@gmail.com" required>
    <button type="submit" name="search">Show My Unique IDs</button>
  </form>
  <?php if($msg!=""){ echo "<div class='msg'>$msg</div>"; } ?>
  <?php if($result && $result->num_rows > 0){ ?>
    <table>
      <tr>
        <th>Unique ID</th>
        <th>Full Name</th>
        <th>Program</th>
        <th>Branch</th>
        <th>Status</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){ ?>
      <tr>
        <td><b><?php echo htmlspecialchars($row['student_uid']); ?></b></td>
        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
        <td><?php echo htmlspecialchars($row['program']); ?></td>
        <td><?php echo htmlspecialchars($row['branch']); ?></td>
        <td>
          <?php
            if($row['status']=="Admission Successful"){
              echo "<span class='status success'>✅ Successful</span>";
            }else{
              echo "<span class='status pending'>⏳ Pending</span>";
            }
          ?>
        </td>
      </tr>
      <?php } ?>
    </table>
  <?php } ?>
</div>
</body>
</html>
