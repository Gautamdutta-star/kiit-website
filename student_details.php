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

$student = null;
$msg = "";

$uid_list_result = null;
$list_msg = "";

/* ✅ Flag to keep Forgot box open after submit */
$showForgotBox = false;

/* ✅ Search by Unique ID */
if(isset($_POST['search_uid'])){
    $uid = trim($_POST['student_uid']);

    $stmt = $conn->prepare("SELECT * FROM admission_form WHERE student_uid=? LIMIT 1");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $student = $res->fetch_assoc();
    } else {
        $msg = "❌ No record found for this Unique ID!";
    }
}

/* ✅ Find ALL Unique IDs by Email */
if(isset($_POST['find_uid_email'])){
    $email = trim($_POST['email']);
    $showForgotBox = true;

    $stmt2 = $conn->prepare(
        "SELECT student_uid, fullname, program, branch, status
         FROM admission_form
         WHERE email=?
         ORDER BY id DESC"
    );
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $uid_list_result = $stmt2->get_result();

    if($uid_list_result->num_rows == 0){
        $list_msg = "❌ No registration found for this email!";
    } else {
        $list_msg = "✅ Found ".$uid_list_result->num_rows." record(s).";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Check Details</title>

<style>
body{
  margin:0;
  font-family:"Segoe UI", Arial;
  background:#f3fff7;
  padding:30px;
}
.box{
  width:75%;
  margin:auto;
  background:white;
  padding:22px;
  border-radius:14px;
  box-shadow:0 10px 30px rgba(0,0,0,0.10);
}
h2{
  text-align:center;
  color:#0b7a36;
  font-size:28px;
  font-weight:900;
}
.section-title{
  font-size:18px;
  font-weight:900;
  color:#14532d;
  margin:18px 0 10px;
  padding-left:10px;
  border-left:5px solid #16c35a;
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
  background:#f9fffb;
}
button{
  margin-top:12px;
  width:100%;
  padding:12px;
  border:none;
  border-radius:12px;
  cursor:pointer;
  font-weight:900;
  color:white;
}
.btn-green{
  background:linear-gradient(135deg,#0b7a36,#16c35a);
}
.btn-blue{
  background:linear-gradient(135deg,#003a8c,#0b66ff);
}
.forgot-btn{
  margin-top:12px;
  background:#eafff0;
  border:1px solid rgba(11,122,54,0.20);
  color:#0b7a36;
  padding:10px;
  border-radius:12px;
  font-weight:900;
  cursor:pointer;
  text-align:center;
}
.hidden-box{
  margin-top:12px;
  padding:14px;
  border-radius:14px;
  background:#f6fbff;
  border:1px solid rgba(0,58,140,0.15);
}
.msg{
  margin-top:12px;
  padding:10px;
  border-radius:12px;
  font-weight:900;
  text-align:center;
}
.msg.error{
  background:#fff3f3;
  color:#c62828;
}
.msg.success{
  background:#eafff0;
  color:#0b7a36;
}
table{
  width:100%;
  border-collapse:collapse;
  margin-top:18px;
}
th{
  background:#0b7a36;
  color:white;
  padding:10px;
}
td{
  padding:10px;
  border:1px solid #ddd;
  text-align:center;
}
.status{
  padding:6px 12px;
  border-radius:999px;
  font-weight:900;
}
.pending{
  background:#fff3cd;
  color:#8a6d00;
}
.success{
  background:#d1e7dd;
  color:#0f5132;
}
</style>
</head>

<body>

<div class="box">
<h2>📄 Student Check Details</h2>

<!-- ✅ UID Search -->
<div class="section-title">Check Details by Unique ID</div>
<form method="POST">
  <label>Unique Student ID</label>
  <input type="text"
         name="student_uid"
         placeholder="Enter your Unique ID (e.g. KIIT202612345)"
         required>
  <button type="submit" name="search_uid" class="btn-green">
    Show My Details
  </button>
</form>

<div class="forgot-btn" onclick="toggleForgotBox()">
  ❓ Forgot Unique ID?
</div>

<!-- ✅ Forgot UID Box -->
<div class="hidden-box" id="forgotBox"
     style="<?php echo $showForgotBox ? 'display:block;' : 'display:none;'; ?>">

  <div class="section-title">Find Unique ID using Email</div>

  <form method="POST">
    <label>Email Address</label>
    <input type="email"
           name="email"
           placeholder="Enter registered email (e.g. student@gmail.com)"
           required>

    <button type="submit" name="find_uid_email" class="btn-blue">
      Show My Unique IDs
    </button>
  </form>

  <?php if($list_msg!=""){ ?>
    <div class="msg <?php echo strpos($list_msg,'❌')!==false?'error':'success'; ?>">
      <?php echo $list_msg; ?>
    </div>
  <?php } ?>

  <?php if($uid_list_result && $uid_list_result->num_rows > 0){ ?>
    <table>
      <tr>
        <th>Unique ID</th>
        <th>Full Name</th>
        <th>Program</th>
        <th>Branch</th>
        <th>Status</th>
      </tr>
      <?php while($r=$uid_list_result->fetch_assoc()){ ?>
      <tr>
        <td><?php echo $r['student_uid']; ?></td>
        <td><?php echo $r['fullname']; ?></td>
        <td><?php echo $r['program']; ?></td>
        <td><?php echo $r['branch']; ?></td>
        <td>
          <?php echo $r['status']=="Admission Successful"
            ? "<span class='status success'>Approved</span>"
            : "<span class='status pending'>Pending</span>"; ?>
        </td>
      </tr>
      <?php } ?>
    </table>
  <?php } ?>
</div>

<?php if($msg!=""){ ?>
  <div class="msg error"><?php echo $msg; ?></div>
<?php } ?>

<?php if($student){ ?>
<table>
<tr><th colspan="2">Student Information</th></tr>
<tr><td>Unique ID</td><td><?php echo $student['student_uid']; ?></td></tr>
<tr><td>Full Name</td><td><?php echo $student['fullname']; ?></td></tr>
<tr><td>Email</td><td><?php echo $student['email']; ?></td></tr>
<tr><td>Phone</td><td><?php echo $student['phone']; ?></td></tr>
<tr><td>Program</td><td><?php echo $student['program']; ?></td></tr>
<tr><td>Branch</td><td><?php echo $student['branch']; ?></td></tr>
<tr><td>Status</td>
<td>
<?php echo $student['status']=="Admission Successful"
? "<span class='status success'>Admission Successful</span>"
: "<span class='status pending'>Pending</span>"; ?>
</td></tr>
</table>
<?php } ?>

</div>

<script>
function toggleForgotBox(){
  var box=document.getElementById("forgotBox");
  box.style.display = (box.style.display==="block") ? "none" : "block";
}
</script>

</body>
</html>
