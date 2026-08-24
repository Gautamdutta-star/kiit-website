<?php
echo "HOST: " . (getenv('MYSQLHOST') ?: 'EMPTY') . "<br>";
echo "USER: " . (getenv('MYSQLUSER') ?: 'EMPTY') . "<br>";
echo "DB: " . (getenv('MYSQLDATABASE') ?: 'EMPTY') . "<br>";
echo "PORT: " . (getenv('MYSQLPORT') ?: 'EMPTY') . "<br>";
exit;
$conn = new mysqli(
    getenv('MYSQLHOST'),
    getenv('MYSQLUSER'),
    getenv('MYSQLPASSWORD'),
    getenv('MYSQLDATABASE'),
    (int) getenv('MYSQLPORT')
);

if ($conn->connect_error) {
    die("DB Error: " . $conn->connect_error);
}

$student = null;
$msg = "";

if(isset($_POST['search'])){

    $uid = trim($_POST['student_uid']);

    $stmt = $conn->prepare("SELECT * FROM admission_form WHERE student_uid=?");
    if(!$stmt) die("Prepare Failed: " . $conn->error);

    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $student = $res->fetch_assoc();
    } else {
        $msg = "❌ Record not found! Please enter correct Unique ID.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Admission (Unique ID)</title>
<style>
body{
  margin:0;
  font-family:"Segoe UI", Arial;
  background:#f3fff7;
  padding:30px;
}
.container{
  width:60%;
  margin:auto;
  background:white;
  padding:25px 30px;
  border-radius:14px;
  box-shadow:0 10px 30px rgba(0,0,0,0.10);
}
h2{
  text-align:center;
  color:#0b7a36;
  font-size:28px;
  font-weight:900;
}
label{
  font-weight:800;
  color:#14532d;
  display:block;
  margin-top:10px;
}
input,select,textarea{
  width:100%;
  padding:10px;
  border-radius:8px;
  border:1px solid #aaa;
}
textarea{ resize:none; height:70px; }
.row{ display:flex; gap:12px; }
.row .col{ flex:1; }
button{
  width:100%;
  margin-top:12px;
  padding:12px;
  border:none;
  border-radius:10px;
  font-weight:900;
  color:white;
  cursor:pointer;
}
.search-btn{ background:#0b7a36; }
.update-btn{ background:#003a8c; }
.msg{ text-align:center; color:red; font-weight:900; margin-top:12px; }
.lock{
  margin-top:15px;
  padding:12px;
  background:#d1e7dd;
  color:#0f5132;
  border-radius:10px;
  font-weight:900;
  text-align:center;
}
@media(max-width:900px){
  .container{ width:95%; }
  .row{ flex-direction:column; }
}
</style>
</head>

<body>
<div class="container">
<h2>✏️ Edit Admission Form</h2>

<!-- SEARCH -->
<form method="POST">
  <label>Enter Your Unique Student ID</label>
  <input type="text" name="student_uid" placeholder="KIIT2026XXXX" required>
  <button type="submit" name="search" class="search-btn">SEARCH</button>
</form>

<?php if($msg!=""){ echo "<div class='msg'>$msg</div>"; } ?>

<?php if($student){ ?>

<?php if($student['status']=="Admission Successful"){ ?>
  <div class="lock">
    ✅ Admission Approved <br>
    🔒 Editing is disabled now
  </div>
<?php } else { ?>

<hr>

<!-- UPDATE FORM -->
<form action="student_update.php" method="POST">

  <!-- ✅ MOST IMPORTANT FIX -->
  <input type="hidden" name="student_uid"
         value="<?php echo htmlspecialchars($student['student_uid']); ?>">

  <label>Unique ID</label>
  <input type="text"
         value="<?php echo htmlspecialchars($student['student_uid']); ?>"
         readonly>

  <label>Full Name</label>
  <input type="text" name="fullname"
         value="<?php echo htmlspecialchars($student['fullname']); ?>" required>

  <div class="row">
    <div class="col">
      <label>Father Name</label>
      <input type="text" name="fathername"
             value="<?php echo htmlspecialchars($student['fathername']); ?>" required>
    </div>
    <div class="col">
      <label>Mother Name</label>
      <input type="text" name="mothername"
             value="<?php echo htmlspecialchars($student['mothername']); ?>" required>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label>DOB</label>
      <input type="date" name="dob"
             value="<?php echo $student['dob']; ?>" required>
    </div>
    <div class="col">
      <label>Gender</label>
      <select name="gender" required>
        <option value="Male"   <?php if($student['gender']=="Male") echo "selected"; ?>>Male</option>
        <option value="Female" <?php if($student['gender']=="Female") echo "selected"; ?>>Female</option>
        <option value="Other"  <?php if($student['gender']=="Other") echo "selected"; ?>>Other</option>
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <label>Phone</label>
      <input type="text" name="phone"
             value="<?php echo htmlspecialchars($student['phone']); ?>" required>
    </div>
    <div class="col">
      <label>Email (locked)</label>
      <input type="email"
             value="<?php echo htmlspecialchars($student['email']); ?>" readonly>
    </div>
  </div>

  <label>Address</label>
  <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>

  <div class="row">
    <div class="col">
      <label>Program</label>
      <input type="text" name="program"
             value="<?php echo htmlspecialchars($student['program']); ?>" required>
    </div>
    <div class="col">
      <label>Branch</label>
      <input type="text" name="branch"
             value="<?php echo htmlspecialchars($student['branch']); ?>" required>
    </div>
  </div>

  <button type="submit" name="update" class="update-btn">UPDATE</button>
</form>

<?php } ?>
<?php } ?>

</div>
</body>
</html>
